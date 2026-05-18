@extends('layouts.admin')

@section('content')
@php $prefix = auth()->user()->role === 'staff' ? 'staff' : 'admin'; @endphp

<style>
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp  { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes scaleIn   { from { opacity: 0; transform: scale(0.95); }     to { opacity: 1; transform: scale(1); } }
@keyframes pulse     { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }

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

.btn-add-new {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    border: none;
    cursor: pointer;
}

.btn-add-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
    color: white;
    text-decoration: none;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
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

.filter-item.search-item {
    flex: 1;
    min-width: 220px;
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
}

.filter-input {
    padding: 0 14px;
    width: 100%;
}

.filter-select {
    padding: 0 12px;
    min-width: 160px;
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
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.filter-input::placeholder { color: #9ca3af; }

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: flex-end;
    padding-bottom: 0;
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
    white-space: nowrap;
    font-family: inherit;
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
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
    white-space: nowrap;
    font-family: inherit;
}

.btn-clear-filter:hover {
    border-color: #dc2626;
    color: #dc2626;
    background: #fef2f2;
    text-decoration: none;
}

/* Active filter indicator */
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

/* ===== TABLE WRAPPER ===== */
.content-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    overflow: hidden;
    animation: scaleIn 0.4s ease-out;
    border: 1px solid #f0f0f0;
}

.table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* ===== TABLE ===== */
.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.data-table thead {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
}

.data-table th {
    padding: 13px 14px;
    text-align: left;
    font-weight: 700;
    color: #374151;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    white-space: nowrap;
}

.data-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #1f2937;
    vertical-align: middle;
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.data-table tbody tr:hover td {
    background: #f9fafb;
}

/* ===== CELL CONTENT ===== */
.customer-name {
    font-weight: 700;
    color: #1f2937;
}

.text-muted {
    color: #9ca3af;
    font-size: 12px;
}

.text-danger {
    color: #dc2626;
    font-weight: 600;
    font-size: 12px;
}

.plate-number {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: #374151;
}

.vehicle-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 12px;
    white-space: nowrap;
}

.vehicle-badge i {
    font-size: 11px;
}

.service-list {
    color: #4b5563;
    line-height: 1.7;
    font-size: 12px;
}

.mechanic-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 700;
    font-size: 12px;
}

.staff-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f3f4f6;
    color: #374151;
    padding: 4px 10px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 12px;
}

.amount {
    color: #059669;
    font-weight: 800;
    font-size: 14px;
}

.balance-due {
    color: #dc2626;
    font-weight: 600;
    font-size: 11px;
}

.completed-date {
    color: #059669;
    font-weight: 600;
    font-size: 11px;
}

/* ===== STATUS BADGES ===== */
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

