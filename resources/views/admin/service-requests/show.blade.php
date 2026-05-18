@extends('layouts.admin')

@section('content')
@php $prefix = auth()->user()->role === 'staff' ? 'staff' : 'admin'; @endphp

<style>
/* ===== HEADER ===== */
.content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #e5e7eb;
}
.page-title {
    font-size: 26px;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
}
.actions-header { display: flex; gap: 10px; flex-wrap: wrap; }

.btn {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-primary   { background: linear-gradient(135deg,#1a5c42,#2d9b6f); color: white; }
.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-danger    { background: linear-gradient(135deg,#ef4444,#dc2626); color: white; }
.btn-danger:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
.btn-secondary:hover { background: #e5e7eb; }
.btn-small { padding: 7px 14px; font-size: 12px; }

/* ===== STATUS BANNER ===== */
.status-banner {
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    background: linear-gradient(135deg, #1a5c42 0%, #2d9b6f 100%);
    color: white;
}
.status-banner.pending     { background: linear-gradient(135deg,#92400e,#d97706); }
.status-banner.approved    { background: linear-gradient(135deg,#1e40af,#3b82f6); }
.status-banner.in_progress { background: linear-gradient(135deg,#5b21b6,#8b5cf6); }
.status-banner.completed   { background: linear-gradient(135deg,#1a5c42,#2d9b6f); }
.status-banner.cancelled   { background: linear-gradient(135deg,#991b1b,#ef4444); }

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 15px;
    font-weight: 800;
    letter-spacing: 0.3px;
}
.status-dates {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.date-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
}

/* ===== CARDS ===== */
.info-card {
    background: white;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border: 1px solid #f3f4f6;
}
.card-title {
    font-size: 16px;
    font-weight: 800;
    color: #1f2937;
    margin: 0 0 18px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-title i {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg,#1a5c42,#2d9b6f);
    color: white;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

/* ===== INFO GRID ===== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
}
.info-item {
    background: #f9fafb;
    border-radius: 10px;
    padding: 14px 16px;
    border-left: 3px solid #2d9b6f;
}
.info-item label {
    display: block;
    font-size: 10px;
    font-weight: 800;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.info-item p {
    margin: 0;
    color: #1f2937;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}
.info-item p i { color: #2d9b6f; font-size: 13px; }

/* ===== SERVICES TABLE ===== */
.data-table { width: 100%; border-collapse: collapse; }
.data-table thead { background: #f9fafb; }
.data-table th {
    padding: 12px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e5e7eb;
}
.data-table td {
    padding: 13px 14px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #374151;
}
.data-table tbody tr:last-child td { border-bottom: none; }

/* ===== MECHANIC ===== */
.mechanic-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 18px 20px;
}
.mechanic-avatar {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg,#1a5c42,#2d9b6f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 800;
    flex-shrink: 0;
}
.mechanic-info { flex: 1; }
.mechanic-name { font-size: 16px; font-weight: 800; color: #1f2937; margin: 0 0 6px; }
.spec-badge {
    display: inline-block;
    background: #dcfce7;
    color: #166534;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.unassigned-box {
    background: #fef2f2;
    border: 1px dashed #fca5a5;
    border-radius: 10px;
    padding: 18px;
    text-align: center;
    color: #dc2626;
    font-weight: 600;
    font-size: 13px;
}

/* ===== STAFF ASSIGNMENT BOX ===== */
.staff-box {
    margin-top: 14px;
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
    border-left: 4px solid #7c3aed;
    border-radius: 10px;
    padding: 14px 18px;
}
.staff-box h4 {
    margin: 0 0 10px;
    font-size: 13px;
    font-weight: 800;
    color: #5b21b6;
}
.staff-box-grid {
    display: flex;
    gap: 24px;
    font-size: 13px;
    flex-wrap: wrap;
}
.staff-box-grid p { margin: 0; color: #374151; }
.staff-box-grid strong { display: block; font-size: 10px; color: #9ca3af; text-transform: uppercase; margin-bottom: 2px; }

/* ===== FINANCIAL ===== */
.financial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 14px;
}
.financial-box {
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid #e5e7eb;
}
.financial-box label {
    display: block;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #9ca3af;
    margin-bottom: 8px;
}
.financial-value {
    font-size: 26px;
    font-weight: 800;
    color: #2d9b6f;
    margin: 0;
}
.financial-value.balance-due { color: #dc2626; }
.financial-value.zero { color: #9ca3af; }

/* ===== TIMELINE ===== */
.timeline { display: flex; flex-direction: column; gap: 14px; }
.timeline-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: #f9fafb;
    border-radius: 10px;
    border: 1px solid #f3f4f6;
}
.timeline-marker {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg,#1a5c42,#2d9b6f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.timeline-marker.completed { background: linear-gradient(135deg,#1a5c42,#2d9b6f); }
.timeline-marker.working   { background: linear-gradient(135deg,#92400e,#d97706); }
.timeline-marker.assigned  { background: linear-gradient(135deg,#1e40af,#3b82f6); }
.timeline-marker.cancelled { background: linear-gradient(135deg,#991b1b,#ef4444); }

.timeline-content h4 { margin: 0 0 6px; font-size: 15px; color: #1f2937; font-weight: 700; }
.timeline-status, .timeline-date, .timeline-user {
    margin: 4px 0; font-size: 12px; color: #6b7280;
}
.timeline-dates {
    display: flex; flex-wrap: wrap; gap: 12px;
    margin: 8px 0; font-size: 11px; color: #9ca3af;
}
.timeline-dates span { display: flex; align-items: center; gap: 4px; }
.timeline-notes {
    margin: 8px 0 0;
    padding: 8px 12px;
    background: white;
    border-left: 3px solid #d97706;
    border-radius: 6px;
    font-size: 12px;
    color: #374151;
}

/* ===== BADGE ===== */
.badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
}
.badge.completed   { color: #166534; }
.badge.approved    { color: #1e40af; }
.badge.in_progress { color: #5b21b6; }
.badge.pending     { color: #92400e; }
.badge.cancelled   { color: #991b1b; }
.badge.assigned    { color: #1e40af; }
.badge.working     { color: #92400e; }

/* ===== NOTES ===== */
.notes-box {
    background: #f9fafb;
    border-left: 4px solid #2d9b6f;
    border-radius: 8px;
    padding: 16px;
    font-size: 14px;
    color: #374151;
    line-height: 1.7;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .status-banner { flex-direction: column; align-items: flex-start; }
    .mechanic-card { flex-direction: column; align-items: flex-start; }
    .actions-header { flex-wrap: wrap; }
}
</style>

<section class="content-section active">

    <!-- Header -->
    <div class="content-header">
        <h1 class="page-title">
            Service Request <span style="color:#2d9b6f;">#{{ $serviceRequest->id }}</span>
        </h1>
        <div class="actions-header">
            <a href="{{ route($prefix . '.service-request.edit', $serviceRequest) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route($prefix . '.service-request.destroy', $serviceRequest) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Delete this service request?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <a href="{{ route($prefix . '.service-request') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="status-banner {{ strtolower($serviceRequest->status) }}">
        <div class="status-pill">
            <i class="fas fa-{{
                $serviceRequest->status === 'pending'     ? 'hourglass-start' :
                ($serviceRequest->status === 'approved'   ? 'check-circle' :
                ($serviceRequest->status === 'in_progress'? 'cog' :
                ($serviceRequest->status === 'completed'  ? 'flag-checkered' : 'times-circle')))
            }}"></i>
            {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
        </div>
        <div class="status-dates">
            <div class="date-chip">
                <i class="fas fa-calendar-alt"></i>
                Requested: {{ \Carbon\Carbon::parse($serviceRequest->requested_date)->format('M d, Y') }}
            </div>
            @if($serviceRequest->completed_date)
            <div class="date-chip">
                <i class="fas fa-flag-checkered"></i>
                Completed: {{ \Carbon\Carbon::parse($serviceRequest->completed_date)->format('M d, Y') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-user"></i> Customer Information
        </h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Customer Name</label>
                <p><i class="fas fa-user"></i> {{ $serviceRequest->customer->name ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Email</label>
                <p><i class="fas fa-envelope"></i> {{ $serviceRequest->customer->email ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <p><i class="fas fa-phone"></i> {{ $serviceRequest->customer->phone ?? 'N/A' }}</p>
            </div>
            <div class="info-item">
                <label>Vehicle Type</label>
                <p>
                    <i class="fas fa-car"></i>
                    {{ $serviceRequest->vehicle_type ?? 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Services Requested -->
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-tools"></i> Services Requested
        </h2>
        @if($serviceRequest->services->count() > 0)
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Category</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceRequest->services as $service)
                    <tr>
                        <td><strong>{{ $service->name }}</strong></td>
                        <td>{{ $service->category->name ?? '—' }}</td>
                        <td style="color:#2d9b6f; font-weight:700;">
                            ₱{{ number_format($service->price, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p style="color:#9ca3af; font-size:13px;">No services selected.</p>
        @endif
    </div>

    <!-- Mechanic Assignment -->
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-wrench"></i> Mechanic Assignment
        </h2>
        @if($serviceRequest->mechanic)
        <div class="mechanic-card">
            <div class="mechanic-avatar">
                {{ strtoupper(substr($serviceRequest->mechanic->name, 0, 1)) }}
            </div>
            <div class="mechanic-info">
                <p class="mechanic-name">{{ $serviceRequest->mechanic->name }}</p>
                <span class="spec-badge">{{ $serviceRequest->mechanic->specialization }}</span>
            </div>
            <a href="{{ route($prefix . '.mechanics.show', $serviceRequest->mechanic) }}"
                class="btn btn-secondary btn-small">
                <i class="fas fa-user-check"></i> View Profile
            </a>
        </div>

        @if($serviceRequest->assignedBy)
        <div class="staff-box">
            <h4><i class="fas fa-user-tie"></i> Assigned by Staff</h4>
            <div class="staff-box-grid">
                <p>
                    <strong>Staff Member</strong>
                    {{ $serviceRequest->assignedBy->name }}
                </p>
                @if($serviceRequest->assigned_at)
                <p>
                    <strong>Assigned On</strong>
                    {{ \Carbon\Carbon::parse($serviceRequest->assigned_at)->format('M d, Y h:i A') }}
                </p>
                @endif
                @if($serviceRequest->staff_notes)
                <p>
                    <strong>Notes</strong>
                    {{ $serviceRequest->staff_notes }}
                </p>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="unassigned-box">
            <i class="fas fa-exclamation-triangle"></i>
            No mechanic assigned yet
        </div>
        @endif
    </div>

    <!-- Financial Summary -->
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-peso-sign"></i> Financial Summary
        </h2>
        <div class="financial-grid">
            <div class="financial-box" style="background:#f0fdf4; border-color:#bbf7d0;">
                <label>Total Amount</label>
                <p class="financial-value">₱{{ number_format($serviceRequest->total_amount ?? 0, 2) }}</p>
            </div>
            <div class="financial-box" style="background:#fefce8; border-color:#fde68a;">
                <label>Payment</label>
                <p class="financial-value" style="color:#d97706;">
                    ₱{{ number_format($serviceRequest->downpayment_amount ?? 0, 2) }}
                </p>
            </div>
            <div class="financial-box" style="background:#fef2f2; border-color:#fecaca;">
                <label>Remaining Balance</label>
                <p class="financial-value {{ ($serviceRequest->remaining_balance ?? 0) > 0 ? 'balance-due' : 'zero' }}">
                    ₱{{ number_format($serviceRequest->remaining_balance ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Assignment History -->
    @if($serviceRequest->mechanicAssignments->count() > 0)
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-history"></i> Assignment History
        </h2>
        <div class="timeline">
            @foreach($serviceRequest->mechanicAssignments()->orderBy('created_at','desc')->get() as $assignment)
            <div class="timeline-item">
                <div class="timeline-marker {{ strtolower($assignment->status) }}">
                    <i class="fas fa-{{
                        $assignment->status === 'assigned'  ? 'user-check' :
                        ($assignment->status === 'working'  ? 'wrench' :
                        ($assignment->status === 'completed'? 'check' : 'times'))
                    }}"></i>
                </div>
                <div class="timeline-content">
                    <h4>{{ $assignment->mechanic->name }}</h4>
                    <p class="timeline-status">
                        Status: <span class="badge {{ strtolower($assignment->status) }}">
                            {{ ucfirst($assignment->status) }}
                        </span>
                    </p>
                    <div class="timeline-dates">
                        @if($assignment->assigned_at)
                        <span><i class="fas fa-calendar"></i>
                            Assigned: {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('M d, Y h:i A') }}
                        </span>
                        @endif
                        @if($assignment->started_at)
                        <span><i class="fas fa-play"></i>
                            Started: {{ \Carbon\Carbon::parse($assignment->started_at)->format('M d, Y h:i A') }}
                        </span>
                        @endif
                        @if($assignment->completed_at)
                        <span><i class="fas fa-check"></i>
                            Completed: {{ \Carbon\Carbon::parse($assignment->completed_at)->format('M d, Y h:i A') }}
                        </span>
                        @endif
                    </div>
                    @if($assignment->notes)
                    <p class="timeline-notes"><strong>Notes:</strong> {{ $assignment->notes }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Status History -->
    @if($serviceRequest->statusHistory->count() > 0)
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-clock"></i> Status History
        </h2>
        <div class="timeline">
            @foreach($serviceRequest->statusHistory()->orderBy('created_at','desc')->get() as $history)
            <div class="timeline-item">
                <div class="timeline-marker">
                    <i class="fas fa-history"></i>
                </div>
                <div class="timeline-content">
                    <p class="timeline-status">
                        Changed to:
                        <span class="badge {{ strtolower($history->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                        </span>
                    </p>
                    <p class="timeline-date">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($history->created_at)->format('M d, Y h:i A') }}
                    </p>
                    @if($history->changedBy)
                    <p class="timeline-user">
                        By: <strong>{{ $history->changedBy->name }}</strong>
                    </p>
                    @endif
                    @if($history->notes)
                    <p class="timeline-notes"><strong>Notes:</strong> {{ $history->notes }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Notes -->
    @if($serviceRequest->notes)
    <div class="info-card">
        <h2 class="card-title">
            <i class="fas fa-sticky-note"></i> Notes
        </h2>
        <div class="notes-box">{{ $serviceRequest->notes }}</div>
    </div>
    @endif

</section>
@endsection
