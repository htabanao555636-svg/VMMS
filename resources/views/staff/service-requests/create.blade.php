@extends('layouts.admin')

@section('content')
<section id="service-request-create" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">Create Service Request</h1>
        <a href="{{ route('staff.service-request') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="content-box form-container">
        <form action="{{ route('staff.service-request.store') }}" method="POST" id="serviceRequestForm">
            @csrf

            <div class="form-section">
                <h3 class="section-title">Customer Information</h3>
                
                <!-- Customer -->
                <div class="form-group">
                    <label for="customer_id" class="form-label">
                        <i class="fas fa-user"></i> Customer <span class="required">*</span>
                    </label>
                    <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Vehicle -->
                <div class="form-group">
                    <label for="vehicle_id" class="form-label">
                        <i class="fas fa-car"></i> Vehicle <span class="required">*</span>
                    </label>
                    <input type="text" name="vehicle_id" id="vehicle_id" class="form-input @error('vehicle_id') is-invalid @enderror" 
                           placeholder="Vehicle info will appear here" readonly>
                    @error('vehicle_id')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Service Details</h3>
                
                <!-- Services -->
                <div class="form-group">
                    <label for="services" class="form-label">
                        <i class="fas fa-wrench"></i> Services <span class="required">*</span>
                    </label>
                    <div class="services-checkbox-group">
                        @foreach($services as $service)
                        <div class="checkbox-item">
                            <input type="checkbox" id="service_{{ $service->id }}" name="services[]" 
                                   value="{{ $service->id }}" class="form-checkbox"
                                   {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
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
                        <i class="fas fa-calendar"></i> Requested Date <span class="required">*</span>
                    </label>
                    <input type="date" id="requested_date" name="requested_date" 
                           class="form-input @error('requested_date') is-invalid @enderror"
                           value="{{ old('requested_date') }}" required>
                    @error('requested_date')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="form-group">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note"></i> Notes
                    </label>
                    <textarea id="notes" name="notes" class="form-textarea @error('notes') is-invalid @enderror"
                              placeholder="Additional notes about the service request" rows="4">{{ old('notes') }}</textarea>
                    @error('notes')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Assignment Options</h3>
                
                <!-- Mechanic Assignment -->
                <div class="form-group">
                    <label for="mechanic_id" class="form-label">
                        <i class="fas fa-tools"></i> Assign Mechanic
                    </label>
                    <select id="mechanic_id" name="mechanic_id" class="form-select @error('mechanic_id') is-invalid @enderror">
                        <option value="">-- Assign Later --</option>
                        @foreach($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}" {{ old('mechanic_id') == $mechanic->id ? 'selected' : '' }}>
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
                        <i class="fas fa-info-circle"></i> Initial Status <span class="required">*</span>
                    </label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : 'selected' }}>Pending</option>
                        <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Staff Notes -->
                <div class="form-group">
                    <label for="staff_notes" class="form-label">
                        <i class="fas fa-sticky-note"></i> Staff Notes
                    </label>
                    <textarea 
                        id="staff_notes" 
                        name="staff_notes" 
                        class="form-textarea @error('staff_notes') is-invalid @enderror"
                        placeholder="Add internal notes for staff reference..."
                        rows="3"
                    >{{ old('staff_notes') }}</textarea>
                    @error('staff_notes')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Request
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
        font-size: 14px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-input.is-invalid,
    .form-select.is-invalid,
    .form-textarea.is-invalid {
        border-color: #e74c3c;
    }

    .services-checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
        margin-top: 10px;
    }

    .checkbox-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .form-checkbox {
        margin-top: 4px;
        cursor: pointer;
        accent-color: #3498db;
    }

    .checkbox-label {
        flex: 1;
        cursor: pointer;
        font-weight: 500;
        color: #333;
    }

    .service-category {
        display: block;
        font-size: 12px;
        color: #7f8c8d;
        font-weight: normal;
        margin-top: 4px;
    }

    .required {
        color: #e74c3c;
        font-weight: bold;
    }

    .error-message {
        display: block;
        color: #e74c3c;
        font-size: 12px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
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

@endsection