.badge-pending    { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.badge-approved   { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
.badge-in_progress{ background: #ede9fe; color: #4c1d95; border: 1px solid #ddd6fe; }
.badge-completed  { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.badge-cancelled  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

/* ===== COPY ID ===== */
.copy-id {
    cursor: pointer;
    padding: 3px 7px;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 12px;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    transition: all 0.2s;
    display: inline-block;
}

.copy-id:hover    { background: #dbeafe; }
.copy-id.copied   { background: #d1fae5; color: #065f46; border-color: #a7f3d0; animation: pulse 0.5s ease-out; }

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 6px;
    align-items: center;
}

.action-buttons form { margin: 0; }

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
    color: white;
    text-decoration: none;
}

.btn-action:hover { transform: translateY(-2px); filter: brightness(1.1); color: white; text-decoration: none; }
.btn-action.view   { background: #3b82f6; }
.btn-action.edit   { background: #f59e0b; }
.btn-action.delete { background: #ef4444; }

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}

.empty-state-icon {
    font-size: 44px;
    margin-bottom: 10px;
    opacity: 0.35;
}

/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(3px);
}

.modal-overlay.active { display: flex; }

.confirm-modal {
    background: white;
    padding: 30px;
    border-radius: 14px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    text-align: center;
    animation: scaleIn 0.3s ease-out;
}

.confirm-modal .modal-icon {
    font-size: 36px;
    margin-bottom: 12px;
}

.confirm-modal h3 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.confirm-modal p {
    margin: 0 0 22px;
    color: #6b7280;
    font-size: 14px;
    line-height: 1.6;
}

.confirm-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.confirm-btn {
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
}

.confirm-btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.confirm-btn-danger:hover  { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.3); }

.confirm-btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.confirm-btn-secondary:hover { background: #e5e7eb; }




/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .content-header   { flex-direction: column; align-items: flex-start; gap: 12px; }
    .filter-row       { flex-direction: column; gap: 10px; }
    .filter-item      { width: 100%; }
    .filter-item.search-item { min-width: unset; }
    .filter-select    { min-width: unset; width: 100%; }
    .filter-actions   { width: 100%; }
    .btn-filter,
    .btn-clear-filter { flex: 1; justify-content: center; }
}
</style>

<section id="service-request" class="content-section active">

    {{-- HEADER --}}
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-clipboard-list" style="color:#059669;margin-right:8px;font-size:22px;"></i>
            Service Requests
        </h1>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.service-request.create') }}" class="btn-add-new">
                <i class="fas fa-plus"></i> Add New Request
            </a>
        @endif
    </div>

    {{-- FILTER SECTION --}}
    <div class="filter-section">
        <form method="GET" action="{{ route($prefix . '.service-request') }}">
            <div class="filter-row">

                {{-- Search --}}
                <div class="filter-item search-item">
                    <label class="filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Search</label>
                    <input
                        type="text"
                        name="search"
                        class="filter-input"
                        placeholder="Customer name or email…"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>

                {{-- Status --}}
                <div class="filter-item">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mechanic --}}
                <div class="filter-item">
                    <label class="filter-label">Mechanic</label>
                    <select name="mechanic_id" class="filter-select">
                        <option value="">All Mechanics</option>
                        @foreach($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}" {{ (string) request('mechanic_id') === (string) $mechanic->id ? 'selected' : '' }}>
                                {{ $mechanic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Assigned By --}}
                <div class="filter-item">
                    <label class="filter-label">Assigned By</label>
                    <select name="assigned_by" class="filter-select">
                        <option value="">All Staff</option>
                        @foreach($staff as $staffMember)
                            <option value="{{ $staffMember->id }}" {{ (string) request('assigned_by') === (string) $staffMember->id ? 'selected' : '' }}>
                                {{ $staffMember->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','status','mechanic_id','assigned_by']))
                        <a href="{{ route($prefix . '.service-request') }}" class="btn-clear-filter">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>

            </div>{{-- .filter-row --}}
        </form>

        {{-- Active filter tags --}}
        @if(request()->hasAny(['search','status','mechanic_id','assigned_by']))
            <div class="active-filters">
                @if(request('search'))
                    <span class="filter-tag"><i class="fas fa-search"></i> "{{ request('search') }}"</span>
                @endif
                @if(request('status'))
                    <span class="filter-tag"><i class="fas fa-circle"></i> {{ ucfirst(str_replace('_',' ',request('status'))) }}</span>
                @endif
                @if(request('mechanic_id'))
                    @php $mName = $mechanics->firstWhere('id', request('mechanic_id'))?->name ?? 'Mechanic'; @endphp
                    <span class="filter-tag"><i class="fas fa-wrench"></i> {{ $mName }}</span>
                @endif
                @if(request('assigned_by'))
                    @php $sName = $staff->firstWhere('id', request('assigned_by'))?->name ?? 'Staff'; @endphp
                    <span class="filter-tag"><i class="fas fa-user-tie"></i> {{ $sName }}</span>
                @endif
            </div>
        @endif
    </div>{{-- .filter-section --}}

    {{-- TABLE --}}
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
                    @forelse($serviceRequests as $request)
                    <tr>
                        {{-- Request ID --}}
                        <td>
                            <span class="copy-id"
                                  onclick="copyToClipboard('#{{ $request->id }}', this)"
                                  title="Click to copy">
                                #{{ $request->id }}
                            </span>
                        </td>

                        {{-- Customer --}}
                        <td>
                            <span class="customer-name">{{ $request->customer->name ?? 'N/A' }}</span>
                            @if($request->customer?->email)
                                <br><span class="text-muted">{{ $request->customer->email }}</span>
                            @endif
                        </td>

                        {{-- Vehicle — triple-layer lookup: name → id → raw value --}}
                        <td>
                            @php
                                $vehicleLabel = $categories->firstWhere('name', $request->vehicle_type)?->name
                                    ?? $categories->firstWhere('id', $request->vehicle_type)?->name
                                    ?? ($request->vehicle_type ?? 'N/A');
                            @endphp
                            <span class="vehicle-badge">
                                {{ $vehicleLabel }}
                            </span>
                        </td>

                        {{-- Services --}}
                        <td>
                            @if($request->services->count() > 0)
                                <span class="service-list">
                                    {{ $request->services->pluck('name')->implode(', ') }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Mechanic --}}
                        <td>
                            @if($request->mechanic)
                                <span class="mechanic-badge">
                                    <i class="fas fa-wrench"></i>
                                    {{ $request->mechanic->name }}
                                </span>
                                @if($request->mechanic->specialization)
                                    <br><span class="text-muted">{{ $request->mechanic->specialization }}</span>
                                @endif
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-exclamation-circle"></i> Unassigned
                                </span>
                            @endif
                        </td>

                        {{-- Assigned By --}}
                        <td>
                            @if($request->assignedBy)
                                <span class="staff-badge">
                                    <i class="fas fa-user-tie"></i>
                                    {{ $request->assignedBy->name }}
                                </span>
                                @if($request->assigned_at)
                                    <br><span class="text-muted">{{ \Carbon\Carbon::parse($request->assigned_at)->format('M d, Y') }}</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Total Amount --}}
                        <td>
                            <strong class="amount">₱{{ number_format($request->total_amount ?? 0, 2) }}</strong>
                            @if(($request->remaining_balance ?? 0) > 0)
                                <br>
                                <span class="balance-due">
                                    Balance: ₱{{ number_format($request->remaining_balance, 2) }}
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td>
                            @php
                                $statusKey = str_replace(' ', '_', strtolower($request->status));
                                $statusIcons = [
                                    'pending'     => 'hourglass-start',
                                    'approved'    => 'check-circle',
                                    'in_progress' => 'cog fa-spin',
                                    'completed'   => 'flag-checkered',
                                    'cancelled'   => 'times-circle',
                                ];
                                $icon = $statusIcons[$statusKey] ?? 'circle';
                            @endphp
                            <span class="badge badge-{{ $statusKey }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td>
                            <span style="font-size:13px;">{{ $request->requested_date->format('M d, Y') }}</span>
                            @if($request->completed_date)
                                <br>
                                <span class="completed-date">
                                    <i class="fas fa-check" style="font-size:10px;"></i>
                                    {{ \Carbon\Carbon::parse($request->completed_date)->format('M d, Y') }}
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route($prefix . '.service-request.show', $request) }}"
                                   class="btn-action view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route($prefix . '.service-request.edit', $request) }}"
                                   class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route($prefix . '.service-request.destroy', $request) }}"
                                      method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            title="Delete"
                                            onclick="event.preventDefault(); showConfirm(this.form)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-state-icon">📋</div>
                                <p style="margin:0;font-weight:700;color:#374151;">No service requests found</p>
                                <p style="font-size:12px;margin:6px 0 0;color:#9ca3af;">
                                    Try adjusting your filters or
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.service-request.create') }}" style="color:#059669;">create a new request</a>
                                    @else
                                        contact an administrator
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($serviceRequests->hasPages())
            {{ $serviceRequests->links('components.pagination') }}
        @endif
    </div>{{-- .content-box --}}

</section>

{{-- DELETE CONFIRM MODAL --}}
<div id="confirmModal" class="modal-overlay">
    <div class="confirm-modal">
        <div class="modal-icon">🗑️</div>
        <h3>Delete Service Request?</h3>
        <p>This action cannot be undone. All related data including payments and status history will be removed.</p>
        <div class="confirm-buttons">
            <button class="confirm-btn confirm-btn-danger" onclick="confirmAction()">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
            <button class="confirm-btn confirm-btn-secondary" onclick="cancelAction()">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
let pendingForm = null;

function showConfirm(form) {
    pendingForm = form;
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

// Close on backdrop click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) cancelAction();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cancelAction();
});

// Copy to clipboard
function copyToClipboard(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        const original = el.textContent;
        el.textContent = '✓ Copied!';
        el.classList.add('copied');
        setTimeout(() => {
            el.textContent = original;
            el.classList.remove('copied');
        }, 1500);
    }).catch(() => {
        // Fallback for older browsers
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        const original = el.textContent;
        el.textContent = '✓ Copied!';
        el.classList.add('copied');
        setTimeout(() => {
            el.textContent = original;
            el.classList.remove('copied');
        }, 1500);
    });
}

// Auto-submit filters on select change (optional UX improvement)
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

@endsection
