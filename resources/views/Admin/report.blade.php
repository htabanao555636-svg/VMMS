@extends('layouts.admin')

@section('content')

<style>
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes scaleIn  { from { opacity: 0; transform: scale(0.95); }     to { opacity: 1; transform: scale(1); } }

/* ===== HEADER ===== */
.content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 2px solid #e5e7eb;
    animation: fadeInUp 0.4s ease-out;
}

.page-title {
    font-size: 26px;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
    letter-spacing: -0.3px;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    animation: fadeInUp 0.45s ease-out;
}

.filter-row {
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-label {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.filter-input,
.filter-select {
    height: 40px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: #1f2937;
    background: #fafafa;
    transition: all 0.2s ease;
    padding: 0 12px;
}

.filter-input  { min-width: 160px; }
.filter-select {
    min-width: 190px;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #059669;
    background: white;
    box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: flex-end;
}

.btn-filter {
    height: 40px;
    padding: 0 18px;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    font-family: inherit;
    white-space: nowrap;
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}

.btn-clear-filter {
    height: 40px;
    padding: 0 14px;
    background: white;
    color: #6b7280;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    font-family: inherit;
    white-space: nowrap;
}

.btn-clear-filter:hover {
    border-color: #dc2626;
    color: #dc2626;
    background: #fef2f2;
    text-decoration: none;
}

.active-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* ===== CONTENT BOX ===== */
.content-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.07);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    animation: scaleIn 0.4s ease-out;
}

/* ===== TAB BAR ===== */
.report-tab-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    border-bottom: 2px solid #e5e7eb;
    gap: 1rem;
}

.report-tabs {
    display: flex;
    gap: 0;
    flex: 1;
}

.report-tab-btn {
    padding: 14px 20px;
    border: none;
    background: transparent;
    font-size: 13.5px;
    font-weight: 600;
    color: #6b7280;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: inherit;
}

.report-tab-btn:hover {
    color: #1f2937;
    background: #f9fafb;
    text-decoration: none;
}

.report-tab-btn.active {
    color: #059669;
    border-bottom-color: #059669;
    font-weight: 700;
}

.btn-print-pdf {
    height: 36px;
    padding: 0 14px;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    border-radius: 7px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
    font-family: inherit;
    flex-shrink: 0;
}

.btn-print-pdf:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59,130,246,0.3);
}

/* ===== TAB PANELS ===== */
.report-tab-panel {
    display: none;
    padding: 24px;
    animation: fadeInUp 0.3s ease-out;
}

.report-tab-panel.active { display: block; }

/* ===== SUMMARY CARDS ===== */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    background: #f8fafc;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.summary-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.summary-label {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.summary-value {
    font-size: 22px;
    font-weight: 800;
    color: #1f2937;
}

/* ===== TABLE ===== */
.table-scroll-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.report-table thead {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
}

.report-table th {
    padding: 13px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    white-space: nowrap;
}

.report-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #1f2937;
    vertical-align: middle;
}

