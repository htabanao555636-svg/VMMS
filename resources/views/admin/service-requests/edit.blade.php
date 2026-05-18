@extends('layouts.admin')

@section('content')
@php $prefix = auth()->user()->role === 'staff' ? 'staff' : 'admin'; @endphp
<section id="service-request-edit" class="content-section active">

    {{-- Error flash --}}
    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="content-header">
        <h1 class="page-title">Edit Service Request #{{ $serviceRequest->id }}</h1>
        <a href="{{ route($prefix . '.service-request') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="content-box form-container">
        <form action="{{ route($prefix . '.service-request.update', $serviceRequest) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ========== CUSTOMER INFORMATION (read-only) ========== --}}
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user" style="color:#2d9b6f;margin-right:6px;"></i>
                    Customer Information
                    <span class="readonly-label">Read Only</span>
                </h3>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->customer->name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->customer->email ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Vehicle Name</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->vehicle_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vehicle Model</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->vehicle_model ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Vehicle Registration</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->vehicle_registration ?? 'N/A' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Vehicle Type</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->vehicle_type ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>

            {{-- ========== SERVICE DETAILS (read-only) ========== --}}
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-wrench" style="color:#2d9b6f;margin-right:6px;"></i>
                    Service Details
                    <span class="readonly-label">Read Only</span>
                </h3>

                <div class="form-group">
                    <label class="form-label">Services</label>
                    <div class="services-display">
                        @forelse($serviceRequest->services as $service)
                            <span class="service-chip">
                                <i class="fas fa-check-circle"></i>
                                {{ $service->name }}
                                <span class="service-price">₱{{ number_format($service->price, 2) }}</span>
                            </span>
                        @empty
                            <span style="color:#9ca3af;">No services selected</span>
                        @endforelse
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Requested Date</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ $serviceRequest->requested_date->format('M d, Y') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Request Type</label>
                        <input type="text" class="form-input readonly-field"
                            value="{{ ucfirst($serviceRequest->request_type ?? 'N/A') }}" readonly>
                    </div>
                </div>

                <div class="financial-grid">
                    <div class="financial-item">
                        <label>Total Amount</label>
                        <p class="financial-value">₱{{ number_format($serviceRequest->total_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="financial-item">
                        <label>Payment</label>
                        <p class="financial-value">₱{{ number_format($serviceRequest->downpayment_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="financial-item">
                        <label>Balance</label>
                        <p class="financial-value balance-due">₱{{ number_format($serviceRequest->remaining_balance ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- ========== ASSIGNMENT & STATUS (editable) ========== --}}
            <div class="form-section editable-section">
                <h3 class="section-title">
                    <i class="fas fa-edit" style="color:#2d9b6f;margin-right:6px;"></i>
                    Assignment & Status
                    <span class="editable-label">Editable</span>
                </h3>

                <div class="form-group">
                    <label for="mechanic_id" class="form-label">
                        <i class="fas fa-tools"></i> Assign Mechanic
                    </label>
                    <select id="mechanic_id" name="mechanic_id"
                        class="form-select @error('mechanic_id') is-invalid @enderror">
                        <option value="">-- Assign Later --</option>
                        @foreach($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}"
                            {{ old('mechanic_id', $serviceRequest->mechanic_id) == $mechanic->id ? 'selected' : '' }}>
                            {{ $mechanic->name }} — {{ $mechanic->specialization }}
                        </option>
                        @endforeach
                    </select>
                    @error('mechanic_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-info-circle"></i> Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required onchange="updateStatusInfo()">
                        <option value="pending"     {{ old('status', $serviceRequest->status) === 'pending'     ? 'selected' : '' }}>Pending</option>
                        <option value="approved"    {{ old('status', $serviceRequest->status) === 'approved'    ? 'selected' : '' }}>Approved</option>
                        <option value="in_progress" {{ old('status', $serviceRequest->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ old('status', $serviceRequest->status) === 'completed'   ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"   {{ old('status', $serviceRequest->status) === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <small class="status-info text-muted"></small>
                    @error('status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Completed date: read-only display, only shown when already completed --}}
                @if($serviceRequest->completed_date)
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-check-circle"></i> Completed Date
                    </label>
                    <input type="text" class="form-input readonly-field"
                        value="{{ \Carbon\Carbon::parse($serviceRequest->completed_date)->format('M d, Y') }}"
                        readonly>
                    <small class="text-muted">Automatically set when status is marked as Completed</small>
                </div>
                @endif

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Request
                </button>
                <a href="{{ route($prefix . '.service-request') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .form-container { max-width: 860px; margin: 20px auto; }

    .form-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        border-left: 4px solid #d1d5db;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .editable-section {
        border-left-color: #2d9b6f;
        box-shadow: 0 2px 12px rgba(45,155,111,0.1);
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .readonly-label {
        margin-left: auto;
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        background: #f3f4f6;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .editable-label {
        margin-left: auto;
        font-size: 11px;
        font-weight: 700;
        color: #2d9b6f;
        background: #dcfce7;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 4px;
    }

    .form-group { margin-bottom: 16px; }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #374151;
        font-size: 13px;
    }

    .required { color: #e74c3c; }

    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
        color: #1f2937;
        box-sizing: border-box;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none;
        border-color: #2d9b6f;
        box-shadow: 0 0 0 3px rgba(45,155,111,0.1);
    }

    /* Read-only fields */
    .readonly-field {
        background: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
        border-color: #f3f4f6;
    }

    .form-input.is-invalid,
    .form-select.is-invalid { border-color: #e74c3c; }

    .error-message {
        display: block;
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    }

    .text-muted { color: #9ca3af; font-size: 12px; margin-top: 5px; display: block; }

    /* Services display chips */
    .services-display {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 14px;
        border: 1px solid #f3f4f6;
        border-radius: 8px;
        background: #f9fafb;
        min-height: 50px;
    }

    .service-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        color: #166534;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .service-chip i { font-size: 11px; }

    .service-price {
        font-size: 12px;
        color: #15803d;
        font-weight: 700;
        margin-left: 4px;
    }

    /* Financial grid */
    .financial-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 16px;
    }

    .financial-item {
        background: #f9fafb;
        padding: 14px;
        border-radius: 8px;
        border-left: 3px solid #2d9b6f;
    }

    .financial-item label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .financial-value {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #2d9b6f;
    }

    .balance-due { color: #dc2626 !important; }

    /* Status info */
    .status-info {
        display: block;
        margin-top: 6px;
        font-style: italic;
        font-size: 12px;
    }

    /* Form actions */
    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        padding-top: 20px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #1a5c42, #2d9b6f);
        color: white;
        box-shadow: 0 3px 8px rgba(45,155,111,0.3);
    }

    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary:hover { background: #e5e7eb; }

    #billing-alert {
        margin-top: 10px;
        padding: 12px 16px;
        background: #fff8e1;
        border: 1px solid #f9a825;
        border-radius: 8px;
        font-size: 13px;
        color: #5d4037;
    }

    @media (max-width: 768px) {
        .form-row-2 { grid-template-columns: 1fr; }
        .financial-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn { width: 100%; justify-content: center; }
    }
</style>

<script>
function updateStatusInfo() {
    const status = document.getElementById('status').value;
    const infoEl = document.querySelector('.status-info');
    const balance = {{ $serviceRequest->remaining_balance ?? 0 }};
    const paymentStatus = "{{ $serviceRequest->payment_status }}";

    const messages = {
        'pending':     'Waiting for approval',
        'approved':    'Ready to start work',
        'in_progress': 'Currently being serviced',
        'completed':   'Service finished — completed date will be set automatically',
        'cancelled':   'Request cancelled'
    };

    infoEl.textContent = messages[status] || '';

    const existing = document.getElementById('billing-alert');
    if (existing) existing.remove();

    if (status === 'completed' && paymentStatus === 'downpayment_verified' && balance > 0) {
        const alert = document.createElement('div');
        alert.id = 'billing-alert';
        alert.innerHTML = '<strong>⚠ Billing notice:</strong> Marking as <em>completed</em> will make the remaining balance of <strong>₱{{ number_format($serviceRequest->remaining_balance ?? 0, 2) }}</strong> visible to the customer for payment.';
        document.getElementById('status').closest('.form-group').appendChild(alert);
    }
}

document.addEventListener('DOMContentLoaded', updateStatusInfo);
</script>
@endsection