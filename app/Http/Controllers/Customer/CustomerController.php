<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\WheelerCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $services   = Service::where('status', 'active')->with('wheelerCategory')->get();
        $categories = WheelerCategory::withCount('services')->get();
        return view('customer.dashboard', compact('services', 'categories'));
    }

    public function myServices()
    {
        $serviceRequests = ServiceRequest::where('customer_id', auth()->id())
            ->with(['services', 'payments'])
            ->latest()
            ->paginate(5)
            ->through(function ($sr) {
                $totalVerified         = $sr->payments->where('status', 'verified')->sum('amount');
                $totalPending          = $sr->payments->where('status', 'pending')->sum('amount');
                $sr->amount_paid       = $totalVerified;
                $sr->amount_pending    = $totalPending;
                $sr->remaining_balance = max(0, $sr->total_amount - $totalVerified);
                $sr->has_pending_proof = $totalPending > 0;
                return $sr;
            });

        return view('customer.my-services', compact('serviceRequests'));
    }

    public function myPayables()
    {
        // Completed requests where customer still owes money
        $payables = ServiceRequest::where('customer_id', auth()->id())
            ->where('status', 'completed')
            ->with(['services', 'payments'])
            ->latest()
            ->get()
            ->map(function ($sr) {
                $totalVerified         = $sr->payments->where('status', 'verified')->sum('amount');
                $totalPending          = $sr->payments->where('status', 'pending')->sum('amount');
                $sr->amount_paid       = $totalVerified;
                $sr->amount_pending    = $totalPending;
                $sr->remaining_balance = max(0, $sr->total_amount - $totalVerified);
                $sr->has_pending_proof = $totalPending > 0;
                return $sr;
            })
            ->filter(fn($sr) => $sr->total_amount > 0 && $sr->remaining_balance > 0)
            ->values();

        return view('customer.my-payables', compact('payables'));
    }

    public function payments()
    {
        $payments = Payment::whereHas('serviceRequest', function ($q) {
                $q->where('customer_id', auth()->id());
            })
            ->with('serviceRequest')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('customer.payment-history', compact('payments'));
    }

    public function payRemaining(Request $request)
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'proof_image'        => 'required|image|mimes:jpeg,png|max:5120',
        ]);

        $sr = ServiceRequest::with('payments')
            ->where('customer_id', auth()->id())
            ->findOrFail($request->service_request_id);

        $totalVerified    = $sr->payments->where('status', 'verified')->sum('amount');
        $remainingBalance = max(0, $sr->total_amount - $totalVerified);

        if ($remainingBalance <= 0) {
            return back()->with('error', 'This service request is already fully paid.');
        }

        // Block double submission — don't allow if a pending proof already exists
        $hasPending = $sr->payments->where('status', 'pending')->count() > 0;
        if ($hasPending) {
            return back()->with('error', 'You already have a payment proof awaiting verification.');
        }

        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        Payment::create([
            'service_request_id' => $sr->id,
            'user_id'            => auth()->id(),
            'amount'             => $remainingBalance,
            'payment_type'       => 'remaining',
            'status'             => 'pending',
            'proof_image'        => $path,
        ]);

        return back()->with('success', 'Payment proof submitted. Awaiting verification.');
    }
}
