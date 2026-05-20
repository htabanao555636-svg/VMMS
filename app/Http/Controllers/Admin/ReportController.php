<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\WheelerCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom  = $request->query('date_from');
        $dateTo    = $request->query('date_to');
        $status    = $request->query('status', 'all');
        $activeTab = $request->query('tab', 'service_requests');

        $fromDate = $dateFrom ? Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay() : null;
        $toDate   = $dateTo   ? Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay()     : null;

        // Valid payment_status values in DB
        $validStatuses = [
            'downpayment_pending',
            'downpayment_verified',
            'fully_paid',
            'unpaid',
        ];

        // ── Wheeler Categories (for vehicle_type lookup in blade) ────────
        $categories = WheelerCategory::all();

        // ── Service Requests ────────────────────────────────────────────
        // No 'vehicle' relationship — vehicle columns are direct on the model
        $srQuery = ServiceRequest::with(['customer', 'mechanic', 'services', 'payments'])
            ->latest('created_at');

        $this->applyDateFilter($srQuery, $fromDate, $toDate);

        if ($status !== 'all' && in_array($status, $validStatuses)) {
            $srQuery->where('payment_status', $status);
        }

        $serviceRequests = $srQuery
            ->paginate(5, ['*'], 'sr_page')
            ->withQueryString();

        // ── Payments ────────────────────────────────────────────────────
        $payQuery = Payment::with('serviceRequest')->latest('created_at');

        $this->applyDateFilter($payQuery, $fromDate, $toDate);

        $payments = $payQuery
            ->paginate(5, ['*'], 'pay_page')
            ->withQueryString();

        // ── Revenue Summary (all records, no pagination) ─────────────────
        // Uses ->payments (eager-loaded property) not ->payments() (new query)
        $allSr = ServiceRequest::with('payments')
            ->when($fromDate, fn($q) => $q->where('created_at', '>=', $fromDate))
            ->when($toDate,   fn($q) => $q->where('created_at', '<=', $toDate))
            ->when($status !== 'all' && in_array($status, $validStatuses),
                   fn($q) => $q->where('payment_status', $status))
            ->get();

        $revenueSummary = $this->calculateRevenueSummary($allSr);

        $filters = [
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'status'    => $status,
        ];

        $viewPath = 'admin.report';

        return view($viewPath, compact(
            'serviceRequests',
            'payments',
            'revenueSummary',
            'filters',
            'activeTab',
            'categories'        // <-- added: needed for vehicle_type triple-layer lookup in blade
        ));
    }

    private function applyDateFilter($query, $fromDate, $toDate): void
    {
        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $query->where('created_at', '<=', $toDate);
        }
    }

    private function calculateRevenueSummary($serviceRequests): array
    {
        $totalBilled    = 0;
        $totalCollected = 0;
        $totalPending   = 0;

        foreach ($serviceRequests as $sr) {
            $billed = (float) ($sr->total_amount ?? 0);
            $totalBilled += $billed;

            // Use eager-loaded ->payments property, NOT ->payments() method
            $collected = $sr->payments
                ->where('status', 'verified')
                ->sum('amount');
            $totalCollected += $collected;

            $pending = $billed - $collected;
            if ($pending > 0) {
                $totalPending += $pending;
            }
        }

        $rate = $totalBilled > 0
            ? round(($totalCollected / $totalBilled) * 100, 2)
            : 0;

        return [
            'billed'    => $totalBilled,
            'collected' => $totalCollected,
            'pending'   => $totalPending,
            'rate'      => $rate,
        ];
    }
}
