@extends('layouts.admin')

@section('content')
@php $prefix = auth()->user()->role === 'staff' ? 'staff' : 'admin'; @endphp

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }

.billing-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
    animation: fadeInUp 0.5s ease-out;
}
.billing-header h2 { font-size: 26px; font-weight: 800; color: #1f2937; margin: 0; letter-spacing: -0.3px; }
.billing-header p { font-size: 13px; color: #6b7280; margin: 6px 0 0; }

.billing-pills { display: flex; gap: 12px; flex-wrap: wrap; }

.pill {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 12px;
    font-weight: 700;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    cursor: default;
    display: flex;
    align-items: center;
    gap: 6px;
}
.pill:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.pill-pending  { color: #991b1b; }
.pill-verified { color: #166534; }
.pill-rejected { color: #92400e; }
.pill-paid     { color: #1e40af; }

.billing-tabs {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
    gap: 0;
    position: relative;
}

.billing-tab-btn {
    padding: 12px 20px;
    border: none;
    background: transparent;
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    position: relative;
}

.billing-tab-btn:hover { color: #1f2937; background: rgba(59,130,246,0.05); }
.billing-tab-btn.active { color: #1f2937; border-bottom-color: #3b82f6; }

.billing-tab-panel { display: none; animation: fadeInUp 0.4s ease-out; }
.billing-tab-panel.active { display: block; }

.table-scroll-wrapper {
    width: 100%;
    overflow-x: auto;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.tab-table { width: 100%; border-collapse: collapse; min-width: 900px; }
.tab-table thead { background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); }
.tab-table th {
    padding: 14px 12px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: .05em;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}
.tab-table td {
    padding: 13px 12px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #111;
    vertical-align: middle;
}
.tab-table tbody tr { transition: all 0.2s ease; }
.tab-table tbody tr:hover { background: #f9fafb; }

.badge { display: inline-block; font-size: 12px; font-weight: 700; white-space: nowrap; }
.badge-pending  { color: #d97706; }
.badge-verified { color: #059669; }
.badge-rejected { color: #dc2626; }
.badge-neutral  { color: #374151; }
.badge-paid     { color: #2563eb; }
.badge-awaiting { color: #9333ea; }

.amt-green { color: #059669; font-weight: 700; }
.amt-red   { color: #dc2626; font-weight: 700; }

.progress-bar { width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; margin-top: 4px; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #059669, #10b981); border-radius: 3px; }

.actions-row { display: flex; flex-direction: row; align-items: center; gap: 6px; flex-wrap: nowrap; }
.actions-row form { margin: 0; flex-shrink: 0; }

.act-btn {
    width: 34px; height: 34px; padding: 0;
    border: none; border-radius: 8px; font-size: 13px;
    cursor: pointer; transition: all 0.25s; text-decoration: none;
    display: inline-flex; align-items: center; justify-content: center;
}
.act-btn:hover { transform: translateY(-2px); }
.act-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

.act-verify  { background: linear-gradient(135deg,#1a5c42,#2d9b6f); color: white; box-shadow: 0 3px 8px rgba(45,155,111,0.35); }
.act-verify:hover { box-shadow: 0 5px 14px rgba(45,155,111,0.45); }
.act-reject  { background: linear-gradient(135deg,#b91c1c,#dc2626); color: white; box-shadow: 0 3px 8px rgba(220,38,38,0.3); }
.act-reject:hover { box-shadow: 0 5px 14px rgba(220,38,38,0.4); }
.act-complete { background: linear-gradient(135deg,#374151,#6b7280); color: white; box-shadow: 0 3px 8px rgba(107,114,128,0.3); }
.act-complete:hover { box-shadow: 0 5px 14px rgba(107,114,128,0.4); }
.act-view { background: linear-gradient(135deg,#1d4ed8,#3b82f6); color: white; box-shadow: 0 3px 8px rgba(59,130,246,0.3); }
.act-view:hover { box-shadow: 0 5px 14px rgba(59,130,246,0.4); }

.empty-state {
    text-align: center; padding: 4rem 2rem; color: #9ca3af;
    font-size: 15px; background: linear-gradient(135deg,#f9fafb,#f3f4f6);
    border-radius: 12px; border: 2px dashed #e5e7eb;
}
.empty-state-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }

.flash-success {
    padding: 14px 18px; margin-bottom: 1.5rem;
    background: linear-gradient(135deg,#dcfce7,#d1fae5);
    color: #166534; border-radius: 8px; font-size: 13px;
    font-weight: 600; border-left: 4px solid #10b981;
    animation: slideIn 0.4s ease-out;
}
.flash-error {
    padding: 14px 18px; margin-bottom: 1.5rem;
    background: linear-gradient(135deg,#fee2e2,#fecaca);
    color: #991b1b; border-radius: 8px; font-size: 13px;
    font-weight: 600; border-left: 4px solid #ef4444;
    animation: slideIn 0.4s ease-out;
}

.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px); }
.modal-overlay.active { display: flex; }
.modal-content { background: white; padding: 24px; border-radius: 12px; max-width: 640px; width: 90%; position: relative; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: fadeInUp 0.4s ease-out; }
.modal-close { position: absolute; top: 14px; right: 14px; border: none; background: #f3f4f6; font-size: 20px; cursor: pointer; color: #6b7280; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
.modal-close:hover { color: #1f2937; background: #e5e7eb; }
.proof-image { max-width: 100%; border-radius: 8px; display: block; }

.confirm-modal { background: white; padding: 32px; border-radius: 12px; max-width: 420px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.2); text-align: center; animation: fadeInUp 0.4s ease-out; }
.confirm-modal h3 { margin: 0 0 12px; font-size: 18px; color: #1f2937; }
.confirm-modal p  { margin: 0 0 24px; color: #6b7280; font-size: 14px; }
.confirm-buttons { display: flex; gap: 12px; justify-content: center; }
.confirm-btn { padding: 10px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
.confirm-btn-primary { background: linear-gradient(135deg,#1a5c42,#2d9b6f); color: white; }
.confirm-btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.confirm-btn-secondary { background: #e5e7eb; color: #374151; }
.confirm-btn-secondary:hover { background: #d1d5db; }

.copy-id { cursor: pointer; padding: 2px 6px; border-radius: 4px; transition: background 0.2s; font-family: 'Monaco','Courier New',monospace; }
.copy-id:hover { background: #f3f4f6; }
.copy-id.copied { animation: pulse 0.5s ease-out; }

.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 12px;
    background: #fff;
}
.pagination-summary { font-size: 13px; color: #6b7280; }
.pagination-summary strong { color: #1a2e1a; font-weight: 700; }
.pagination-nav { display: flex; justify-content: flex-end; }
.pagination-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 4px;
    align-items: center;
}
.page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    border: 1.5px solid #e5e7eb;
    background: #fff;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.18s ease;
    cursor: pointer;
    white-space: nowrap;
}
.page-btn:hover { border-color: #2d9b6f; background: #f0fdf4; color: #1a5c42; }
.page-item.active .page-btn {
    background: linear-gradient(135deg, #1a5c42, #2d9b6f);
    border-color: transparent;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(45,155,111,0.3);
}
.page-item.disabled .page-btn {
    color: #c9cdd4;
    border-color: #f0f0f0;
    background: #fafafa;
    cursor: not-allowed;
    pointer-events: none;
}

@media (max-width: 768px) {
    .billing-header { flex-direction: column; align-items: flex-start; }
    .billing-pills { width: 100%; }
    .billing-tab-btn { padding: 10px 14px; font-size: 12px; }
    .actions-row { flex-wrap: wrap; gap: 4px; }
    .modal-content { max-width: 90%; }
    .pagination-wrapper { flex-direction: column; align-items: center; }
    .pagination-summary { font-size: 12px; }
    .page-btn { min-width: 32px; height: 32px; font-size: 12px; padding: 0 8px; }
}
</style>

<div style="padding: 2rem;">

    {{-- Header --}}
    <div class="billing-header">
        <div>
            <h2>Billing Management</h2>
            <p>Verify payments to unlock service request processing</p>
        </div>
        <div class="billing-pills">
            <span class="pill pill-pending">● Pending {{ $pendingCount }}</span>
            <span class="pill pill-verified">✓ Verified {{ $verifiedCount }}</span>
            <span class="pill pill-rejected">✗ Rejected {{ $rejectedCount }}</span>
            <span class="pill pill-paid">✦ Fully Paid {{ $fullyPaidCount }}</span>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="flash-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash-error">❌ {{ session('error') }}</div>
    @endif

    {{-- Tabs --}}
    <div class="billing-tabs">
        <button class="billing-tab-btn" id="tab-btn-downpayment" onclick="switchTab('downpayment', this)">
            Downpayment
            @if($downpayments->total() > 0)
                <span style="background:#e5e7eb;color:#374151;padding:1px 7px;border-radius:20px;font-size:10px;margin-left:4px;">{{ $downpayments->total() }}</span>
            @endif
        </button>
        <button class="billing-tab-btn" id="tab-btn-balance" onclick="switchTab('balance', this)">
            Balance & Full Payment
            @if($balancePayments->count() > 0)
                <span style="background:#e5e7eb;color:#374151;padding:1px 7px;border-radius:20px;font-size:10px;margin-left:4px;">{{ $balancePayments->count() }}</span>
            @endif
        </button>
        <button class="billing-tab-btn" id="tab-btn-fullypaid" onclick="switchTab('fullypaid', this)">
            Fully Paid
            @if($fullyPaid->total() > 0)
                <span style="background:#e5e7eb;color:#374151;padding:1px 7px;border-radius:20px;font-size:10px;margin-left:4px;">{{ $fullyPaid->total() }}</span>
            @endif
        </button>
    </div>

    {{-- TAB 1: DOWNPAYMENT --}}
    <div id="tab-downpayment" class="billing-tab-panel active">
        @if($downpayments->count() > 0)
            <div class="table-scroll-wrapper">
                <table class="tab-table">
                    <thead>
                        <tr>
                            <th>Request</th>
                            <th>Customer</th>
                            <th>Services</th>
                            <th>Total</th>
                            <th>Downpayment</th>
                            <th>Balance</th>
                            <th>Proof</th>
                            <th>Payment Status</th>
                            <th>Service Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($downpayments as $sr)
                            @php
                                $verifiedAmt = $sr->payments->where('status','verified')->sum('amount');
                                $balance     = $sr->total_amount - $verifiedAmt;
                                $pct         = $sr->total_amount > 0 ? round(($verifiedAmt / $sr->total_amount) * 100) : 0;
                                $payment     = $sr->payments->first(fn($p) => in_array($p->payment_type, ['downpayment','full']));
                            @endphp
                            <tr>
                                <td>
                                    <div style="font-weight:700;" class="copy-id" onclick="copyToClipboard('#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}', this)">#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}</div>
                                    <small style="color:#9ca3af;">{{ $sr->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div style="font-weight:600;">{{ $sr->user->name ?? '—' }}</div>
                                    <small style="color:#9ca3af;">{{ $sr->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $sr->services->pluck('name')->join(', ') ?: '—' }}</td>
                                <td><strong>₱{{ number_format($sr->total_amount,2) }}</strong></td>
                                <td>
                                    <div class="amt-green">₱{{ number_format($verifiedAmt,2) }}</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <small style="color:#9ca3af;">{{ $pct }}%</small>
                                </td>
                                <td>
                                    @if($balance > 0)
                                        <span class="amt-red">₱{{ number_format($balance,2) }}</span>
                                    @else
                                        <span class="amt-green">✓ Cleared</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment?->proof_image)
                                        <button class="act-btn act-view" onclick="viewProof('{{ asset('storage/'.$payment->proof_image) }}')" title="View Proof">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <span style="color:#9ca3af;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment?->status === 'verified')
                                        <span class="badge badge-verified">Verified</span>
                                    @elseif($payment?->status === 'rejected')
                                        <span class="badge badge-rejected">Rejected</span>
                                    @else
                                        <span class="badge badge-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-neutral">{{ ucfirst($sr->status) }}</span>
                                </td>
                                <td>
                                    <div class="actions-row">
                                        @if($payment && $payment->status === 'pending')
                                            <form action="{{ route($prefix . '.billing.verify', $payment) }}" method="POST"
                                                onsubmit="event.preventDefault(); showConfirm('Verify Payment', 'Are you sure you want to verify this payment?', this)">
                                                @csrf
                                                <button type="submit" class="act-btn act-verify" title="Verify Payment">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route($prefix . '.billing.reject', $payment) }}" method="POST"
                                                onsubmit="event.preventDefault(); showConfirm('Reject Payment', 'Are you sure you want to reject this payment?', this)">
                                                @csrf
                                                <button type="submit" class="act-btn act-reject" title="Reject Payment">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route($prefix . '.billing.mark-completed', $sr) }}" method="POST"
                                            onsubmit="event.preventDefault(); showConfirm('Mark as Completed', 'Mark this service request as completed?', this)">
                                            @csrf
                                            <button type="submit" class="act-btn act-complete" title="Mark as Completed">
                                                <i class="fas fa-flag-checkered"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TAB 1 PAGINATION --}}
                <div class="pagination-wrapper">
                    <div class="pagination-summary">
                        Showing <strong>{{ $downpayments->firstItem() }}</strong>–<strong>{{ $downpayments->lastItem() }}</strong>
                        of <strong>{{ $downpayments->total() }}</strong> results
                    </div>
                    @if($downpayments->hasPages())
                    <nav class="pagination-nav">
                        <ul class="pagination-list">
                            @if($downpayments->onFirstPage())
                                <li class="page-item disabled"><span class="page-btn">&#8592; Prev</span></li>
                            @else
                                <li class="page-item"><a class="page-btn" href="{{ $downpayments->previousPageUrl() }}&tab=downpayment">&#8592; Prev</a></li>
                            @endif
                            @foreach(range(1, $downpayments->lastPage()) as $page)
                                @if($page == $downpayments->currentPage())
                                    <li class="page-item active"><span class="page-btn">{{ $page }}</span></li>
                                @elseif(abs($page - $downpayments->currentPage()) <= 2 || $page == 1 || $page == $downpayments->lastPage())
                                    <li class="page-item"><a class="page-btn" href="{{ $downpayments->url($page) }}&tab=downpayment">{{ $page }}</a></li>
                                @elseif(abs($page - $downpayments->currentPage()) == 3)
                                    <li class="page-item disabled"><span class="page-btn">…</span></li>
                                @endif
                            @endforeach
                            @if($downpayments->hasMorePages())
                                <li class="page-item"><a class="page-btn" href="{{ $downpayments->nextPageUrl() }}&tab=downpayment">Next &#8594;</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-btn">Next &#8594;</span></li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <p><strong>No downpayment records found</strong></p>
                <p style="font-size:12px;margin:6px 0 0;">All pending downpayments have been processed.</p>
            </div>
        @endif
    </div>

    {{-- TAB 2: BALANCE & FULL PAYMENT (Collection — no pagination) --}}
    <div id="tab-balance" class="billing-tab-panel">
        @if($balancePayments->count() > 0)
            <div class="table-scroll-wrapper">
                <table class="tab-table">
                    <thead>
                        <tr>
                            <th>Request</th>
                            <th>Customer</th>
                            <th>Services</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance Due</th>
                            <th>Proof</th>
                            <th>Payment Status</th>
                            <th>Service Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($balancePayments as $sr)
                        <tr>
                            <td>
                                <div style="font-weight:700;" class="copy-id" onclick="copyToClipboard('#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}', this)">#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}</div>
                                <small style="color:#9ca3af;">{{ $sr->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div style="font-weight:600;">{{ $sr->user->name ?? '—' }}</div>
                                <small style="color:#9ca3af;">{{ $sr->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $sr->services->pluck('name')->join(', ') ?: '—' }}</td>
                            <td><strong>₱{{ number_format($sr->total_amount,2) }}</strong></td>
                            <td class="amt-green">₱{{ number_format($sr->amount_paid,2) }}</td>
                            <td class="amt-red">₱{{ number_format($sr->remaining_balance,2) }}</td>
                            <td>
                                @if($sr->latest_pending_payment?->proof_image)
                                    <button class="act-btn act-view" onclick="viewProof('{{ asset('storage/'.$sr->latest_pending_payment->proof_image) }}')" title="View Proof">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($sr->amount_pending > 0)
                                    <span class="badge badge-pending">Pending</span>
                                @else
                                    <span class="badge badge-awaiting">Awaiting Proof</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-neutral">{{ ucfirst($sr->status) }}</span>
                            </td>
                            <td>
                                <div class="actions-row">
                                    @if($sr->latest_pending_payment)
                                        <form action="{{ route($prefix . '.billing.verify', $sr->latest_pending_payment) }}" method="POST"
                                            onsubmit="event.preventDefault(); showConfirm('Verify Payment', 'Are you sure you want to verify this payment?', this)">
                                            @csrf
                                            <button type="submit" class="act-btn act-verify" title="Verify Payment">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route($prefix . '.billing.reject', $sr->latest_pending_payment) }}" method="POST"
                                            onsubmit="event.preventDefault(); showConfirm('Reject Payment', 'Are you sure you want to reject this payment?', this)">
                                            @csrf
                                            <button type="submit" class="act-btn act-reject" title="Reject Payment">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div class="pagination-summary">
                        Showing <strong>{{ $balancePayments->count() }}</strong> result(s)
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">💳</div>
                <p><strong>No balance payments pending</strong></p>
                <p style="font-size:12px;margin:6px 0 0;">All customers have completed their payments.</p>
            </div>
        @endif
    </div>

    {{-- TAB 3: FULLY PAID — PAGINATED --}}
    <div id="tab-fullypaid" class="billing-tab-panel">
        @if($fullyPaid->count() > 0)
            <div class="table-scroll-wrapper">
                <table class="tab-table">
                    <thead>
                        <tr>
                            <th>Request</th>
                            <th>Customer</th>
                            <th>Services</th>
                            <th>Total</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fullyPaid as $sr)
                        <tr>
                            <td>
                                <div style="font-weight:700;" class="copy-id" onclick="copyToClipboard('#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}', this)">#{{ str_pad($sr->id,4,'0',STR_PAD_LEFT) }}</div>
                                <small style="color:#9ca3af;">{{ $sr->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div style="font-weight:600;">{{ $sr->user->name ?? '—' }}</div>
                                <small style="color:#9ca3af;">{{ $sr->user->email ?? '' }}</small>
                            </td>
                            <td>{{ $sr->services->pluck('name')->join(', ') ?: '—' }}</td>
                            <td><strong>₱{{ number_format($sr->total_amount,2) }}</strong></td>
                            <td><span class="amt-green">₱{{ number_format($sr->verified_amt,2) }}</span></td>
                            <td><span class="badge badge-paid">Fully Paid</span></td>
                            <td>
                                <a href="{{ route($prefix . '.service-request.show', $sr) }}" class="act-btn act-view" title="View Request">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TAB 3 PAGINATION --}}
                <div class="pagination-wrapper">
                    <div class="pagination-summary">
                        Showing <strong>{{ $fullyPaid->firstItem() }}</strong>–<strong>{{ $fullyPaid->lastItem() }}</strong>
                        of <strong>{{ $fullyPaid->total() }}</strong> results
                    </div>
                    @if($fullyPaid->hasPages())
                    <nav class="pagination-nav">
                        <ul class="pagination-list">
                            @if($fullyPaid->onFirstPage())
                                <li class="page-item disabled"><span class="page-btn">&#8592; Prev</span></li>
                            @else
                                <li class="page-item"><a class="page-btn" href="{{ $fullyPaid->previousPageUrl() }}&tab=fullypaid">&#8592; Prev</a></li>
                            @endif
                            @foreach(range(1, $fullyPaid->lastPage()) as $page)
                                @if($page == $fullyPaid->currentPage())
                                    <li class="page-item active"><span class="page-btn">{{ $page }}</span></li>
                                @elseif(abs($page - $fullyPaid->currentPage()) <= 2 || $page == 1 || $page == $fullyPaid->lastPage())
                                    <li class="page-item"><a class="page-btn" href="{{ $fullyPaid->url($page) }}&tab=fullypaid">{{ $page }}</a></li>
                                @elseif(abs($page - $fullyPaid->currentPage()) == 3)
                                    <li class="page-item disabled"><span class="page-btn">…</span></li>
                                @endif
                            @endforeach
                            @if($fullyPaid->hasMorePages())
                                <li class="page-item"><a class="page-btn" href="{{ $fullyPaid->nextPageUrl() }}&tab=fullypaid">Next &#8594;</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-btn">Next &#8594;</span></li>
                            @endif
                        </ul>
                    </nav>
                    @endif
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🎉</div>
                <p><strong>No fully paid records</strong></p>
                <p style="font-size:12px;margin:6px 0 0;">No service requests have been fully paid yet.</p>
            </div>
        @endif
    </div>

</div>

{{-- Proof Image Modal --}}
<div id="proofModal" class="modal-overlay">
    <div class="modal-content">
        <button onclick="closeProof()" class="modal-close">×</button>
        <img id="proofImg" src="" alt="Payment Proof" class="proof-image">
    </div>
</div>

{{-- Confirm Modal --}}
<div id="confirmModal" class="modal-overlay">
    <div class="confirm-modal">
        <h3 id="confirmTitle">Confirm Action</h3>
        <p id="confirmMessage">Are you sure?</p>
        <div class="confirm-buttons">
            <button class="confirm-btn confirm-btn-primary" onclick="confirmAction()">Confirm</button>
            <button class="confirm-btn confirm-btn-secondary" onclick="cancelAction()">Cancel</button>
        </div>
    </div>
</div>

<script>
let pendingForm = null;

function switchTab(tab, btn) {
    document.querySelectorAll('.billing-tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.billing-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.history.replaceState(null, '', url.toString());
}

(function () {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab') || 'downpayment';
    const btn = document.getElementById('tab-btn-' + tab);
    if (btn) switchTab(tab, btn);
})();

function viewProof(src) {
    document.getElementById('proofImg').src = src;
    document.getElementById('proofModal').classList.add('active');
}

function closeProof() {
    document.getElementById('proofModal').classList.remove('active');
    document.getElementById('proofImg').src = '';
}

document.getElementById('proofModal').addEventListener('click', function(e) {
    if (e.target === this) closeProof();
});

function showConfirm(title, message, form) {
    pendingForm = form;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.add('active');
}

function confirmAction() {
    document.getElementById('confirmModal').classList.remove('active');
    if (pendingForm) pendingForm.submit();
}

function cancelAction() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingForm = null;
}

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) cancelAction();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeProof(); cancelAction(); }
});

function copyToClipboard(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const original = el.textContent;
        el.textContent = '✓ Copied!';
        el.classList.add('copied');
        setTimeout(() => {
            el.textContent = original;
            el.classList.remove('copied');
        }, 1500);
    }).catch(err => console.error('Failed to copy:', err));
}
</script>

@endsection