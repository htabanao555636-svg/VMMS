<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\RequestStatus;
use App\Models\RequestAssignment;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\User;
use App\Models\WheelerCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ServiceRequestController extends Controller
{
    private function routePrefix(): string
    {
        return auth()->user()->role === 'staff' ? 'staff' : 'admin';
    }

    public function index(Request $request)
    {
        $query = ServiceRequest::with(['customer', 'services', 'mechanic', 'assignedBy', 'statusHistory']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mechanic_id')) {
            $query->where('mechanic_id', $request->mechanic_id);
        }

        if ($request->filled('assigned_by')) {
            $query->where('assigned_by', $request->assigned_by);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('customer', function (Builder $q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $serviceRequests = $query->orderBy('created_at', 'desc')->paginate(5)->appends(request()->query());
        $mechanics  = Mechanic::where('status', 'active')->get();
        $staff      = User::whereIn('role', ['admin', 'staff'])->get();
        $statuses   = ['pending', 'approved', 'in_progress', 'completed', 'cancelled'];
        $categories = WheelerCategory::all();

        return view('admin.service-request', compact(
            'serviceRequests',
            'mechanics',
            'staff',
            'statuses',
            'categories'
        ));
    }

    public function create()
    {
        $customers  = User::where('role', 'customer')->get();
        $mechanics  = Mechanic::where('status', 'active')->get();
        $services   = Service::where('status', 'active')->with('wheelerCategory')->get();
        $categories = WheelerCategory::all();

        return view('admin.service-requests.create', compact('customers', 'mechanics', 'services', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'    => 'required|exists:users,id',
            'vehicle_type'   => 'required|string|max:100',
            'services'       => 'required|array|min:1',
            'services.*'     => 'exists:services,id',
            'mechanic_id'    => 'nullable|exists:mechanics,id',
            'requested_date' => 'required|date',
            'notes'          => 'nullable|string|max:1000',
            'staff_notes'    => 'nullable|string|max:1000',
            'status'         => 'required|in:pending,approved,in_progress,completed,cancelled',
        ]);

        try {
            \DB::beginTransaction();

            $serviceRequest = new ServiceRequest([
                'customer_id'    => $validated['customer_id'],
                'vehicle_type'   => $validated['vehicle_type'],
                'mechanic_id'    => $validated['mechanic_id'] ?? null,
                'assigned_by'    => auth()->id(),
                'assigned_at'    => now(),
                'status'         => $validated['status'],
                'requested_date' => $validated['requested_date'],
                'notes'          => $validated['notes'] ?? null,
                'staff_notes'    => $validated['staff_notes'] ?? null,
                'total_amount'   => 0,
            ]);

            // Auto-set completed_date if created with completed status
            if ($validated['status'] === 'completed') {
                $serviceRequest->completed_date = now()->toDateString();
            }

            $serviceIds = $validated['services'];
            $selectedServices = Service::whereIn('id', $serviceIds)->get();
            $serviceRequest->total_amount = $selectedServices->sum('price');
            $serviceRequest->save();

            $serviceRequest->services()->attach($serviceIds);

            if ($validated['mechanic_id']) {
                RequestAssignment::create([
                    'service_request_id' => $serviceRequest->id,
                    'mechanic_id'        => $validated['mechanic_id'],
                    'status'             => 'assigned',
                    'assigned_at'        => now(),
                ]);
            }

            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status'             => $validated['status'],
                'notes'              => 'Request created by ' . auth()->user()->name,
                'changed_by'         => auth()->id(),
            ]);

            \DB::commit();

            return redirect()
                ->route($this->routePrefix() . '.service-request')
                ->with('success', 'Service request created successfully');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to create service request: ' . $e->getMessage());
        }
    }

    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load([
            'customer',
            'mechanic',
            'services',
            'payments',
            'assignedBy',
            'mechanicAssignments.mechanic',
            'statusHistory.changedBy',
        ]);

        return view('admin.service-requests.show', compact('serviceRequest'));
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        $customers  = User::where('role', 'customer')->get();
        $mechanics  = Mechanic::where('status', 'active')->get();
        $services   = Service::where('status', 'active')->with('wheelerCategory')->get();
        $categories = WheelerCategory::all();

        $serviceRequest->load(['services']);

        return view('admin.service-requests.edit', compact('serviceRequest', 'customers', 'mechanics', 'services', 'categories'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'vehicle_type' => 'nullable|string|max:100',
            'services'     => 'nullable|array',
            'services.*'   => 'exists:services,id',
            'mechanic_id'  => 'nullable|exists:mechanics,id',
            'notes'        => 'nullable|string|max:1000',
            'staff_notes'  => 'nullable|string|max:1000',
            'status'       => 'required|in:pending,approved,in_progress,completed,cancelled',
        ]);

        try {
            \DB::beginTransaction();

            $statusChanged   = $serviceRequest->status !== $validated['status'];
            // FIX: cast to int to avoid strict type mismatch between DB int and form string
            $mechanicChanged = (int) $serviceRequest->mechanic_id !== (int) ($validated['mechanic_id'] ?? 0);

            if (isset($validated['services'])) {
                $serviceRequest->services()->sync($validated['services']);
                $serviceRequest->total_amount = Service::whereIn('id', $validated['services'])->sum('price');
            }

            if ($mechanicChanged) {
                $newMechanicId = $validated['mechanic_id'] ?? null;
                $serviceRequest->mechanic_id = $newMechanicId;

                if ($newMechanicId) {
                    $serviceRequest->assigned_by = auth()->id();
                    $serviceRequest->assigned_at = now();

                    RequestAssignment::create([
                        'service_request_id' => $serviceRequest->id,
                        'mechanic_id'        => $newMechanicId,
                        'status'             => 'assigned',
                        'assigned_at'        => now(),
                    ]);
                }
            }

            $serviceRequest->vehicle_type = $validated['vehicle_type'] ?? $serviceRequest->vehicle_type;
            $serviceRequest->notes        = $validated['notes'] ?? null;
            $serviceRequest->staff_notes  = $validated['staff_notes'] ?? ($serviceRequest->staff_notes ?? null);
            $serviceRequest->status       = $validated['status'];

            // Auto-set completed_date: set when completed, clear when any other status
            if ($validated['status'] === 'completed' && !$serviceRequest->completed_date) {
                $serviceRequest->completed_date = now()->toDateString();
            } elseif ($validated['status'] !== 'completed') {
                $serviceRequest->completed_date = null;
            }

            $serviceRequest->save();

            if ($validated['status'] === 'completed' &&
                $serviceRequest->payment_status === 'downpayment_verified' &&
                ($serviceRequest->remaining_balance ?? 0) > 0) {

                \App\Models\Billing::updateOrCreate(
                    ['service_request_id' => $serviceRequest->id],
                    [
                        'customer_id'        => $serviceRequest->customer_id,
                        'total_amount'       => $serviceRequest->total_amount,
                        'downpayment_amount' => $serviceRequest->downpayment_amount,
                        'remaining_balance'  => $serviceRequest->remaining_balance,
                        'payment_status'     => 'downpayment_verified',
                        'verified_by'        => auth()->id(),
                        'verified_at'        => now(),
                    ]
                );
            }

            if ($statusChanged) {
                RequestStatus::create([
                    'service_request_id' => $serviceRequest->id,
                    'status'             => $validated['status'],
                    'notes'              => 'Status updated to ' . $validated['status'] . ' by ' . auth()->user()->name,
                    'changed_by'         => auth()->id(),
                ]);
            }

            \DB::commit();

            return redirect()
                ->route($this->routePrefix() . '.service-request')
                ->with('success', 'Service request updated successfully');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to update service request: ' . $e->getMessage());
        }
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        try {
            $serviceRequest->delete();
            return redirect()
                ->route($this->routePrefix() . '.service-request')
                ->with('success', 'Service request deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete service request: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,in_progress,completed,cancelled',
            'notes'  => 'nullable|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();

            $serviceRequest->status = $validated['status'];

            // Auto-set completed_date: set when completed, clear when any other status
            if ($validated['status'] === 'completed' && !$serviceRequest->completed_date) {
                $serviceRequest->completed_date = now()->toDateString();
            } elseif ($validated['status'] !== 'completed') {
                $serviceRequest->completed_date = null;
            }

            $serviceRequest->save();

            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status'             => $validated['status'],
                'notes'              => $validated['notes'] ?? 'Status changed to ' . $validated['status'] . ' by ' . auth()->user()->name,
                'changed_by'         => auth()->id(),
            ]);

            \DB::commit();
            return back()->with('success', 'Service request status updated successfully');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to update service request status: ' . $e->getMessage());
        }
    }

    public function assignMechanic(Request $request, ServiceRequest $serviceRequest)
    {
        $validated = $request->validate([
            'mechanic_id' => 'required|exists:mechanics,id',
            'staff_notes' => 'nullable|string|max:500',
        ]);

        try {
            \DB::beginTransaction();

            $mechanic = Mechanic::findOrFail($validated['mechanic_id']);
            if ($mechanic->status !== 'active') {
                return back()->with('error', 'This mechanic is not available for assignment');
            }

            $serviceRequest->mechanic_id = $validated['mechanic_id'];
            $serviceRequest->assigned_by = auth()->id();
            $serviceRequest->assigned_at = now();

            if ($validated['staff_notes']) {
                $serviceRequest->staff_notes = $validated['staff_notes'];
            }

            $serviceRequest->save();

            RequestAssignment::create([
                'service_request_id' => $serviceRequest->id,
                'mechanic_id'        => $validated['mechanic_id'],
                'status'             => 'assigned',
                'assigned_at'        => now(),
            ]);

            RequestStatus::create([
                'service_request_id' => $serviceRequest->id,
                'status'             => $serviceRequest->status,
                'notes'              => auth()->user()->name . ' assigned mechanic: ' . $mechanic->name,
                'changed_by'         => auth()->id(),
            ]);

            \DB::commit();
            return back()->with('success', 'Mechanic assigned successfully by ' . auth()->user()->name);

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to assign mechanic: ' . $e->getMessage());
        }
    }
}
