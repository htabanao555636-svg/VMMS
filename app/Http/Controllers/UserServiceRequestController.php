<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class UserServiceRequestController extends Controller
{
    /**
     * Get all services as JSON (for dynamic form)
     */
    public function getServices()
    {
        $services = Service::where('status', 'active')
            ->select('id', 'name', 'price')
            ->get();

        return response()->json($services);
    }

    public function getServicesByCategory(string $wheelerCategoryId)
{
    $services = Service::where('status', 'active')
        ->where('wheeler_category_id', $wheelerCategoryId)
        ->select('id', 'name', 'price')
        ->get();

    return response()->json($services);
}

    /**
     * Store a new service request from user
     */
    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'vehicle_type' => 'required|string',
            'vehicle_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'vehicle_registration' => 'required|string|max:255',
            'owner_contact' => 'required|string|max:20',
            'vehicle_model' => 'required|string|max:255',
            'owner_email' => 'required|email',
            'services' => 'required|array|min:1',
            'services.*' => 'exists:services,id',
            'address' => 'required|string',
            'request_type' => 'required|in:drop-off,pickup',
            'payment_type' => 'required|in:downpayment,full',
            'downpayment_percentage' => 'required_if:payment_type,downpayment|nullable|numeric|in:20,25,30,50',
            'proof_of_payment' => 'required|image|mimes:jpeg,png|max:2048',
        ]);

        // Find or create customer
        $customer = \App\Models\User::firstOrCreate(
            ['email' => $validated['owner_email']],
            [
                'name' => $validated['owner_name'],
                'password' => bcrypt('default123'), // Default password for new users
            ]
        );

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
            'customer_id' => $customer->id,
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

        // Attach selected services (many-to-many)
        $serviceRequest->services()->attach($validated['services']);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Service request submitted successfully!',
            'service_request_id' => $serviceRequest->id,
        ]);
    }
}
