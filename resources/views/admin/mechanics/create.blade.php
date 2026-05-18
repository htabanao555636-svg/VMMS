@extends('layouts.admin')

@section('content')
<div class="form-section">
    <!-- Header -->
    <div class="form-header">
        <div class="header-content">
            <a href="{{ route('admin.mechanics') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Mechanics
            </a>
            <h1 class="form-title">
                <i class="fas fa-user-plus"></i> Add New Mechanic
            </h1>
            <p class="form-subtitle">Fill in the details to create a new mechanic profile</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Validation Errors</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.mechanics.store') }}" method="POST" enctype="multipart/form-data" class="mechanic-form">
            @csrf

            <!-- Personal Information Section -->
            <div class="form-section-box">
                <h2 class="section-heading">Personal Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Enter mechanic's full name"
                               required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="mechanic@example.com"
                               required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}"
                               placeholder="+1 (555) 000-0000"
                               required>
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="form-section-box">
                <h2 class="section-heading">Professional Information</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="specialization" class="form-label">Specialization *</label>
                        <select id="specialization" 
                                name="specialization" 
                                class="form-control @error('specialization') is-invalid @enderror"
                                required>
                            <option value="">-- Select Specialization --</option>
                            <option value="Engine" {{ old('specialization') == 'Engine' ? 'selected' : '' }}>Engine</option>
                            <option value="Brake" {{ old('specialization') == 'Brake' ? 'selected' : '' }}>Brake Systems</option>
                            <option value="Electrical" {{ old('specialization') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                            <option value="Transmission" {{ old('specialization') == 'Transmission' ? 'selected' : '' }}>Transmission</option>
                            <option value="Suspension" {{ old('specialization') == 'Suspension' ? 'selected' : '' }}>Suspension</option>
                            <option value="General" {{ old('specialization') == 'General' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('specialization')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" 
                                name="status" 
                                class="form-control @error('status') is-invalid @enderror"
                                required>
                            <option value="">-- Select Status --</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="certificate_path" class="form-label">Certificate/Qualification</label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   id="certificate_path" 
                                   name="certificate_path" 
                                   class="form-control @error('certificate_path') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Accepted formats: PDF, JPG, PNG (Optional)</small>
                        </div>
                        @error('certificate_path')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.mechanics') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Mechanic
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-section {
    padding: 20px;
}

.form-header {
    margin-bottom: 30px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3b82f6;
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 10px;
    transition: color 0.2s;
}

.back-link:hover {
    color: #1e40af;
}

.form-title {
    font-size: 28px;
    font-weight: 600;
    color: #1f2937;
    margin: 15px 0 0 0;
}

.form-subtitle {
    color: #6b7280;
    margin: 5px 0 0 0;
}

.form-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    padding: 30px;
}

.form-section-box {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e5e7eb;
}

.form-section-box:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-heading {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control.is-invalid {
    border-color: #dc2626;
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.file-upload-wrapper {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.file-upload-wrapper small {
    font-size: 12px;
    color: #6b7280;
}

.error-message {
    color: #dc2626;
    font-size: 12px;
    margin-top: 6px;
}

.alert {
    border-radius: 8px;
    border: none;
    margin-bottom: 20px;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #dc2626;
}

.alert-danger ul {
    padding-left: 20px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #1e40af;
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #d1d5db;
}

@media (max-width: 768px) {
    .form-container {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

@endsection
