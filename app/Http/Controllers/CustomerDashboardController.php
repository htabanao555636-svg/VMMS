<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Payment History
     * GET /my-payments
     */
    public function paymentHistory()
    {
        $serviceRequests = ServiceRequest::where('customer_id', auth()->id())
            ->with('services')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.payment-history', compact('serviceRequests'));
    }

    /**
     * My Services
     * GET /my-services
     */
    public function myServices()
    {
        $serviceRequests = ServiceRequest::where('customer_id', auth()->id())
            ->with('services', 'mechanic')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.my-services', compact('serviceRequests'));
    }

    /**
     * My Payables
     * GET /my-payables
     */
    public function myPayables()
    {
        $serviceRequests = ServiceRequest::where('customer_id', auth()->id())
            ->where('payment_status', 'downpayment_verified')
            ->where('remaining_balance', '>', 0)
            ->with('services')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate total outstanding balance
        $totalPayables = ServiceRequest::where('customer_id', auth()->id())
            ->where('payment_status', 'downpayment_verified')
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');

        return view('customer.my-payables', compact('serviceRequests', 'totalPayables'));
    }

    /**
     * Submit Full Payment
     * POST /my-payables/{serviceRequest}/pay
     */
    public function submitFullPayment(Request $request, ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Validate
        $validated = $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png|max:10240',
        ]);

        // Store file
        $filePath = $request->file('proof_of_payment')->store('proofs', 'public');

        // Update service request
        $serviceRequest->update([
            'proof_of_payment' => $filePath,
            'payment_status' => 'downpayment_pending',
            'staff_notes' => null,
        ]);

        return back()->with('success', 'Full payment proof submitted. Awaiting verification.');
    }

    /**
     * Resubmit Proof
     * POST /my-payments/{serviceRequest}/resubmit
     */
    public function resubmitProof(Request $request, ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Validate
        $validated = $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png|max:10240',
        ]);

        // Store file
        $filePath = $request->file('proof_of_payment')->store('proofs', 'public');

        // Update service request
        $serviceRequest->update([
            'proof_of_payment' => $filePath,
            'payment_status' => 'downpayment_pending',
            'staff_notes' => null,
        ]);

        return back()->with('success', 'Proof resubmitted successfully.');
    }
}
