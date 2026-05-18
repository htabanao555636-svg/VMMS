@extends('layouts.user')

@section('content')
<style>
    .cu-nav-link.active {
        background: var(--green-light);
        color: var(--green-dark);
    }

    .cu-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        height: 60px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .cu-topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cu-logo-icon {
        width: 32px;
        height: 32px;
        background: var(--green-dark);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cu-logo-icon svg {
        width: 20px;
        height: 20px;
        fill: white;
    }

    .cu-logo-text {
        font-size: 16px;
        font-weight: 700;
        color: var(--green-dark);
    }

    .cu-logo-text span {
        color: var(--green-light);
    }

    .cu-topbar-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .cu-nav-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        color: #6b7280;
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .cu-nav-link i {
        font-size: 16px;
    }

    .cu-nav-link:hover {
        background: #f3f4f6;
    }

    .cu-user-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 20px;
        background: #f3f4f6;
        cursor: pointer;
        position: relative;
    }

    .cu-user-avatar {
        width: 28px;
        height: 28px;
        background: var(--green-dark);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }

    .cu-user-name {
        font-size: 13px;
        font-weight: 500;
        color: #1f2937;
    }

    .cu-user-chevron {
        font-size: 10px;
        color: #9ca3af;
    }

    .cu-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        min-width: 160px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-top: 6px;
        display: none;
        z-index: 1000;
    }

    .cu-dropdown.active {
        display: block;
    }

    .cu-dropdown-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 0;
    }

    .cu-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: none;
        border: none;
        color: #374151;
        font-size: 13px;
        cursor: pointer;
        width: 100%;
        text-align: left;
        transition: all 0.2s;
    }

    .cu-dropdown-item:hover {
        background: #f3f4f6;
    }

    .cu-dropdown-item.logout {
        color: #dc2626;
    }

    .cu-hero {
        background: white;
        padding: 40px 20px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .cu-hero-badge {
        display: inline-block;
        background: var(--green-light);
        color: var(--green-dark);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .cu-hero-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .cu-hero-title span {
        color: var(--green-dark);
    }

    .cu-hero-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 24px;
    }

    .cu-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        max-width: 600px;
        margin: 0 auto;
    }

    .cu-stat-box {
        background: #f9fafb;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .cu-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--green-dark);
    }

    .cu-stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .cu-content {
        background: white;
        margin: 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .cu-table-wrapper {
        overflow-x: auto;
    }

    .cu-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cu-table thead {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .cu-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cu-table td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #1f2937;
    }

    .cu-table tbody tr:hover {
        background: #fafafa;
    }

    .cu-ref-id {
        font-weight: 600;
        color: var(--green-dark);
    }

    .cu-vehicle-plate {
        font-weight: 600;
        color: #1f2937;
    }

    .cu-vehicle-details {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .cu-services-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .cu-service-item {
        font-size: 13px;
        color: #1f2937;
    }

    .cu-service-extra {
        font-size: 12px;
        color: #9ca3af;
    }

    .cu-amount-green {
        font-weight: 600;
        color: #16a34a;
    }

    .cu-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .cu-status-pending {
        color: #d97706;
    }

    .cu-status-in-progress {
        color: #185fa5;
    }

    .cu-status-completed {
        color: #0f6e56;
    }

    .cu-status-cancelled {
        color: #dc2626;
    }

    .cu-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
    }

    .cu-empty-icon {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 16px;
    }

    .cu-empty-text {
        font-size: 14px;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    .cu-button-green {
        background: var(--green-dark);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
    }

    .cu-button-green:hover {
        background: #15803d;
    }

    .cu-button-pay {
        background: var(--green-dark);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        line-height: 1.2;
    }

    .cu-button-pay:hover {
        background: #15803d;
    }

    .cu-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .cu-modal-overlay.active {
        display: flex;
    }

    .cu-modal {
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .cu-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .cu-modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .cu-modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cu-modal-close:hover {
        color: #1f2937;
    }

    .cu-modal-body {
        padding: 20px;
    }

    .cu-form-group {
        margin-bottom: 16px;
    }

    .cu-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .cu-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        font-family: inherit;
    }

    .cu-input:focus {
        outline: none;
        border-color: var(--green-dark);
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .cu-modal-footer {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        justify-content: flex-end;
    }

    .cu-button-secondary {
        background: #f3f4f6;
        color: #1f2937;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cu-button-secondary:hover {
        background: #e5e7eb;
    }

    .cu-button-submit {
        background: var(--green-dark);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cu-button-submit:hover {
        background: #15803d;
    }

    .cu-footer {
        display: flex;
        justify-content: center;
        gap: 24px;
        padding: 20px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        margin-top: 40px;
    }

    .cu-footer-text {
        font-size: 12px;
        color: #6b7280;
    }

    @media (max-width: 991px) {
        .cu-topbar {
            padding: 0 16px;
            height: 56px;
        }

        .cu-logo-text {
            font-size: 14px;
        }

        .cu-nav-link {
            font-size: 11px;
            padding: 4px 8px;
        }

        .cu-nav-link i {
            font-size: 14px;
        }

        .cu-hero-title {
            font-size: 24px;
        }

        .cu-stats {
            grid-template-columns: repeat(3, 1fr);
        }

        .cu-stat-value {
            font-size: 24px;
        }
    }

    @media (max-width: 768px) {
        .cu-topbar-right {
            gap: 12px;
        }

        .cu-nav-link span {
            display: none;
        }

        .cu-hero-title {
            font-size: 20px;
        }

        .cu-hero-subtitle {
            font-size: 13px;
        }

        .cu-stats {
            gap: 12px;
        }

        .cu-stat-box {
            padding: 12px;
        }

        .cu-table th,
        .cu-table td {
            padding: 12px;
            font-size: 12px;
        }

        .cu-table th {
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .cu-topbar {
            height: 52px;
            padding: 0 12px;
        }

        .cu-logo-text {
            font-size: 12px;
        }

        .cu-hero {
            padding: 24px 12px;
        }

        .cu-hero-title {
            font-size: 18px;
        }

        .cu-hero-title span {
            display: block;
        }

        .cu-stats {
            grid-template-columns: 1fr;
            max-width: 100%;
        }

        .cu-content {
            margin: 12px;
        }

        .cu-table th,
        .cu-table td {
            padding: 8px;
            font-size: 11px;
        }

        .cu-table th {
            font-size: 10px;
        }

        .cu-service-item,
        .cu-service-extra {
            font-size: 11px;
        }

        .cu-footer {
            flex-direction: column;
            gap: 8px;
            padding: 16px;
        }

        .cu-footer-text {
            font-size: 11px;
            text-align: center;
        }
    }
</style>

<nav class="cu-topbar">
    <div class="cu-topbar-left">
        <div class="cu-logo-icon">
            <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>
        <span class="cu-logo-text">VM<span>MS</span></span>
    </div>
    <div class="cu-topbar-right">
        <a href="{{ route('customer.dashboard') }}" class="cu-nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a>
        <a href="{{ route('customer.services') }}" class="cu-nav-link active"><i class="fas fa-tools"></i><span>My Services</span></a>
        <a href="{{ route('customer.payables') }}" class="cu-nav-link"><i class="fas fa-credit-card"></i><span>My Payables</span></a>
        <a href="{{ route('customer.payments') }}" class="cu-nav-link"><i class="fas fa-receipt"></i><span>Payment History</span></a>
        <div class="cu-user-pill" onclick="toggleCuDropdown(event)">
            <div class="cu-user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <span class="cu-user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down cu-user-chevron"></i>
            <div class="cu-dropdown" id="cuDropdown">
                <div class="cu-dropdown-divider"></div>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="cu-dropdown-item logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="cu-hero">
    <div class="cu-hero-badge">My Services</div>
    <h1 class="cu-hero-title">Your <span>Service Requests</span></h1>
    <p class="cu-hero-subtitle">Track all your submitted vehicle service requests</p>
    @if($serviceRequests->count() > 0)
        <div class="cu-stats">
            <div class="cu-stat-box">
                <div class="cu-stat-value">{{ $serviceRequests->count() }}</div>
                <div class="cu-stat-label">Total Requests</div>
            </div>
            <div class="cu-stat-box">
                <div class="cu-stat-value">{{ $serviceRequests->where('status', 'in-progress')->count() }}</div>
                <div class="cu-stat-label">In Progress</div>
            </div>
            <div class="cu-stat-box">
                <div class="cu-stat-value">{{ $serviceRequests->where('status', 'completed')->count() }}</div>
                <div class="cu-stat-label">Completed</div>
            </div>
        </div>
    @endif
</div>

@if($serviceRequests->isEmpty())
    <div class="cu-content">
        <div class="cu-empty-state">
            <div class="cu-empty-icon">
                <i class="fas fa-tools"></i>
            </div>
            <p class="cu-empty-text">No service requests yet.</p>
            <a href="{{ route('customer.dashboard') }}" class="cu-button-green">Browse Services</a>
        </div>
    </div>
@else
    <div class="cu-content">
        <div class="cu-table-wrapper">
            <table class="cu-table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Vehicle</th>
                        <th>Services</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceRequests as $sr)
                        <tr>
                            <td>
                                <span class="cu-ref-id">#{{ $sr->id }}</span>
                            </td>
                            <td>
                                <div class="cu-vehicle-plate">{{ $sr->vehicle_plate }}</div>
                                <div class="cu-vehicle-details">{{ $sr->vehicle_model }} · {{ $sr->vehicle_type }}</div>
                            </td>
                            <td>
                                <div class="cu-services-list">
                                    @forelse($sr->services as $index => $service)
                                        <div class="cu-service-item">{{ $service->name }}</div>
                                        @if($index === 0 && $sr->services->count() > 1)
                                            <div class="cu-service-extra">+{{ $sr->services->count() - 1 }} more</div>
                                        @endif
                                    @empty
                                        <div class="cu-service-item">No services</div>
                                    @endforelse
                                </div>
                            </td>
                            <td>{{ $sr->created_at->format('M d, Y') }}</td>
                            <td class="cu-amount-green">₱{{ number_format($sr->total_amount ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $statusClass = 'cu-status-' . str_replace('_', '-', $sr->status);
                                @endphp
                                <span class="cu-status-badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $sr->status)) }}</span>
                            </td>
                            <td>
                                @php
                                    $verifiedAmt = $sr->payments->where('status','verified')->sum('amount');
                                    $balance = $sr->total_amount - $verifiedAmt;
                                    $hasPendingBalance = $sr->payments
                                        ->whereIn('payment_type', ['remaining', 'full'])
                                        ->where('status', 'pending')
                                        ->count() > 0;
                                @endphp

                                @if($sr->status === 'completed' && $balance > 0 && !$hasPendingBalance)
                                    {{-- Completed with balance, no pending submission --}}
                                    <button 
                                        type="button" 
                                        class="cu-button-pay"
                                        onclick="openPaymentModal(
                                            '{{ $sr->id }}',
                                            '{{ number_format($sr->total_amount, 2) }}',
                                            '{{ number_format($verifiedAmt, 2) }}',
                                            '{{ number_format($balance, 2) }}',
                                            false
                                        )"
                                        style="padding:7px 16px;
                                            background:linear-gradient(135deg,#1a5c42,#2d9b6f);
                                            color:white; border:none; border-radius:8px;
                                            font-size:12px; font-weight:700; cursor:pointer;">
                                        Pay ₱{{ number_format($balance, 2) }}
                                    </button>
                                @elseif($sr->status === 'completed' && $hasPendingBalance)
                                    {{-- Proof submitted, awaiting verification --}}
                                    <span style="color:#d97706; font-size:12px; font-weight:600;">
                                        <i class="fas fa-hourglass-half"></i> Pending Verification
                                    </span>
                                @elseif($sr->status === 'completed' && $balance <= 0)
                                    {{-- Fully paid --}}
                                    <span style="color:#16a34a; font-size:12px; font-weight:600;">
                                        <i class="fas fa-check-circle"></i> Fully Paid
                                    </span>
                                @elseif($sr->status !== 'cancelled')
                                    {{-- Still in progress, show nothing or status --}}
                                    <span style="color:#9ca3af; font-size:12px;">In Service</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            @if($serviceRequests->hasPages())
                {{ $serviceRequests->links('components.pagination') }}
            @endif
        </div>
    </div>
@endif

<footer class="cu-footer">
    <span class="cu-footer-text">VMMS · Vehicle Maintenance Management System</span>
    <span class="cu-footer-text">Davao, Philippines · © {{ date('Y') }}</span>
</footer>

<!-- Payment Modal -->
<div id="paymentModal" style="display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.5); z-index:9999;
    align-items:center; justify-content:center;
    backdrop-filter:blur(4px);">

    <div style="background:white; border-radius:16px; width:90%;
        max-width:520px; max-height:90vh; overflow-y:auto;
        box-shadow:0 24px 60px rgba(0,0,0,0.2); position:relative;">

        <!-- Header -->
        <div style="padding:20px 24px 0;
            display:flex; align-items:center; justify-content:space-between;
            border-bottom:1px solid #f3f4f6; padding-bottom:16px;">
            <div>
                <h3 style="margin:0; font-size:18px; font-weight:800;
                    color:#1a2e1a;">Upload Payment Proof</h3>
                <p style="margin:4px 0 0; font-size:12px; color:#9ca3af;">
                    Scan the QR code and upload your receipt below
                </p>
            </div>
            <button onclick="closePaymentModal()"
                style="border:none; background:#f3f4f6; border-radius:8px;
                width:32px; height:32px; font-size:18px; cursor:pointer;
                color:#6b7280; line-height:1;">×</button>
        </div>

        <form id="paymentForm" method="POST"
            action="{{ route('customer.payables.pay') }}"
            enctype="multipart/form-data"
            style="padding:20px 24px 24px;">
            @csrf
            <input type="hidden" name="service_request_id" id="modal_sr_id">

            <!-- Balance Summary -->
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr;
                gap:12px; margin-bottom:20px;">

                <div style="background:#f0fdf4; border:1px solid #bbf7d0;
                    border-radius:10px; padding:12px; text-align:center;">
                    <div style="font-size:10px; font-weight:700;
                        text-transform:uppercase; letter-spacing:1px;
                        color:#16a34a; margin-bottom:4px;">Total</div>
                    <div id="modal_total"
                        style="font-size:16px; font-weight:800; color:#1a2e1a;">
                        —
                    </div>
                </div>

                <div style="background:#fefce8; border:1px solid #fde68a;
                    border-radius:10px; padding:12px; text-align:center;">
                    <div style="font-size:10px; font-weight:700;
                        text-transform:uppercase; letter-spacing:1px;
                        color:#d97706; margin-bottom:4px;">Paid</div>
                    <div id="modal_paid"
                        style="font-size:16px; font-weight:800; color:#1a2e1a;">
                        —
                    </div>
                </div>

                <div style="background:#fef2f2; border:1px solid #fecaca;
                    border-radius:10px; padding:12px; text-align:center;">
                    <div style="font-size:10px; font-weight:700;
                        text-transform:uppercase; letter-spacing:1px;
                        color:#dc2626; margin-bottom:4px;">Balance Due</div>
                    <div id="modal_balance"
                        style="font-size:16px; font-weight:800; color:#dc2626;">
                        —
                    </div>
                </div>

            </div>

            <!-- Pending notice -->
            <div id="modal_pending_notice"
                style="display:none; background:#fff7ed;
                border:1px solid #fed7aa; border-radius:10px;
                padding:10px 14px; margin-bottom:16px;
                font-size:12px; color:#c2410c; font-weight:600;">
                ⏳ You have a pending proof awaiting verification.
                You may still submit another if needed.
            </div>

            <!-- QR Code Section -->
            <div style="background:#f8fafc; border:1px solid #e5e7eb;
                border-radius:12px; padding:20px;
                text-align:center; margin-bottom:20px;">

                <div style="font-size:11px; font-weight:700;
                    text-transform:uppercase; letter-spacing:1px;
                    color:#6b7280; margin-bottom:12px;">
                    Scan to Pay via GCash / Maya
                </div>

                <img src="{{ asset('images/payment-qr.jpg') }}"
                    alt="Payment QR Code"
                    style="width:180px; height:180px; object-fit:contain;
                    border-radius:8px; border:1px solid #e5e7eb;">

                <div style="margin-top:12px; font-size:12px; color:#6b7280;">
                    Amount to send: 
                    <strong id="modal_balance_qr" style="color:#1a2e1a;">—</strong>
                </div>

                <div style="margin-top:6px; font-size:11px; color:#9ca3af;">
                    After payment, upload your screenshot below
                </div>
            </div>

            <!-- File Upload -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:700;
                    color:#374151; margin-bottom:8px;">
                    Proof of Payment <span style="color:#dc2626;">*</span>
                </label>

                <label for="proof_image"
                    style="display:flex; flex-direction:column;
                    align-items:center; justify-content:center;
                    border:2px dashed #d1d5db; border-radius:10px;
                    padding:24px; cursor:pointer; transition:border-color 0.2s;
                    background:#fafafa;" id="upload_label">
                    <i class="fas fa-cloud-upload-alt"
                        style="font-size:28px; color:#9ca3af; margin-bottom:8px;"></i>
                    <span id="upload_text"
                        style="font-size:13px; color:#6b7280; font-weight:600;">
                        Click to upload screenshot
                    </span>
                    <span style="font-size:11px; color:#9ca3af; margin-top:4px;">
                        JPG, PNG, WEBP up to 5MB
                    </span>
                    <input type="file" id="proof_image" accept="image/jpg,image/jpeg,image/png" name="proof_image"
                        accept="image/*" required
                        style="display:none;"
                        onchange="previewFile(this)">
                </label>

                <!-- Image preview -->
                <div id="img_preview" style="display:none; margin-top:10px;
                    text-align:center;">
                    <img id="preview_img" style="max-height:140px;
                        border-radius:8px; border:1px solid #e5e7eb;">
                </div>
            </div>

            <!-- Note -->
            <div style="background:#f0fdf4; border:1px solid #bbf7d0;
                border-radius:8px; padding:10px 14px;
                font-size:12px; color:#166534; margin-bottom:20px;">
                <strong>Note:</strong> Your payment will be verified by our admin
                within 24 hours. You'll be notified once confirmed.
            </div>

            <!-- Buttons -->
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <button type="button" onclick="closePaymentModal()"
                    style="padding:11px 24px; border:1.5px solid #e5e7eb;
                    background:white; border-radius:8px; font-size:14px;
                    font-weight:600; color:#6b7280; cursor:pointer;">
                    Cancel
                </button>
                <button type="submit"
                    style="padding:11px 28px;
                    background:linear-gradient(135deg, #1a5c42, #2d9b6f);
                    color:white; border:none; border-radius:8px;
                    font-size:14px; font-weight:700; cursor:pointer;
                    display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-paper-plane"></i> Submit Proof
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function toggleCuDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('cuDropdown');
        dropdown.classList.toggle('active');
    }

    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('cuDropdown');
        const userPill = event.target.closest('.cu-user-pill');
        if (!userPill) {
            dropdown.classList.remove('active');
        }
    });

    function openPaymentModal(srId, total, paid, balance, hasPending) {
        document.getElementById('modal_sr_id').value = srId;
        document.getElementById('modal_total').textContent = '₱' + total;
        document.getElementById('modal_paid').textContent = '₱' + paid;
        document.getElementById('modal_balance').textContent = '₱' + balance;
        document.getElementById('modal_balance_qr').textContent = '₱' + balance;

        const notice = document.getElementById('modal_pending_notice');
        notice.style.display = hasPending ? 'block' : 'none';

        // Reset file input
        document.getElementById('proof_image').value = '';
        document.getElementById('upload_text').textContent = 'Click to upload screenshot';
        document.getElementById('img_preview').style.display = 'none';

        const modal = document.getElementById('paymentModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function previewFile(input) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById('upload_text').textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('preview_img').src = e.target.result;
            document.getElementById('img_preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Close on backdrop click
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target === this) closePaymentModal();
    });
</script>
@endsection
