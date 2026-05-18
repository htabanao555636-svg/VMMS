<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        // TAB 1: Has a downpayment OR full payment record, not yet completed — PAGINATED
        $downpayments = ServiceRequest::with(['user', 'services', 'payments'])
            ->whereHas('payments', function ($q) {
                $q->whereIn('payment_type', ['downpayment', 'full']);
            })
            ->whereNotIn('status', ['completed'])
            ->latest()
            ->paginate(10, ['*'], 'dp_page')
            ->appends(request()->query())
            ->through(function ($sr) {
                $totalVerified              = $sr->payments->where('status', 'verified')->sum('amount');
                $sr->remaining_balance      = max(0, $sr->total_amount - $totalVerified);
                $sr->latest_pending_payment = $sr->payments
                    ->where('status', 'pending')
                    ->sortByDesc('created_at')
                    ->first();
                return $sr;
            });

        // TAB 2: Completed with outstanding balance — COLLECTION (filter after map)
        $balancePayments = ServiceRequest::with(['user', 'services', 'payments'])
            ->where('status', 'completed')
            ->whereHas('payments', function ($q) {
                $q->where('status', 'verified');
            })
            ->latest()
            ->get()
            ->map(function ($sr) {
                $totalVerified              = $sr->payments->where('status', 'verified')->sum('amount');
                $totalPending               = $sr->payments->where('status', 'pending')->sum('amount');
                $sr->amount_paid            = $totalVerified;
                $sr->amount_pending         = $totalPending;
                $sr->remaining_balance      = max(0, $sr->total_amount - $totalVerified);
                $sr->latest_pending_payment = $sr->payments
                    ->where('status', 'pending')
                    ->sortByDesc('created_at')
                    ->first();
                return $sr;
            })
            ->filter(fn($sr) => $sr->remaining_balance > 0)
            ->values();

        // TAB 3: Fully paid — MANUALLY PAGINATED via LengthAwarePaginator
        $fullyPaidCollection = ServiceRequest::with(['user', 'services', 'payments'])
            ->whereHas('payments', function ($q) {
                $q->where('status', 'verified');
            })
            ->latest()
            ->get()
            ->map(function ($sr) {
                $sr->verified_amt      = $sr->payments->where('status', 'verified')->sum('amount');
                $sr->remaining_balance = max(0, $sr->total_amount - $sr->verified_amt);
                return $sr;
            })
            ->filter(fn($sr) => $sr->remaining_balance <= 0)
            ->values();

        $fpPage  = request()->get('fp_page', 1);
        $perPage = 10;

        $fullyPaid = new LengthAwarePaginator(
            $fullyPaidCollection->forPage($fpPage, $perPage),
            $fullyPaidCollection->count(),
            $perPage,
            $fpPage,
            [
                'pageName' => 'fp_page',
                'path'     => request()->url(),
                'query'    => request()->query(),
            ]
        );

        $pendingCount   = Payment::where('status', 'pending')->count();
        $verifiedCount  = Payment::where('status', 'verified')->count();
        $rejectedCount  = Payment::where('status', 'rejected')->count();
        $fullyPaidCount = $fullyPaid->total();

        return view('admin.billing', compact(
            'downpayments',
            'balancePayments',
            'fullyPaid',
            'pendingCount',
            'verifiedCount',
            'rejectedCount',
            'fullyPaidCount'
        ));
    }

    public function verifyPayment(Payment $payment)
    {
        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $sr            = $payment->serviceRequest;
        $totalVerified = $sr->payments()->where('status', 'verified')->sum('amount');
        $remaining     = max(0, $sr->total_amount - $totalVerified);
        $sr->update(['remaining_balance' => $remaining]);

        return back()->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment(Payment $payment)
    {
        $payment->update(['status' => 'rejected']);
        return back()->with('success', 'Payment rejected.');
    }

    public function markCompleted(ServiceRequest $serviceRequest)
    {
        $totalVerified = $serviceRequest->payments()
            ->where('status', 'verified')
            ->sum('amount');

        $remaining = max(0, $serviceRequest->total_amount - $totalVerified);

        $serviceRequest->update([
            'status'            => 'completed',
            'remaining_balance' => $remaining,
            'completed_date'    => now(),
        ]);

        return back()->with('success', 'Service request marked as completed.');
    }
}
