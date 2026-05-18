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

    .cu-vehicle-details {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 2px;
    }

    .cu-amount-bold {
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

    .cu-status-verified {
        color: #0f6e56;
    }

    .cu-proof-thumbnail {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cu-proof-thumbnail:hover {
        transform: scale(1.2);
    }

    .cu-proof-dash {
        color: #9ca3af;
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

    .cu-lightbox-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 3000;
        align-items: center;
        justify-content: center;
    }

    .cu-lightbox-overlay.active {
        display: flex;
    }

    .cu-lightbox-image {
        max-width: 90vw;
        max-height: 90vh;
        border-radius: 8px;
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
        <a href="{{ route('customer.services') }}" class="cu-nav-link"><i class="fas fa-tools"></i><span>My Services</span></a>
        <a href="{{ route('customer.payables') }}" class="cu-nav-link"><i class="fas fa-credit-card"></i><span>My Payables</span></a>
        <a href="{{ route('customer.payments') }}" class="cu-nav-link active"><i class="fas fa-receipt"></i><span>Payment History</span></a>
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
    <div class="cu-hero-badge">Payment History</div>
    <h1 class="cu-hero-title">Your <span>Payment Records</span></h1>
    <p class="cu-hero-subtitle">View all payments made for your service requests</p>
    @if($payments->count() > 0)
        <div class="cu-stats">
            <div class="cu-stat-box">
                <div class="cu-stat-value">{{ $payments->count() }}</div>
                <div class="cu-stat-label">Total Payments</div>
            </div>
            <div class="cu-stat-box">
                <div class="cu-stat-value">{{ $payments->where('status', 'verified')->count() }}</div>
                <div class="cu-stat-label">Verified</div>
            </div>
            <div class="cu-stat-box">
                <div class="cu-stat-value">₱{{ number_format($payments->sum('amount'), 2) }}</div>
                <div class="cu-stat-label">Total Paid</div>
            </div>
        </div>
    @endif
</div>

@if($payments->isEmpty())
    <div class="cu-content">
        <div class="cu-empty-state">
            <div class="cu-empty-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <p class="cu-empty-text">No payment history yet. Your payments will appear here.</p>
            <a href="{{ route('customer.dashboard') }}" class="cu-button-green">Back to Dashboard</a>
        </div>
    </div>
@else
    <div class="cu-content">
        <div class="cu-table-wrapper">
            <table class="cu-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Request</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Proof</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div>#{{ $payment->serviceRequest->id ?? 'N/A' }}</div>
                                <div class="cu-vehicle-details">{{ $payment->serviceRequest->vehicle_plate ?? 'N/A' }} · {{ $payment->serviceRequest->vehicle_model ?? '' }}</div>
                            </td>
                            <td>
                                @if($payment->payment_type === 'downpayment')
                                    <span style="font-size:13px;font-weight:600;color:#854d0e;">Downpayment</span>
                                @elseif($payment->payment_type === 'full')
                                    <span style="font-size:13px;font-weight:600;color:#166534;">Full Payment</span>
                                @elseif($payment->payment_type === 'remaining')
                                    <span style="font-size:13px;font-weight:600;color:#1e40af;">Remaining Balance</span>
                                @else
                                    <span style="color:#9ca3af;">—</span>
                                @endif
                            </td>
                            <td class="cu-amount-bold">₱{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                @php
                                    $statusClass = 'cu-status-' . $payment->status;
                                @endphp
                                <span class="cu-status-badge {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td>
                                @if($payment->proof_image)
                                    <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Payment proof" class="cu-proof-thumbnail" onclick="openLightbox('{{ asset('storage/' . $payment->proof_image) }}')">
                                @else
                                    <span class="cu-proof-dash">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
            @if($payments->hasPages())
                {{ $payments->links('components.pagination') }}
            @endif
        </div>
    </div>
@endif

<footer class="cu-footer">
    <span class="cu-footer-text">VMMS · Vehicle Maintenance Management System</span>
    <span class="cu-footer-text">Davao, Philippines · © {{ date('Y') }}</span>
</footer>

<!-- Lightbox Modal -->
<div class="cu-lightbox-overlay" id="lightboxOverlay" onclick="closeLightbox(event)">
    <img id="lightboxImage" src="" alt="Payment proof" class="cu-lightbox-image">
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

    function openLightbox(imageUrl) {
        document.getElementById('lightboxImage').src = imageUrl;
        document.getElementById('lightboxOverlay').classList.add('active');
    }

    function closeLightbox(event) {
        if (event && event.target !== document.getElementById('lightboxOverlay')) {
            return;
        }
        document.getElementById('lightboxOverlay').classList.remove('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('lightboxOverlay').classList.remove('active');
        }
    });
</script>
@endsection
