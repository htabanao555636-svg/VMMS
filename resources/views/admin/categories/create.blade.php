@extends('layouts.admin')

@section('content')
<section id="category-create" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">Add New Category</h1>
        <a href="{{ route('admin.categories') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    <div class="content-box form-container">
        <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
            @csrf

            <!-- Category Name -->
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-tag"></i> Category Name <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input @error('name') is-invalid @enderror"
                    placeholder="Enter category name"
                    value="{{ old('name') }}"
                    required
                >
                @error('name')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Vehicle Type -->
            <div class="form-group">
                <label for="vehicle_type" class="form-label">
                    <i class="fas fa-car"></i> Vehicle Type <span class="required">*</span>
                </label>
                <div class="vehicle-type-options">
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            name="vehicle_type" 
                            value="3-wheeler" 
                            class="radio-input"
                            {{ old('vehicle_type') === '3-wheeler' ? 'checked' : '' }}
                        >
                        <span class="radio-text">
                            <i class="fas fa-gopuram"></i> 3-Wheeler
                        </span>
                    </label>
                    <label class="radio-label">
                        <input 
                            type="radio" 
                            name="vehicle_type" 
                            value="4-wheeler" 
                            class="radio-input"
                            {{ old('vehicle_type') === '4-wheeler' || !old('vehicle_type') ? 'checked' : '' }}
                        >
                        <span class="radio-text">
                            <i class="fas fa-car"></i> 4-Wheeler
                        </span>
                    </label>
                </div>
                @error('vehicle_type')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description" class="form-label">
                    <i class="fas fa-file-alt"></i> Description
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-textarea @error('description') is-invalid @enderror"
                    placeholder="Enter category description"
                    rows="4"
                >{{ old('description') }}</textarea>
                @error('description')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status" class="form-label">
                    <i class="fas fa-toggle-on"></i> Status <span class="required">*</span>
                </label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Category
                </button>
                <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<style>
    .form-container {
        max-width: 600px;
        margin: 20px auto;
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

    /* Vehicle Type Radio Options */
    .vehicle-type-options {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
    }

    .radio-label:hover {
        border-color: #3498db;
        background-color: #f0f7ff;
    }

    .radio-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        margin: 0;
    }

    .radio-input:checked + .radio-text {
        color: #3498db;
        font-weight: 600;
    }

    .radio-label input:checked {
        accent-color: #3498db;
    }

    .radio-label input:checked ~ .radio-text {
        color: #3498db;
    }

    .radio-input:checked {
        accent-color: #3498db;
    }

    .radio-label:has(input:checked) {
        border-color: #3498db;
        background-color: #f0f7ff;
    }

    .radio-text {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    .radio-text i {
        font-size: 16px;
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
</style>
@endsection
