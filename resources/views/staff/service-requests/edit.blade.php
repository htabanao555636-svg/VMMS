@extends('layouts.admin')

@section('content')
<section id="service-request-edit" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">Edit Service Request #{{ $serviceRequest->id }}</h1>
        <a href="{{ route('staff.service-request') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="content-box form-container">
        <form action="{{ route('staff.service-request.update', $serviceRequest) }}" method="POST" id="serviceRequestForm">
            @csrf
            @method('PATCH')

            <div class="form-section">
                <h3 class="section-title">Customer Information</h3>
                
                <!-- Customer (Read-only) -->
                <div class="form-group">
                    <label for="customer_id" class="form-label">
                        <i class="fas fa-user"></i> Customer
                    </label>
                    <input type="text" class="form-input" value="{{ $serviceRequest->customer->name ?? 'N/A' }}" readonly>
                    <small class="text-muted">{{ $serviceRequest->customer->email }}</small>
                </div>

                <!-- Vehicle (Read-only) -->
                <div class="form-group">
                    <label for="vehicle_id" class="form-label">
                        <i class="fas fa-car"></i> Vehicle
                    </label>
                    <input type="text" class="form-input" 
                           value="{{ $serviceRequest->vehicle->model ?? 'N/A' }} - {{ $serviceRequest->vehicle->plate_number ?? 'N/A' }}" readonly>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Service Details</h3>
                
                <!-- Services -->
                <div class="form-group">
                    <label for="services" class="form-label">
                        <i class="fas fa-wrench"></i> Services
                    </label>
                    <div class="services-checkbox-group">
                        @foreach($services as $service)
                        <div class="checkbox-item">
                            <input type="checkbox" id="service_{{ $service->id }}" name="services[]" 
                                   value="{{ $service->id }}" class="form-checkbox"
                                   {{ $serviceRequest->services->contains($service->id) ? 'checked' : '' }}>
                            <label for="service_{{ $service->id }}" class="checkbox-label">
                                {{ $service->name }} - ₱{{ number_format($service->price, 2) }}
                                <span class="service-category">({{ $service->category->name }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('services')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Requested Date -->
                <div class="form-group">
                    <label for="requested_date" class="form-label">
                        <i class="fas fa-calendar"></i> Requested Date
                    </label>
                        <input type="text" class="form-input" 
                            value="{{ $serviceRequest->requested_date->format('M d, Y') }}" readonly>
                    @error('requested_date')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Completed Date -->
                <div class="form-group">
                    <label for="completed_date" class="form-label">
                        <i class="fas fa-check-circle"></i> Completed Date
                    </label>
                    <input type="date" id="completed_date" name="completed_date" 
                           class="form-input @error('completed_date') is-invalid @enderror"
                           value="{{ old('completed_date', $serviceRequest->completed_date) }}">
                    <small class="text-muted">Leave empty if not yet completed</small>
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" class="form-textarea @error('notes') is-invalid @enderror"
                              placeholder="Additional notes about the service request" rows="4">{{ old('notes', $serviceRequest->notes) }}</textarea>
                    @error('notes')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Assignment & Status</h3>
                <!-- Mechanic Assignment -->
                <div class="form-group">
                    <label for="mechanic_id" class="form-label">
                        <i class="fas fa-tools"></i> Assign Mechanic
                    </label>
                    <select id="mechanic_id" name="mechanic_id" class="form-select @error('mechanic_id') is-invalid @enderror">
                        <option value="">-- Assign Later --</option>
                        @foreach($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}" {{ old('mechanic_id', $serviceRequest->mechanic_id) == $mechanic->id ? 'selected' : '' }}>
                            {{ $mechanic->name }} - {{ $mechanic->specialization }}
                        </option>
                        @endforeach
                    </select>
                    @error('mechanic_id')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-info-circle"></i> Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required onchange="updateStatusInfo()">
                        <option value="pending" {{ old('status', $serviceRequest->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $serviceRequest->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="in_progress" {{ old('status', $serviceRequest->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $serviceRequest->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $serviceRequest->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <small class="status-info text-muted"></small>
                    @error('status')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Staff Notes -->
                <div class="form-group">
                    <label for="staff_notes" class="form-label">
                        <i class="fas fa-user-tie"></i> Staff Notes
                    </label>
                    <textarea id="staff_notes" name="staff_notes" class="form-textarea @error('staff_notes') is-invalid @enderror"
                              placeholder="Internal notes for staff reference" rows="3">{{ old('staff_notes', $serviceRequest->staff_notes) }}</textarea>
                    @error('staff_notes')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Financial Information</h3>
                
                <div class="financial-grid">
                    <div class="financial-item">
                        <label>Total Amount</label>
                        <p class="financial-value">₱{{ number_format($serviceRequest->total_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="financial-item">
                        <label>Down Payment</label>
                        <p class="financial-value">₱{{ number_format($serviceRequest->downpayment_amount ?? 0, 2) }}</p>
                    </div>
                    <div class="financial-item">
                        <label>Balance</label>
                        <p class="financial-value balance-due">₱{{ number_format($serviceRequest->remaining_balance ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Request
                </button>
                <a href="{{ route('staff.service-request') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .form-container {
        max-width: 800px;
        margin: 20px auto;
    }

    .form-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
        border-left: 4px solid #3498db;
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .required {
        color: #e74c3c;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-input[readonly] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    .form-input.is-invalid,
    .form-textarea.is-invalid,
    .form-select.is-invalid {
        border-color: #e74c3c;
    }

    .error-message {
        display: block;
        color: #e74c3c;
        font-size: 13px;
        margin-top: 5px;
    }

    .text-muted {
        color: #7f8c8d;
        font-size: 13px;
    }

    .services-checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 4px;
        background-color: #f9f9f9;
    }

    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .form-checkbox {
        margin-top: 4px;
        cursor: pointer;
    }

    .checkbox-label {
        cursor: pointer;
        font-size: 14px;
        color: #333;
    }

    .service-category {
        display: block;
        font-size: 12px;
        color: #999;
        margin-top: 2px;
    }

    .status-info {
        display: block;
        margin-top: 8px;
        font-style: italic;
    }

    .financial-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .financial-item {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #2ecc71;
    }

    .financial-item label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #7f8c8d;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .financial-value {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #27ae60;
    }

    .balance-due {
        color: #e74c3c;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #3498db;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-secondary {
        background-color: #95a5a6;
        color: white;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #ecf0f1;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .btn-secondary {
        padding: 10px 20px;
        background-color: #95a5a6;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }
</style>

<script>
    function updateStatusInfo() {
        const status = document.getElementById('status').value;
        const infoEl = document.querySelector('.status-info');
        const statusMessages = {
            'pending': 'Waiting for approval',
            'approved': 'Ready to start work',
            'in_progress': 'Currently being serviced',
            'completed': 'Service finished',
            'cancelled': 'Request cancelled'
        };
        infoEl.textContent = statusMessages[status] || '';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', updateStatusInfo);
</script>
@endsection
