@extends('layouts.admin')

@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\ServiceRequest> $serviceRequests */
    /** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\Mechanic> $mechanics */
    /** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\User> $staff */
    /** @var string[] $statuses */
@endphp

@section('content')

<style>
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
@keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

/* ===== HEADER & LAYOUT ===== */
.content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
    animation: fadeInUp 0.5s ease-out;
}

.header-left {
    flex: 1;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
    letter-spacing: -0.3px;
}

.page-subtitle {
    color: #6b7280;
    font-size: 14px;
    margin-top: 8px;
}

.btn-add-new {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 11px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    border: none;
    cursor: pointer;
}

.btn-add-new:hover {
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
    transform: translateY(-2px);
}

/* ===== FLASH MESSAGES ===== */
.alert-with-icon {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 600;
    animation: slideInLeft 0.4s ease-out;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border: 1px solid #6ee7b7;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
}

.alert-error {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #fca5a5;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: box-shadow 0.3s ease;
    animation: slideInLeft 0.4s ease-out;
}

.filter-section:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}

.filter-form {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-group {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
    width: 100%;
}

.filter-input,
.filter-select {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
    color: #1f2937;
}

.filter-input::placeholder {
    color: #9ca3af;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-input {
    flex-grow: 1;
    min-width: 250px;
}

.filter-select {
    min-width: 160px;
}

.btn-filter,
.btn-clear-filter {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-filter {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
}

.btn-filter:hover {
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
}

.btn-clear-filter {
    background: #f3f4f6;
    color: #6b7280;
}

.btn-clear-filter:hover {
    background: #e5e7eb;
    color: #1f2937;
}

/* ===== TABLE STYLING ===== */
.content-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    animation: scaleIn 0.4s ease-out;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.data-table thead {
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    border-bottom: 2px solid #e5e7eb;
}

.data-table th {
    padding: 14px 12px;
    text-align: left;
    font-weight: 800;
    color: #374151;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e5e7eb;
}

.data-table td {
    padding: 13px 12px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #111;
    vertical-align: middle;
}

.data-table tbody tr {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.data-table tbody tr:hover {
    background: linear-gradient(90deg, #f9fafb 0%, #fff 50%);
    box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.1);
}

.data-table tbody tr.status-pending { border-left: 4px solid #f59e0b; }
.data-table tbody tr.status-approved { border-left: 4px solid #3b82f6; }
.data-table tbody tr.status-in_progress { border-left: 4px solid #8b5cf6; }
.data-table tbody tr.status-completed { border-left: 4px solid #10b981; }
.data-table tbody tr.status-cancelled { border-left: 4px solid #ef4444; }

/* ===== TEXT STYLES ===== */
.request-id {
    color: #3b82f6;
    font-weight: 700;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    transition: background 0.2s;
}

.request-id:hover {
    background: #f3f4f6;
}

.customer-name {
    font-weight: 700;
    color: #1f2937;
}

.vehicle-info {
    font-weight: 600;
    color: #34495e;
}

.plate-number {
    color: #95a5a6;
    font-family: monospace;
    font-weight: 600;
}

.service-list {
    color: #34495e;
    line-height: 1.6;
}

.mechanic-badge {
    display: inline-block;
    background: linear-gradient(135deg, #e0f2fe 0%, #cffafe 100%);
    color: #0369a1;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 12px;
    box-shadow: 0 2px 4px rgba(3, 105, 161, 0.1);
}

.badge-unassigned {
    color: #dc2626;
    font-weight: 700;
    font-size: 12px;
}

.staff-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f3f4f6;
    color: #4b5563;
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 12px;
}

.amount {
    color: #059669;
    font-size: 14px;
    font-weight: 800;
}

.balance-due {
    color: #dc2626;
    font-weight: 700;
    font-size: 11px;
}

.date-badge {
    display: inline-block;
    background: #f0f9ff;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.completed-date {
    color: #059669;
    font-weight: 600;
}

.text-muted {
    color: #6b7280;
    font-size: 12px;
}

/* ===== BADGES ===== */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    transition: all 0.2s ease;
}

.badge:hover { transform: scale(1.05); }

.badge-warning { color: #92400e; }
.badge-info { color: #0c4a6e; }
.badge-primary { color: #084298; }
.badge-success { color: #065f46; }
.badge-danger { color: #991b1b; }

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 7px;
    align-items: center;
}

.action-buttons form {
    margin: 0;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    position: relative;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-action:active::before {
    width: 300px;
    height: 300px;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-action.view {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.btn-action.edit {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.btn-action.delete {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #9ca3af;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    padding: 2rem 1.5rem;
    text-align: center;
}

/* ===== MODALS ===== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
    animation: fadeInUp 0.3s ease-out;
}

.modal-overlay.active {
    display: flex;
}

.confirm-modal {
    background: white;
    padding: 32px;
    border-radius: 12px;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    text-align: center;
    animation: scaleIn 0.4s ease-out;
}

.confirm-modal h3 {
    margin: 0 0 12px;
    font-size: 18px;
    color: #1f2937;
    font-weight: 700;
}

.confirm-modal p {
    margin: 0 0 24px;
    color: #6b7280;
    font-size: 14px;
}

.confirm-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.confirm-btn {
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s;
}

.confirm-btn-primary {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.confirm-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.confirm-btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.confirm-btn-secondary:hover {
    background: #d1d5db;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .content-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
    .filter-input { min-width: 100%; }
    .filter-select { min-width: 100%; }
    .data-table th, .data-table td { padding: 10px 8px; font-size: 12px; }
    .btn-action { width: 34px; height: 34px; font-size: 12px; }
}
</style>
<section id="service-request" class="content-section active">
    <div class="content-header">
        <div class="header-left">
            <h1 class="page-title">Service Requests</h1>
            <p class="page-subtitle">Manage and track all service requests</p>
        </div>
        <a href="{{ route('staff.service-request.create') }}" class="btn-add-new">
            <i class="fas fa-plus"></i> Create New Request
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-with-icon">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error alert-with-icon">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" action="{{ route('staff.service-request') }}" class="filter-form">
            <div class="filter-group">
                <input type="text" name="search" placeholder="Search by customer name or email..." 
                       class="filter-input" value="{{ request('search') }}">
                
                <select name="status" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="mechanic_id" class="filter-select">
                    <option value="">All Mechanics</option>
                    @foreach($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}" {{ request('mechanic_id') == $mechanic->id ? 'selected' : '' }}>
                        {{ $mechanic->name }}
                    </option>
                    @endforeach
                </select>

                <select name="assigned_by" class="filter-select">
                    <option value="">All Staff</option>
                    @foreach($staff as $staffMember)
                    <option value="{{ $staffMember->id }}" {{ request('assigned_by') == $staffMember->id ? 'selected' : '' }}>
                        {{ $staffMember->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('staff.service-request') }}" class="btn-clear-filter">
                    <i class="fas fa-redo"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <div class="content-box">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Services</th>
                        <th>Mechanic</th>
                        <th>Assigned By</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($serviceRequests ?? [] as $serviceRequest)
                    <tr class="status-{{ strtolower($serviceRequest->status) }}">
                        <td><span class="request-id" onclick="copyToClipboard('#{{ $serviceRequest->id }}', this)" title="Click to copy">#{{ $serviceRequest->id }}</span></td>
                        <td>
                            <span class="customer-name">{{ $serviceRequest->customer->name ?? 'N/A' }}</span>
                            <br>
                            <small class="text-muted">{{ $serviceRequest->customer->email ?? '' }}</small>
                        </td>
                        <td>
                            <span class="vehicle-info">{{ $serviceRequest->vehicle->model ?? 'N/A' }}</span>
                            <br>
                            <small class="plate-number">{{ $serviceRequest->vehicle->plate_number ?? '-' }}</small>
                        </td>
                        <td>
                            @if($serviceRequest->services->count() > 0)
                                <small class="service-list">
                                    {{ $serviceRequest->services->pluck('name')->implode(', ') }}
                                </small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($serviceRequest->mechanic)
                                <span class="mechanic-badge">{{ $serviceRequest->mechanic->name }}</span>
                                <br>
                                <small class="text-muted">{{ $serviceRequest->mechanic->specialization }}</small>
                            @else
                                <span class="badge-unassigned"><i class="fas fa-exclamation-circle"></i> Unassigned</span>
                            @endif
                        </td>
                        <td>
                            @if($serviceRequest->assignedBy)
                                <span class="staff-badge">
                                    <i class="fas fa-user-tie"></i> {{ $serviceRequest->assignedBy->name }}
                                </span>
                                @if($serviceRequest->assigned_at)
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($serviceRequest->assigned_at)->format('M d, Y') }}</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <strong class="amount">₱{{ number_format($serviceRequest->total_amount ?? 0, 2) }}</strong>
                            @if($serviceRequest->remaining_balance > 0)
                            <br>
                            <small class="balance-due">Balance: ₱{{ number_format($serviceRequest->remaining_balance, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ 
                                $serviceRequest->status === 'pending' ? 'warning' :
                                ($serviceRequest->status === 'approved' ? 'info' :
                                ($serviceRequest->status === 'in_progress' ? 'primary' :
                                ($serviceRequest->status === 'completed' ? 'success' : 'danger')))
                            }}">
                                <i class="fas fa-{{ 
                                    $serviceRequest->status === 'pending' ? 'hourglass-start' :
                                    ($serviceRequest->status === 'approved' ? 'check-circle' :
                                    ($serviceRequest->status === 'in_progress' ? 'cog' :
                                    ($serviceRequest->status === 'completed' ? 'flag-checkered' : 'times-circle')))
                                }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="date-badge">{{ $serviceRequest->requested_date->format('M d, Y') }}</span>
                            @if($serviceRequest->completed_date)
                            <br>
                            <small class="completed-date">Completed: {{ $serviceRequest->completed_date->format('M d, Y') }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('staff.service-request.show', $serviceRequest) }}" class="btn-action view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.service-request.edit', $serviceRequest) }}" class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('staff.service-request.destroy', $serviceRequest) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete" onclick="event.preventDefault(); showConfirm('Delete Request', 'Are you sure you want to delete this service request? This action cannot be undone.', this.form)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <div style="padding: 3rem 0;">
                                <div style="font-size: 48px; margin-bottom: 12px; opacity: 0.4;">📋</div>
                                <p style="margin: 0;"><strong>No service requests found</strong></p>
                                <p style="font-size: 12px; margin: 6px 0 0; color: #9ca3af;">Try adjusting your filters or create a new request</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($serviceRequests, 'links'))
        {{ $serviceRequests->links('components.pagination') }}
    @endif
</section>

<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .header-left {
        flex: 1;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 14px;
        margin-top: 5px;
    }

    .btn-add-new {
        background: linear-gradient(135deg, #27ae60, #229954);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
    }

    .btn-add-new:hover {
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        transform: translateY(-2px);
    }

    .alert-with-icon {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .filter-section {
        background: white;
        padding: 18px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        width: 100%;
    }

    .filter-input,
    .filter-select {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .filter-input {
        flex-grow: 1;
        min-width: 250px;
    }

    .filter-select {
        min-width: 150px;
    }

    .btn-filter,
    .btn-clear-filter {
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-filter {
        background-color: #3498db;
        color: white;
    }

    .btn-filter:hover {
        background-color: #2980b9;
        box-shadow: 0 2px 8px rgba(52, 152, 219, 0.2);
    }

    .btn-clear-filter {
        background-color: #95a5a6;
        color: white;
    }

    .btn-clear-filter:hover {
        background-color: #7f8c8d;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table thead {
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
    }

    .data-table th {
        padding: 14px 12px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .data-table tbody tr.status-pending {
        border-left: 3px solid #f39c12;
    }

    .data-table tbody tr.status-approved {
        border-left: 3px solid #3498db;
    }

    .data-table tbody tr.status-in_progress {
        border-left: 3px solid #9b59b6;
    }

    .data-table tbody tr.status-completed {
        border-left: 3px solid #27ae60;
    }

    .data-table tbody tr.status-cancelled {
        border-left: 3px solid #e74c3c;
    }

    .request-id {
        color: #3498db;
        font-size: 13px;
    }

    .customer-name {
        font-weight: 700;
        color: #2c3e50;
    }

    .vehicle-info {
        font-weight: 600;
        color: #34495e;
    }

    .plate-number {
        color: #95a5a6;
        font-family: monospace;
    }

    .service-list {
        color: #34495e;
        line-height: 1.5;
    }

    .mechanic-badge {
        background: #e8f4f8;
        color: #2980b9;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 12px;
    }

    .badge-unassigned {
        color: #e74c3c;
        font-weight: 600;
        font-size: 12px;
    }

    .staff-badge {
        background: #f3f4f6;
        color: #4b5563;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 12px;
    }

    .amount {
        color: #27ae60;
        font-size: 14px;
    }

    .balance-due {
        color: #e74c3c;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-warning { color: #92400e; }
.badge-info { color: #0c4a6e; }
.badge-primary { color: #084298; }
.badge-success { color: #0f5132; }
.badge-danger { color: #842029; }

    .date-badge {
        background: #f0f0f0;
        color: #666;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .completed-date {
        color: #27ae60;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .btn-action.view {
        background: #e8f4f8;
        color: #2980b9;
    }

    .btn-action.view:hover {
        background: #2980b9;
        color: white;
    }

    .btn-action.edit {
        background: #f0f4ff;
        color: #6366f1;
    }

    .btn-action.edit:hover {
        background: #6366f1;
        color: white;
    }

    .btn-action.delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .btn-action.delete:hover {
        background: #dc2626;
        color: white;
    }

    .empty-state {
        padding: 40px;
        font-size: 14px;
    }

    .empty-state i {
        font-size: 32px;
        color: #bdc3c7;
        margin-bottom: 10px;
        display: block;
    }

    .pagination-wrapper {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    @media (max-width: 768px) {
        .content-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-input,
        .filter-select {
            width: 100%;
        }
    }
</style>

<!-- Confirm Modal -->
<div id="confirmModal" class="modal-overlay">
    <div class="confirm-modal">
        <h3 id="confirmTitle">Confirm Action</h3>
        <p id="confirmMessage">Are you sure?</p>
        <div class="confirm-buttons">
            <button class="confirm-btn confirm-btn-primary" onclick="confirmAction()">Delete</button>
            <button class="confirm-btn confirm-btn-secondary" onclick="cancelAction()">Cancel</button>
        </div>
    </div>
</div>

<script>
let pendingForm = null;

// Custom confirm modal
function showConfirm(title, message, form) {
    pendingForm = form;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.add('active');
}

function confirmAction() {
    document.getElementById('confirmModal').classList.remove('active');
    if (pendingForm) {
        pendingForm.submit();
    }
}

function cancelAction() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingForm = null;
}

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) cancelAction();
});

// Escape key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cancelAction();
    }
});

// Copy to clipboard with visual feedback
function copyToClipboard(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const original = el.textContent;
        el.textContent = '✓ Copied!';
        el.classList.add('copied');
        setTimeout(() => {
            el.textContent = original;
            el.classList.remove('copied');
        }, 1500);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}
</script>

@endsection
