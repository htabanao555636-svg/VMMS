@extends('layouts.admin')

@section('content')
<section id="service-request-detail" class="content-section active">
    <div class="detail-header">
        <div class="header-left">
            <a href="{{ route('staff.service-request') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="page-title">Service Request <span class="request-id">#{{ $serviceRequest->id }}</span></h1>
                <p class="page-subtitle">View and manage request details</p>
            </div>
        </div>
        <div class="status-display">
            <span class="status-badge status-{{ strtolower($serviceRequest->status) }}">
                <i class="fas fa-{{ 
                    $serviceRequest->status === 'pending' ? 'hourglass-start' :
                    ($serviceRequest->status === 'approved' ? 'check-circle' :
                    ($serviceRequest->status === 'in_progress' ? 'cog' :
                    ($serviceRequest->status === 'completed' ? 'flag-checkered' : 'times-circle')))
                }}"></i>
                {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
            </span>
        </div>
    </div>

    <div class="detail-container">
        <!-- Left Column -->
        <div class="detail-column">
            <!-- Customer Information -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-user-circle"></i> Customer Information
                    </h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Customer Name</label>
                            <p class="info-value">{{ $serviceRequest->customer->name }}</p>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <p class="info-value">
                                <i class="fas fa-envelope"></i> {{ $serviceRequest->customer->email }}
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Phone</label>
                            <p class="info-value">
                                <i class="fas fa-phone"></i> {{ $serviceRequest->customer->phone ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Vehicle</label>
                            <p class="info-value">
                                <i class="fas fa-car"></i> {{ $serviceRequest->vehicle->model ?? 'N/A' }}
                                <br>
                                <span class="plate-info">{{ $serviceRequest->vehicle->plate_number ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-wrench"></i> Services Requested
                    </h2>
                </div>
                <div class="card-body">
                    @if($serviceRequest->services()->count() > 0)
                    <div class="services-list">
                        @foreach($serviceRequest->services as $service)
                        <div class="service-item">
                            <div class="service-info">
                                <h3 class="service-name">{{ $service->name }}</h3>
                                <p class="service-category">{{ $service->category->name ?? 'N/A' }}</p>
                            </div>
                            <div class="service-price">₱{{ number_format($service->price, 2) }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="empty-state"><i class="fas fa-inbox"></i> No services selected</p>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-credit-card"></i> Payment Information
                    </h2>
                </div>
                <div class="card-body">
                    <div class="payment-grid">
                        <div class="payment-item">
                            <label>Total Amount</label>
                            <p class="amount-value">₱{{ number_format($serviceRequest->total_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="payment-item">
                            <label>Downpayment</label>
                            <p class="amount-value downpayment">₱{{ number_format($serviceRequest->downpayment_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="payment-item">
                            <label>Balance Due</label>
                            <p class="amount-value balance">₱{{ number_format($serviceRequest->remaining_balance ?? 0, 2) }}</p>
                        </div>
                        <div class="payment-item">
                            <label>Payment Status</label>
                            <p>
                                <span class="badge badge-{{ 
                                    $serviceRequest->payment_status === 'downpayment_pending' ? 'warning' :
                                    ($serviceRequest->payment_status === 'downpayment_verified' ? 'info' :
                                    ($serviceRequest->payment_status === 'fully_paid' ? 'success' : 'danger'))
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $serviceRequest->payment_status)) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="detail-column">
            <!-- Timeline -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-timeline"></i> Timeline
                    </h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot requested"></div>
                            <div class="timeline-content">
                                <h4>Requested</h4>
                                <p>{{ $serviceRequest->requested_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @if($serviceRequest->completed_date)
                        <div class="timeline-item">
                            <div class="timeline-dot completed"></div>
                            <div class="timeline-content">
                                <h4>Completed</h4>
                                <p>{{ $serviceRequest->completed_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="timeline-item">
                            <div class="timeline-dot current"></div>
                            <div class="timeline-content">
                                <h4>Current Status</h4>
                                <p class="status-text">{{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mechanic Assignment -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-tools"></i> Mechanic Assignment
                    </h2>
                </div>
                <div class="card-body">
                    @if($serviceRequest->mechanic)
                    <div class="mechanic-card">
                        <div class="mechanic-avatar">
                            <i class="fas fa-user-wrench"></i>
                        </div>
                        <div class="mechanic-details">
                            <h3>{{ $serviceRequest->mechanic->name }}</h3>
                            <p class="mechanic-spec">{{ $serviceRequest->mechanic->specialization }}</p>
                            <div class="mechanic-contact">
                                <a href="tel:{{ $serviceRequest->mechanic->phone }}">
                                    <i class="fas fa-phone"></i> {{ $serviceRequest->mechanic->phone ?? 'N/A' }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="empty-state">
                        <i class="fas fa-exclamation-circle"></i> No mechanic assigned yet
                    </p>
                    @endif
                </div>
            </div>

            <!-- Assign Mechanic Form -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-user-plus"></i> Reassign Mechanic
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.service-request.assign-mechanic', $serviceRequest) }}" method="POST" class="form-compact">
                        @csrf
                        <div class="form-group">
                            <select name="mechanic_id" class="form-control" required>
                                <option value="">-- Select Mechanic --</option>
                                @foreach(\App\Models\Mechanic::where('status', 'active')->get() as $mechanic)
                                <option value="{{ $mechanic->id }}" {{ $serviceRequest->mechanic_id == $mechanic->id ? 'selected' : '' }}>
                                    {{ $mechanic->name }} - {{ $mechanic->specialization }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-assign">
                            <i class="fas fa-check"></i> Assign
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Update Form -->
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-sync-alt"></i> Update Status
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.service-request.update-status', $serviceRequest) }}" method="POST" class="form-compact">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $serviceRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $serviceRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="in_progress" {{ $serviceRequest->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $serviceRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $serviceRequest->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-update">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </form>
                </div>
            </div>

            <!-- Staff Notes -->
            @if($serviceRequest->staff_notes)
            <div class="info-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-sticky-note"></i> Staff Notes
                    </h2>
                </div>
                <div class="card-body">
                    <div class="notes-box">
                        {{ $serviceRequest->staff_notes }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .btn-back {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f0f0f0;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #e0e0e0;
    }

    .request-id {
        color: #3498db;
        font-weight: 700;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 13px;
        margin-top: 4px;
    }

    .status-display {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        color: #856404;
    }

    .status-approved {
        color: #0c5460;
    }

    .status-in_progress {
        color: #084298;
    }

    .status-completed {
        color: #0f5132;
    }

    .status-cancelled {
        color: #842029;
    }

    .detail-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .detail-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        background: #f9f9f9;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #3498db;
    }

    .card-body {
        padding: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .info-item {
        padding: 12px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .info-item label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #95a5a6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        margin: 0;
        color: #2c3e50;
        font-weight: 600;
        font-size: 13px;
        line-height: 1.6;
    }

    .info-value i {
        color: #3498db;
        margin-right: 6px;
    }

    .plate-info {
        color: #95a5a6;
        font-family: monospace;
        font-size: 12px;
    }

    .services-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .service-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 3px solid #3498db;
    }

    .service-info {
        flex: 1;
    }

    .service-name {
        margin: 0 0 4px 0;
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
    }

    .service-category {
        margin: 0;
        font-size: 11px;
        color: #95a5a6;
    }

    .service-price {
        font-size: 14px;
        font-weight: 700;
        color: #27ae60;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .payment-item {
        padding: 16px;
        background: linear-gradient(135deg, #f5f8fb 0%, #ffffff 100%);
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .payment-item label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #95a5a6;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .amount-value {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #27ae60;
    }

    .amount-value.downpayment {
        color: #f39c12;
    }

    .amount-value.balance {
        color: #e74c3c;
    }

    .timeline {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .timeline-item {
        display: flex;
        gap: 12px;
    }

    .timeline-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .timeline-dot.requested {
        background: #3498db;
    }

    .timeline-dot.completed {
        background: #27ae60;
    }

    .timeline-dot.current {
        background: #f39c12;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    .timeline-content h4 {
        margin: 0 0 4px 0;
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
    }

    .timeline-content p {
        margin: 0;
        font-size: 12px;
        color: #95a5a6;
    }

    .status-text {
        color: #3498db !important;
        font-weight: 600;
    }

    .mechanic-card {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: linear-gradient(135deg, #faf5ff 0%, #ffffff 100%);
        border-radius: 8px;
        border: 2px solid #e8dff5;
    }

    .mechanic-avatar {
        width: 56px;
        height: 56px;
        border-radius: 8px;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .mechanic-details h3 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
    }

    .mechanic-spec {
        margin: 0 0 8px 0;
        font-size: 12px;
        color: #95a5a6;
    }

    .mechanic-contact a {
        color: #3498db;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mechanic-contact a:hover {
        color: #2980b9;
    }

    .form-compact {
        display: flex;
        gap: 8px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .btn {
        padding: 10px 14px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-assign {
        background: linear-gradient(135deg, #27ae60, #229954);
        color: white;
    }

    .btn-assign:hover {
        box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
    }

    .btn-update {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .btn-update:hover {
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
    }

    .notes-box {
        padding: 12px;
        background: #f9f9f9;
        border-radius: 6px;
        border-left: 3px solid #f39c12;
        color: #2c3e50;
        font-size: 13px;
        line-height: 1.6;
    }

    .empty-state {
        text-align: center;
        padding: 20px;
        color: #95a5a6;
        font-size: 13px;
    }

    .empty-state i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
        opacity: 0.5;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-success {
        background: #d1e7dd;
        color: #0f5132;
    }

    .badge-danger {
        background: #f8d7da;
        color: #842029;
    }

    @media (max-width: 1024px) {
        .detail-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .info-grid,
        .payment-grid {
            grid-template-columns: 1fr;
        }

        .form-compact {
            flex-direction: column;
        }
    }
</style>
@endsection
