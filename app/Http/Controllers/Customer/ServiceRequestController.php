<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceRequestController extends Controller
{
    /**
     * Display listing of customer's service requests
     */
    public function index()
    {
        $serviceRequests = ServiceRequest::where('customer_id', Auth::id())
            ->with(['services', 'mechanic'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        return view('customer.service-requests.index', compact('serviceRequests'));
    }

    /**
     * Display the specified service request
     */
    public function show(ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $serviceRequest->load(['services', 'mechanic']);
        return view('customer.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Display customer's services with status tracking
     */
    public function myServices()
    {
        $serviceRequests = ServiceRequest::where('customer_id', Auth::id())
            ->with(['services', 'mechanic'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        return view('customer.my-services', compact('serviceRequests'));
    }

    /**
     * Upload proof of payment for a service request
     */
        public function getServices()
    {
        $services = Service::where('status', 'active')
            ->select('id', 'name', 'price')
            ->get();

        return response()->json($services);
    }

    /**
     * Get services by category
     */
    public function getServicesByCategory(string $wheelerCategoryId)
    {
        $services = Service::where('status', 'active')
            ->where('wheeler_category_id', $wheelerCategoryId)
            ->select('id', 'name', 'price')
            ->get();

        return response()->json($services);
    }

    /**
     * Store a new service request from customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string',
            'vehicle_name' => 'required|string|max:255',
            'vehicle_registration' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'address' => 'required|string',
            'request_type' => 'required|in:drop-off,pickup',
            'payment_type' => 'required|in:downpayment,full',
            'downpayment_percentage' => 'required_if:payment_type,downpayment|nullable|numeric|in:20,25,30,50',
            'proof_of_payment' => 'required|image|mimes:jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals from selected services
            $selectedServices = Service::whereIn('id', $validated['services'])->get();
            $totalAmount = $selectedServices->sum('price');

            // Determine payment type and calculate amounts
            $paymentType = $validated['payment_type'];

            if ($paymentType === 'full') {
                $downpaymentPercentage = 100;
                $downpaymentAmount = $totalAmount;
                $remainingBalance = 0;
            } else {
                $downpaymentPercentage = $validated['downpayment_percentage'];
                $downpaymentAmount = $totalAmount * ($downpaymentPercentage / 100);
                $remainingBalance = $totalAmount - $downpaymentAmount;
            }

            // Handle file upload
            $proofPath = null;
            if ($request->hasFile('proof_of_payment')) {
                $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');
            }

            // Create service request
            $serviceRequest = ServiceRequest::create([
                'customer_id' => Auth::id(),
                'status' => 'pending',
                'vehicle_name' => $validated['vehicle_name'],
                'vehicle_model' => $validated['vehicle_model'],
                'vehicle_registration' => $validated['vehicle_registration'],
                'vehicle_type' => $validated['vehicle_type'],
                'address' => $validated['address'],
                'request_type' => $validated['request_type'],
                'notes' => null,
                'requested_date' => now()->toDateString(),
                'total_amount' => $totalAmount,
                'downpayment_amount' => $downpaymentAmount,
                'remaining_balance' => $remainingBalance,
                'downpayment_percentage' => $downpaymentPercentage,
                'payment_type' => $paymentType,
                'proof_of_payment' => $proofPath,
                'payment_status' => 'downpayment_pending',
            ]);

            // Attach selected services
            $serviceRequest->services()->attach($validated['services']);

            // Create Payment record
            Payment::create([
                'service_request_id' => $serviceRequest->id,
                'amount' => $downpaymentAmount,
                'payment_type' => $paymentType === 'full' ? 'full' : 'downpayment',
                'status' => 'pending',
                'proof_image' => $proofPath,
            ]);

            DB::commit();

            Log::info('Service request created', [
                'service_request_id' => $serviceRequest->id,
                'customer_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Service request submitted successfully!',
                'service_request_id' => $serviceRequest->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Service request creation failed', [
                'error' => $e->getMessage(),
                'customer_id' => Auth::id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create service request: ' . $e->getMessage(),
            ], 422);
        }
    }
}
