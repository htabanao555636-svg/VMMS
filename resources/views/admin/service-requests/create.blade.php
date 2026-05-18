@extends('layouts.admin')

@section('content')
@php $prefix = auth()->user()->role === 'staff' ? 'staff' : 'admin'; @endphp
<section id="service-request-create" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">Create Service Request</h1>
        <a href="{{ route($prefix . '.service-request') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Requests
        </a>
    </div>

    <div class="content-box form-container">
        <form action="{{ route($prefix . '.service-request.store') }}" method="POST" id="serviceRequestForm">
            @csrf

            <div class="form-section">
                <h3 class="section-title">Customer Information</h3>

                <!-- Customer -->
                <div class="form-group">
                    <label for="customer_id" class="form-label">
                        <i class="fas fa-user"></i> Customer <span class="required">*</span>
                    </label>
                    <select id="customer_id" name="customer_id"
                        class="form-select @error('customer_id') is-invalid @enderror" required>
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Vehicle Type -->
                <div class="form-group">
                    <label for="vehicle_type" class="form-label">
                        <i class="fas fa-car"></i> Vehicle Type <span class="required">*</span>
                    </label>
                    <select id="vehicle_type" name="vehicle_type"
                        class="form-select @error('vehicle_type') is-invalid @enderror" required>
                        <option value="">-- Select Vehicle Type --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->name }}"
                            {{ old('vehicle_type') == $category->name ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_type')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Service Details</h3>

                <!-- Services -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-wrench"></i> Services <span class="required">*</span>
                    </label>
                    <div class="services-checkbox-group">
                        @foreach($services as $service)
                        <div class="checkbox-item">
                            <input type="checkbox" id="service_{{ $service->id }}" name="services[]"
                                value="{{ $service->id }}" class="form-checkbox"
                                {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>
                            <label for="service_{{ $service->id }}" class="checkbox-label">
                                {{ $service->name }} — ₱{{ number_format($service->price, 2) }}
                                <span class="service-category">
                                    ({{ $service->wheelerCategory->name ?? 'General' }})
                                </span>
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
                    <textarea id="notes" name="notes"
                        class="form-textarea @error('notes') is-invalid @enderror"
                        placeholder="Additional notes about the service request"
                        rows="4">{{ old('notes') }}</textarea>
                    @error('notes')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Assignment Options</h3>

                <!-- Mechanic -->
                <div class="form-group">
                    <label for="mechanic_id" class="form-label">
                        <i class="fas fa-tools"></i> Assign Mechanic
                    </label>
                    <select id="mechanic_id" name="mechanic_id"
                        class="form-select @error('mechanic_id') is-invalid @enderror">
                        <option value="">-- Assign Later --</option>
                        @foreach($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}"
                            {{ old('mechanic_id') == $mechanic->id ? 'selected' : '' }}>
                            {{ $mechanic->name }} — {{ $mechanic->specialization }}
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
                    <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status','pending') === 'pending' ? 'selected' : '' }}>Pending</option>
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
                    <textarea id="staff_notes" name="staff_notes"
                        class="form-textarea @error('staff_notes') is-invalid @enderror"
                        placeholder="Add internal notes for staff reference..."
                        rows="3">{{ old('staff_notes') }}</textarea>
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
                <a href="{{ route($prefix . '.service-request') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .form-container { max-width: 800px; margin: 20px auto; }
    .form-section { background: white; border-radius: 8px; padding: 25px; margin-bottom: 20px; border-left: 4px solid #2d9b6f; }
    .section-title { font-size: 16px; font-weight: 700; color: #333; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
    .required { color: #e74c3c; }
    .form-input, .form-textarea, .form-select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: inherit; font-size: 14px; transition: border-color 0.3s; }
    .form-input:focus, .form-textarea:focus, .form-select:focus { outline: none; border-color: #2d9b6f; box-shadow: 0 0 0 3px rgba(45,155,111,0.1); }
    .form-input.is-invalid, .form-textarea.is-invalid, .form-select.is-invalid { border-color: #e74c3c; }
    .error-message { display: block; color: #e74c3c; font-size: 13px; margin-top: 5px; }
    .services-checkbox-group { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; background-color: #f9f9f9; }
    .checkbox-item { display: flex; align-items: flex-start; gap: 10px; }
    .form-checkbox { margin-top: 4px; cursor: pointer; accent-color: #2d9b6f; }
    .checkbox-label { cursor: pointer; font-size: 14px; color: #333; }
    .service-category { display: block; font-size: 12px; color: #999; margin-top: 2px; }
    .form-actions { display: flex; gap: 10px; margin-top: 30px; }
    .btn { padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary { background: linear-gradient(135deg,#1a5c42,#2d9b6f); color: white; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-secondary { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; text-decoration: none; }
    .btn-secondary:hover { background-color: #e5e7eb; }
</style>
@endsection
