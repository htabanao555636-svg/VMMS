<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\RequestStatus;
use App\Models\RequestAssignment;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of service requests (staff version with filters)
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['customer', 'vehicle', 'services', 'mechanic', 'assignedBy', 'statusHistory']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by mechanic
        if ($request->has('mechanic_id') && $request->mechanic_id !== '') {
            $query->where('mechanic_id', $request->mechanic_id);
        }

        // Filter by assigned staff
        if ($request->has('assigned_by') && $request->assigned_by !== '') {
            $query->where('assigned_by', $request->assigned_by);
        }

        // Search by customer name or email
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->whereHas('customer', function (Builder $q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $serviceRequests = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        $mechanics = Mechanic::where('status', 'active')->get();
        $staff = User::whereIn('role', ['admin', 'staff'])->get();
        $statuses = ['pending', 'approved', 'in_progress', 'completed', 'cancelled'];

        return view('Staff.service-request', compact('serviceRequests', 'mechanics', 'staff', 'statuses'));
    }

    /**
     * Show the form for creating a new service request
     */
    public function create()
    {
        $customers = User::where('role', 'user')->get();
        $mechanics = Mechanic::where('status', 'active')->get();
        $services = Service::where('status', 'active')->with('category')->get();
        
        return view('Staff.service-requests.create', compact('customers', 'mechanics', 'services'));
    }

    /**
     * Store a newly created service request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'requested_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'staff_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled',
        ]);

        try {
            \DB::beginTransaction();

            $serviceRequest = new ServiceRequest([
                'customer_id' => $validated['customer_id'],
                'mechanic_id' => $validated['mechanic_id'] ?? null,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'status' => $validated['status'],
                'requested_date' => $validated['requested_date'],
                'notes' => $validated['notes'] ?? null,
                'staff_notes' => $validated['staff_notes'] ?? null,
                'total_amount' => 0,
            ]);

            $serviceIds = $validated['services'];
            $selectedServices = Service::whereIn('id', $serviceIds)->get();
            $totalAmount = $selectedServices->sum('price');
            $serviceRequest->total_amount = $totalAmount;

            $serviceRequest->save();
            $serviceRequest->services()->attach($serviceIds);

            if ($validated['mechanic_id']) {
                RequestAssignment::create([
                    'service_request_id' => $serviceRequest->id,
                    'mechanic_id' => $validated['mechanic_id'],
                    'status' => 'assigned',
                    'assigned_at' => now(),
                ]);
            }

            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status' => $validated['status'],
                'notes' => 'Request created by ' . auth()->user()->name,
                'changed_by' => auth()->id(),
            ]);

            \DB::commit();
            return redirect()->route('staff.service-request')->with('success', 'Service request created successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to create service request: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified service request
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'customer',
            'mechanic',
            'vehicle',
            'services',
            'payments',
            'assignedBy',
            'mechanicAssignments.mechanic',
            'statusHistory.changedBy',
        ]);
        
        return view('Staff.service-request-detail', compact('serviceRequest'));
    }

    /**
     * Update the specified service request (staff version - full update)
     */
    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'completed_date' => 'nullable|date|after_or_equal:requested_date',
            'notes' => 'nullable|string|max:1000',
            'staff_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled',
        ]);

        try {
            \DB::beginTransaction();

            // Track if status changed
            $statusChanged = $serviceRequest->status !== $validated['status'];
            $mechanicChanged = $serviceRequest->mechanic_id !== ($validated['mechanic_id'] ?? null);

            // Update services if provided
            if (isset($validated['services'])) {
                $serviceRequest->services()->sync($validated['services']);
                
                // Recalculate total amount
                $totalAmount = Service::whereIn('id', $validated['services'])->sum('price');
                $serviceRequest->total_amount = $totalAmount;
            }

            // Update mechanic assignment
            $oldMechanicId = $serviceRequest->mechanic_id;
            $newMechanicId = $validated['mechanic_id'] ?? null;

            if ($mechanicChanged) {
                $serviceRequest->mechanic_id = $newMechanicId;

                // If new mechanic is assigned, create assignment record and update assigned_by
                if ($newMechanicId) {
                    $serviceRequest->assigned_by = auth()->id();
                    $serviceRequest->assigned_at = now();

                    RequestAssignment::create([
                        'service_request_id' => $serviceRequest->id,
                        'mechanic_id' => $newMechanicId,
                        'status' => 'assigned',
                        'assigned_at' => now(),
                    ]);
                }
            }

            // Update other fields
            $serviceRequest->completed_date = $validated['completed_date'] ?? null;
            $serviceRequest->notes = $validated['notes'] ?? null;
            $serviceRequest->staff_notes = $validated['staff_notes'] ?? ($serviceRequest->staff_notes ?? null);
            $serviceRequest->status = $validated['status'];

            // When service is marked as completed and has remaining balance
            // Trigger balance payment workflow
            if ($validated['status'] === 'completed' && 
                $serviceRequest->payment_status === 'downpayment_verified' && 
                $serviceRequest->remaining_balance > 0) {
                // Don't change status - it stays as downpayment_verified
                // Customer will now see this service in their payables
                // and can upload their balance payment proof
            }

            $serviceRequest->save();

            // Log status change
            if ($statusChanged) {
                RequestStatus::create([
                    'service_request_id' => $serviceRequest->id,
                    'status' => $validated['status'],
                    'notes' => 'Status updated to ' . $validated['status'],
                    'changed_by' => auth()->id(),
                ]);
            }

            \DB::commit();
            return redirect()->route('staff.service-request.show', $serviceRequest)
                ->with('success', 'Service request updated successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to update service request: ' . $e->getMessage());
        }
    }

    /**
     * Update service request status and log it (dedicated status update)
     */
    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
            'completed_date' => 'nullable|date',
        ]);

        try {
            \DB::beginTransaction();

            $serviceRequest->status = $validated['status'];
            
            if ($validated['status'] === 'completed' && !$serviceRequest->completed_date) {
                $serviceRequest->completed_date = $validated['completed_date'] ?? now()->toDateString();
            }

            $serviceRequest->save();

            // Log the status change with staff attribution
            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? 'Status changed to ' . $validated['status'],
                'changed_by' => auth()->id(),
            ]);

            \DB::commit();
            return back()->with('success', 'Service request status updated successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to update service request status: ' . $e->getMessage());
        }
    }

    /**
     * Assign a mechanic to a service request
     */
    public function assignMechanic(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'mechanic_id' => 'required|exists:mechanics,id',
            'staff_notes' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            // Verify mechanic is active
            $mechanic = Mechanic::findOrFail($validated['mechanic_id']);
            if ($mechanic->status !== 'active') {
                return back()->with('error', 'This mechanic is not available for assignment');
            }

            // Update mechanic on service request with staff tracking
            $serviceRequest->mechanic_id = $validated['mechanic_id'];
            $serviceRequest->assigned_by = auth()->id();
            $serviceRequest->assigned_at = now();
            
            if ($validated['staff_notes']) {
                $serviceRequest->staff_notes = $validated['staff_notes'];
            }

            $serviceRequest->save();

            // Create assignment record
            RequestAssignment::create([
                'service_request_id' => $serviceRequest->id,
                'mechanic_id' => $validated['mechanic_id'],
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            // Log in status history
            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status' => $serviceRequest->status,
                'notes' => auth()->user()->name . ' assigned mechanic: ' . $mechanic->name,
                'changed_by' => auth()->id(),
            ]);

            \DB::commit();
            return back()->with('success', 'Mechanic assigned successfully by ' . auth()->user()->name);
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to assign mechanic: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified service request
     */
    public function edit(ServiceRequest $serviceRequest)
    {
        $customers = User::where('role', 'user')->get();
        $mechanics = Mechanic::where('status', 'active')->get();
        $services = Service::where('status', 'active')->with('category')->get();
        
        $serviceRequest->load(['services']);
        
        return view('Staff.service-requests.edit', compact('serviceRequest', 'customers', 'mechanics', 'services'));
    }

    /**
     * Remove the specified service request
     */
    public function destroy(ServiceRequest $serviceRequest)
    {
        try {
            $serviceRequest->delete();
            return redirect()->route('staff.service-request')->with('success', 'Service request deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete service request: ' . $e->getMessage());
        }
    }
}