.report-table tbody tr:last-child td { border-bottom: none; }
.report-table tbody tr:hover td { background: #f9fafb; }

/* ===== BADGES ===== */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

.badge-downpayment_pending  { background: transparent; color: #92400e; border: none; padding-left: 0; }
.badge-downpayment_verified { background: transparent; color: #075985; border: none; padding-left: 0; }
.badge-fully_paid           { background: transparent; color: #065f46; border: none; padding-left: 0; }
.badge-unpaid               { background: transparent; color: #991b1b; border: none; padding-left: 0; }

.badge-verified { background: transparent; color: #065f46; border: none; padding-left: 0; }
.badge-pending  { background: transparent; color: #92400e; border: none; padding-left: 0; }
.badge-rejected { background: transparent; color: #991b1b; border: none; padding-left: 0; }

/* ===== AMOUNTS ===== */
.amt-currency { font-weight: 700; color: #1f2937; }
.amt-zero     { color: #9ca3af; font-weight: 600; }

.amt-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 600;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
}

.amt-link:hover { color: #2563eb; text-decoration: underline; }

.text-muted { color: #9ca3af; font-size: 12px; }

/* payment type pill */
.ptype-full       { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.ptype-downpayment{ background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.ptype-remaining  { background: #ede9fe; color: #4c1d95; border: 1px solid #ddd6fe; }
.ptype-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}

.empty-state-icon { font-size: 44px; margin-bottom: 10px; opacity: 0.35; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .content-header    { flex-direction: column; align-items: flex-start; gap: 12px; }
    .filter-row        { flex-direction: column; gap: 10px; }
    .filter-item       { width: 100%; }
    .filter-input,
    .filter-select     { min-width: unset; width: 100%; }
    .filter-actions    { width: 100%; }
    .btn-filter,
    .btn-clear-filter  { flex: 1; justify-content: center; }
    .report-tab-header { flex-direction: column; align-items: flex-start; padding: 12px 16px; }
    .report-tabs       { width: 100%; overflow-x: auto; }
    .report-tab-panel  { padding: 16px; }
    .summary-cards     { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 480px) {
    .summary-cards { grid-template-columns: 1fr; }
}
</style>

@php
    $reportRoute = Auth::user()->role === 'admin' ? 'admin.report' : 'staff.report';
    $hasFilters  = ($filters['date_from'] ?? null)
                || ($filters['date_to']   ?? null)
                || (($filters['status']   ?? 'all') !== 'all');

    /**
     * Derive the accurate payment status from actual verified payments.
     * This is a helper closure used in both Service Requests and Revenue Summary tabs.
     * It overrides the raw DB payment_status which can be stale.
     */
    $derivePaymentStatus = function($sr) {
        $total    = (float)($sr->total_amount ?? 0);
        $verified = $sr->payments->where('status', 'verified')->sum('amount');
        $verified = (float)$verified;

        if ($total <= 0)             return 'unpaid';
        if ($verified <= 0)          return 'unpaid';
        if ($verified >= $total)     return 'fully_paid';

        // Has a verified payment but not fully paid yet — check if first payment was a downpayment
        $hasVerifiedDownpayment = $sr->payments
            ->where('status', 'verified')
            ->whereIn('payment_type', ['downpayment'])
            ->count() > 0;

        return $hasVerifiedDownpayment ? 'downpayment_verified' : 'downpayment_pending';
    };
@endphp

<section id="report" class="content-section active">

    {{-- HEADER --}}
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-chart-bar" style="color:#059669;margin-right:8px;font-size:22px;"></i>
            Reports
        </h1>
    </div>

    {{-- FILTER SECTION --}}
    <div class="filter-section">
        <form method="GET" action="{{ route($reportRoute) }}">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            <div class="filter-row">
                <div class="filter-item">
                    <label class="filter-label">From Date</label>
                    <input type="date" name="date_from" class="filter-input"
                           value="{{ $filters['date_from'] ?? '' }}">
                </div>

                <div class="filter-item">
                    <label class="filter-label">To Date</label>
                    <input type="date" name="date_to" class="filter-input"
                           value="{{ $filters['date_to'] ?? '' }}">
                </div>

                <div class="filter-item">
                    <label class="filter-label">Payment Status</label>
                    <select name="status" class="filter-select">
                        <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>
                            All Statuses
                        </option>
                        <option value="downpayment_pending"
                            {{ ($filters['status'] ?? '') === 'downpayment_pending' ? 'selected' : '' }}>
                            Downpayment Pending
                        </option>
                        <option value="downpayment_verified"
                            {{ ($filters['status'] ?? '') === 'downpayment_verified' ? 'selected' : '' }}>
                            Downpayment Verified
                        </option>
                        <option value="fully_paid"
                            {{ ($filters['status'] ?? '') === 'fully_paid' ? 'selected' : '' }}>
                            Fully Paid
                        </option>
                        <option value="unpaid"
                            {{ ($filters['status'] ?? '') === 'unpaid' ? 'selected' : '' }}>
                            Unpaid
                        </option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    @if($hasFilters)
                        <a href="{{ route($reportRoute, ['tab' => $activeTab]) }}"
                           class="btn-clear-filter">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @if($hasFilters)
            <div class="active-filters">
                @if($filters['date_from'] ?? null)
                    <span class="filter-tag">
                        <i class="fas fa-calendar"></i> From {{ $filters['date_from'] }}
                    </span>
                @endif
                @if($filters['date_to'] ?? null)
                    <span class="filter-tag">
                        <i class="fas fa-calendar"></i> To {{ $filters['date_to'] }}
                    </span>
                @endif
                @if(($filters['status'] ?? 'all') !== 'all')
                    <span class="filter-tag">
                        <i class="fas fa-circle"></i>
                        {{ ucwords(str_replace('_', ' ', $filters['status'])) }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- CONTENT BOX --}}
    <div class="content-box">

        {{-- TAB BAR --}}
        <div class="report-tab-header">
            <div class="report-tabs">
                <a href="{{ route($reportRoute, array_merge($filters ?? [], ['tab' => 'service_requests'])) }}"
                   class="report-tab-btn {{ $activeTab === 'service_requests' ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Service Requests
                </a>
                <a href="{{ route($reportRoute, array_merge($filters ?? [], ['tab' => 'payments'])) }}"
                   class="report-tab-btn {{ $activeTab === 'payments' ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
                <a href="{{ route($reportRoute, array_merge($filters ?? [], ['tab' => 'revenue_summary'])) }}"
                   class="report-tab-btn {{ $activeTab === 'revenue_summary' ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Revenue Summary
                </a>
            </div>
            <button type="button" class="btn-print-pdf" onclick="printToPDF()">
                <i class="fas fa-file-pdf"></i> Print to PDF
            </button>
        </div>

        {{-- ── TAB 1: SERVICE REQUESTS ── --}}
        <div class="report-tab-panel {{ $activeTab === 'service_requests' ? 'active' : '' }}">
            @if($serviceRequests->count() > 0)
                <div class="table-scroll-wrapper">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Request #</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Plate No.</th>
                                <th>Services</th>
                                <th>Total (₱)</th>
                                <th>Paid (₱)</th>
                                <th>Balance (₱)</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $sr)
                                @php
                                    $paid           = (float)$sr->payments->where('status', 'verified')->sum('amount');
                                    $balance        = max(0, (float)($sr->total_amount ?? 0) - $paid);
                                    $derivedStatus  = $derivePaymentStatus($sr);
                                    $psClass        = str_replace(' ', '_', $derivedStatus);

                                    // Triple-layer vehicle type lookup (numeric ID → category name)
                                    $vehicleLabel = isset($categories)
                                        ? ($categories->firstWhere('name', $sr->vehicle_type)?->name
                                            ?? $categories->firstWhere('id', $sr->vehicle_type)?->name
                                            ?? ($sr->vehicle_type ?? 'N/A'))
                                        : ($sr->vehicle_type ?? 'N/A');
                                @endphp
                                <tr>
                                    <td>
                                        <span style="font-family:'Courier New',monospace;font-weight:700;
                                                     color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;
                                                     padding:3px 7px;border-radius:5px;font-size:12px;">
                                            #SR-{{ str_pad($sr->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-weight:600;">{{ $sr->customer->name ?? '—' }}</span>
                                        @if($sr->customer?->email)
                                            <br><span class="text-muted">{{ $sr->customer->email }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $sr->vehicle_name ?? '—' }}
                                        @if($sr->vehicle_model)
                                            <br><span class="text-muted">{{ $sr->vehicle_model }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-family:'Courier New',monospace;font-weight:600;font-size:12px;">
                                            {{ strtoupper($sr->vehicle_registration ?? '—') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($sr->services->count() > 0)
                                            <span style="font-size:12px;color:#4b5563;line-height:1.7;">
                                                {{ $sr->services->pluck('name')->join(', ') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="amt-currency">
                                        ₱{{ number_format($sr->total_amount ?? 0, 2) }}
                                    </td>
                                    <td class="amt-currency" style="color:#059669;">
                                        ₱{{ number_format($paid, 2) }}
                                    </td>
                                    <td class="amt-currency" style="{{ $balance > 0 ? 'color:#dc2626;' : 'color:#9ca3af;' }}">
                                        ₱{{ number_format($balance, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $psClass }}">
                                            {{ ucwords(str_replace('_', ' ', $derivedStatus)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $serviceRequests->links('components.pagination') }}
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                    <p style="margin:0;font-weight:700;color:#374151;">No service requests found</p>
                    <p style="font-size:12px;margin:6px 0 0;">Try adjusting your filters</p>
                </div>
            @endif
        </div>

        {{-- ── TAB 2: PAYMENTS ── --}}
        {{-- Method and Reference columns removed — both are null in DB (never captured by payment form) --}}
        <div class="report-tab-panel {{ $activeTab === 'payments' ? 'active' : '' }}">
            @if($payments->count() > 0)
                <div class="table-scroll-wrapper">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Payment #</th>
                                <th>Request #</th>
                                <th>Date & Time</th>
                                <th>Amount (₱)</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Proof</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $ptypeMap = [
                                        'full'        => ['label' => 'Full Payment',     'class' => 'ptype-full'],
                                        'downpayment' => ['label' => 'Downpayment',       'class' => 'ptype-downpayment'],
                                        'remaining'   => ['label' => 'Remaining Balance', 'class' => 'ptype-remaining'],
                                    ];
                                    $ptype = $ptypeMap[$payment->payment_type] ?? ['label' => ucfirst($payment->payment_type ?? '—'), 'class' => ''];
                                @endphp
                                <tr>
                                    <td>
                                        <span style="font-family:'Courier New',monospace;font-weight:700;
                                                     color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;
                                                     padding:3px 7px;border-radius:5px;font-size:12px;">
                                            #{{ $payment->id }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-family:'Courier New',monospace;font-weight:600;font-size:12px;color:#374151;">
                                            #SR-{{ str_pad($payment->service_request_id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size:13px;">{{ $payment->created_at->format('M d, Y') }}</span>
                                        <br>
                                        <span class="text-muted">{{ $payment->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="amt-currency" style="color:#059669;">
                                        ₱{{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="ptype-badge {{ $ptype['class'] }}">
                                            {{ $ptype['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->proof_image)
                                            <a href="{{ asset('storage/' . $payment->proof_image) }}"
                                               target="_blank" class="amt-link">
                                                View <i class="fas fa-external-link-alt" style="font-size:10px;"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $payments->links('components.pagination') }}
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                    <p style="margin:0;font-weight:700;color:#374151;">No payments found</p>
                    <p style="font-size:12px;margin:6px 0 0;">Try adjusting your filters</p>
                </div>
            @endif
        </div>

        {{-- ── TAB 3: REVENUE SUMMARY ── --}}
        <div class="report-tab-panel {{ $activeTab === 'revenue_summary' ? 'active' : '' }}">

            {{-- KPI Cards --}}
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-label">
                        <i class="fas fa-file-invoice" style="color:#6b7280;"></i> Total Billed
                    </div>
                    <div class="summary-value">₱{{ number_format($revenueSummary['billed'], 2) }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">
                        <i class="fas fa-check-circle" style="color:#059669;"></i> Total Collected
                    </div>
                    <div class="summary-value" style="color:#059669;">
                        ₱{{ number_format($revenueSummary['collected'], 2) }}
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">
                        <i class="fas fa-hourglass-half" style="color:#dc2626;"></i> Total Pending
                    </div>
                    <div class="summary-value" style="color:#dc2626;">
                        ₱{{ number_format($revenueSummary['pending'], 2) }}
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">
                        <i class="fas fa-percent" style="color:#3b82f6;"></i> Collection Rate
                    </div>
                    <div class="summary-value" style="color:#3b82f6;">
                        {{ $revenueSummary['rate'] }}%
                    </div>
                </div>
            </div>

            @if($serviceRequests->count() > 0)
                <div class="table-scroll-wrapper">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Request #</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Billed (₱)</th>
                                <th>Collected (₱)</th>
                                <th>Balance (₱)</th>
                                <th>Last Payment</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($serviceRequests as $sr)
                                @php
                                    $paid        = (float)$sr->payments->where('status', 'verified')->sum('amount');
                                    $balance     = max(0, (float)($sr->total_amount ?? 0) - $paid);
                                    $lastPayment = $sr->payments->where('status', 'verified')->sortByDesc('created_at')->first();
                                    $derivedStatus = $derivePaymentStatus($sr);
                                    $psClass     = str_replace(' ', '_', $derivedStatus);

                                    // Triple-layer vehicle type lookup
                                    $vehicleLabel = isset($categories)
                                        ? ($categories->firstWhere('name', $sr->vehicle_type)?->name
                                            ?? $categories->firstWhere('id', $sr->vehicle_type)?->name
                                            ?? ($sr->vehicle_type ?? 'N/A'))
                                        : ($sr->vehicle_type ?? 'N/A');
                                @endphp
                                <tr>
                                    <td>
                                        <span style="font-family:'Courier New',monospace;font-weight:700;
                                                     color:#2563eb;background:#eff6ff;border:1px solid #bfdbfe;
                                                     padding:3px 7px;border-radius:5px;font-size:12px;">
                                            #SR-{{ str_pad($sr->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td style="font-weight:600;">{{ $sr->customer->name ?? '—' }}</td>
                                    <td>
                                        {{ $sr->vehicle_name ?? '—' }}
                                        @if($vehicleLabel && $vehicleLabel !== 'N/A')
                                            <br><span class="text-muted">{{ $vehicleLabel }}</span>
                                        @endif
                                    </td>
                                    <td class="amt-currency">₱{{ number_format($sr->total_amount ?? 0, 2) }}</td>
                                    <td class="amt-currency" style="color:#059669;">
                                        ₱{{ number_format($paid, 2) }}
                                    </td>
                                    <td class="amt-currency" style="{{ $balance > 0 ? 'color:#dc2626;' : 'color:#9ca3af;' }}">
                                        ₱{{ number_format($balance, 2) }}
                                    </td>
                                    <td>
                                        @if($lastPayment)
                                            {{ \Carbon\Carbon::parse($lastPayment->created_at)->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $psClass }}">
                                            {{ ucwords(str_replace('_', ' ', $derivedStatus)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $serviceRequests->links('components.pagination') }}
            @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                    <p style="margin:0;font-weight:700;color:#374151;">No data found</p>
                    <p style="font-size:12px;margin:6px 0 0;">Try adjusting your filters</p>
                </div>
            @endif
        </div>

    </div>{{-- .content-box --}}
</section>

<script>
function printToPDF() {
    const activeTabEl  = document.querySelector('.report-tab-btn.active');
    const activeTab    = activeTabEl ? activeTabEl.textContent.trim() : 'Report';
    const params       = new URLSearchParams(window.location.search);
    const dateFrom     = params.get('date_from') || 'All Dates';
    const dateTo       = params.get('date_to')   || '';
    const dateRange    = dateTo ? `${dateFrom} to ${dateTo}` : dateFrom;
    const generated    = new Date().toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });

    const activePanel  = document.querySelector('.report-tab-panel.active');
    const tableHTML    = activePanel.querySelector('.table-scroll-wrapper')?.innerHTML
                      || activePanel.querySelector('.empty-state')?.outerHTML
                      || '';
    const summaryHTML  = activePanel.querySelector('.summary-cards')?.outerHTML || '';

    const printContent = `<!DOCTYPE html><html><head>
        <title>Report – ${activeTab}</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; color: #111; }
            h1   { font-size: 22px; font-weight: 800; margin: 0 0 8px; }
            .meta { font-size: 12px; color: #6b7280; margin: 3px 0; }
            .header { border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 20px; }
            .summary-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
            .summary-card  { border: 1px solid #e5e7eb; padding: 14px; border-radius: 8px; text-align:center; }
            .summary-label { font-size: 11px; color: #6b7280; font-weight: 700; margin-bottom: 6px; }
            .summary-value { font-size: 18px; font-weight: 800; }
            table   { width: 100%; border-collapse: collapse; }
            th      { background: #f3f4f6; padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 700; border: 1px solid #e5e7eb; }
            td      { padding: 10px 12px; border: 1px solid #e5e7eb; font-size: 12px; }
            tr:nth-child(even) td { background: #f9fafb; }
            .badge  { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 10px; font-weight: 700; }
            .badge-downpayment_pending  { background:#fef3c7; color:#92400e; }
            .badge-downpayment_verified { background:#e0f2fe; color:#075985; }
            .badge-fully_paid           { background:#d1fae5; color:#065f46; }
            .badge-unpaid               { background:#fee2e2; color:#991b1b; }
            .badge-verified             { background:#d1fae5; color:#065f46; }
            .badge-pending              { background:#fef3c7; color:#92400e; }
            .badge-rejected             { background:#fee2e2; color:#991b1b; }
            .ptype-badge  { display:inline-block; padding:3px 8px; border-radius:12px; font-size:10px; font-weight:700; }
            .ptype-full        { background:#d1fae5; color:#065f46; }
            .ptype-downpayment { background:#fef3c7; color:#92400e; }
            .ptype-remaining   { background:#ede9fe; color:#4c1d95; }
            @media print { body { margin:0; } }
        </style>
    </head><body>
        <div class="header">
            <h1>VMMS – ${activeTab}</h1>
            <div class="meta">Date Range: ${dateRange}</div>
            <div class="meta">Generated: ${generated}</div>
        </div>
        ${summaryHTML}
        ${tableHTML}
    </body></html>`;

    const win = window.open('', '_blank', 'width=1200,height=800');
    win.document.write(printContent);
    win.document.close();
    setTimeout(() => win.print(), 300);
}
</script>

@endsection