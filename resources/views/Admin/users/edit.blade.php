@extends('layouts.admin')

@section('content')
<div class="form-section">
    <!-- Header -->
    <div class="form-header">
        <div class="header-content">
            <a href="{{ route('admin.users') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <h1 class="form-title">
                <i class="fas fa-user-edit"></i> Edit User
            </h1>
            <p class="form-subtitle">Update user information and settings</p>
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

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="user-form">
            @csrf
            @method('PUT')

            <!-- Personal Information Section -->
            <div class="form-section-box">
                <h2 class="section-heading">
                    <i class="fas fa-user-circle"></i> Personal Information
                </h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+63 9XX XXX XXXX">
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Street address, city, province">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Account Settings Section -->
            <div class="form-section-box">
                <h2 class="section-heading">
                    <i class="fas fa-lock-alt"></i> Account Settings
                </h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="role" class="form-label">Role *</label>
                        <select id="role" 
                                name="role" 
                                class="form-control @error('role') is-invalid @enderror"
                                required>
                            <option value="">-- Select a role --</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('role')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <div class="checkbox-wrapper">
                            <label class="checkbox-label">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1" 
                                    {{ $user->is_active ? 'checked' : '' }}
                                    class="checkbox-input"
                                >
                                <span class="checkbox-text">Active Account</span>
                            </label>
                            <small class="checkbox-hint">Uncheck to suspend this user</small>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-header">
                        <i class="fas fa-calendar-alt"></i>
                        <strong>Account Timeline</strong>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <span class="info-label">Member Since:</span>
                            <span class="info-value">{{ $user->created_at->format('F d, Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated:</span>
                            <span class="info-value">{{ $user->updated_at->format('F d, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Theme Variables */
    :root {
        --primary: #1e7a54;
        --primary-light: #2a9d6e;
        --text: #1a2e25;
        --text-muted: #5a7a6a;
        --border: #d4e8dd;
        --background: #f5f9f7;
        --surface: #ffffff;
        --surface-alt: #f0faf5;
        --radius: 10px;
        --shadow: 0 4px 10px rgba(0,0,0,0.05);
        --error: #dc2626;
    }

    .form-section {
        padding: 20px;
    }

    .form-header {
        margin-bottom: 30px;
    }

    .header-content {
        display: flex;
        flex-direction: column;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 15px;
        transition: all 0.2s ease;
        font-weight: 500;
        width: fit-content;
    }

    .back-link:hover {
        color: var(--primary-light);
        gap: 12px;
    }

    .form-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 28px;
        font-weight: 600;
        color: var(--text);
        margin: 15px 0 0 0;
    }

    .form-title i {
        color: var(--primary);
        font-size: 32px;
    }

    .form-subtitle {
        color: var(--text-muted);
        font-size: 14px;
        margin: 8px 0 0 0;
    }

    .form-container {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 30px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: var(--radius);
        margin-bottom: 20px;
        border: 1px solid;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .alert-danger {
        background: #fee2e2;
        border-color: #fecaca;
        color: #991b1b;
    }

    .alert-danger i {
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-danger ul {
        margin: 8px 0 0 0;
        padding-left: 20px;
    }

    .alert-danger li {
        margin: 4px 0;
        font-size: 14px;
    }

    .alert-danger strong {
        font-weight: 600;
    }

    .alert-dismissible {
        position: relative;
        padding-right: 40px;
    }

    .btn-close {
        position: absolute;
        right: 12px;
        top: 12px;
        background: none;
        border: none;
        font-size: 20px;
        color: #991b1b;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .user-form {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .form-section-box {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
    }

    .form-section-box:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-heading {
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-heading i {
        color: var(--primary);
        font-size: 20px;
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
        color: var(--text);
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        font-family: 'Segoe UI', sans-serif;
        transition: all 0.2s ease;
        background: var(--surface);
        color: var(--text);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30, 122, 84, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--error);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
        font-family: 'Segoe UI', sans-serif;
    }

    .error-message {
        display: block;
        color: var(--error);
        font-size: 12px;
        margin-top: 5px;
        font-weight: 500;
    }

    .checkbox-wrapper {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 500;
        color: var(--text);
        margin: 0;
    }

    .checkbox-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary);
        flex-shrink: 0;
    }

    .checkbox-text {
        font-weight: 500;
    }

    .checkbox-hint {
        font-size: 12px;
        color: var(--text-muted);
        margin-left: 30px;
        display: block;
    }

    .info-box {
        background: var(--surface-alt);
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        margin-top: 20px;
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .info-header i {
        color: var(--primary);
        font-size: 16px;
    }

    .info-content {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item {
        display: flex;
        gap: 12px;
        font-size: 13px;
    }

    .info-label {
        font-weight: 600;
        color: var(--text);
        min-width: 120px;
    }

    .info-value {
        color: var(--text-muted);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 30px;
        margin-top: 30px;
        border-top: 1px solid var(--border);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        font-family: 'Segoe UI', sans-serif;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-light);
        box-shadow: 0 4px 12px rgba(30, 122, 84, 0.3);
        transform: translateY(-1px);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: var(--text-muted);
        color: white;
    }

    .btn-secondary:hover {
        background: var(--text);
        box-shadow: 0 4px 12px rgba(26, 46, 37, 0.2);
        transform: translateY(-1px);
    }

    .btn-secondary:active {
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
            gap: 10px;
        }

        .form-actions .btn {
            width: 100%;
        }

        .form-title {
            font-size: 24px;
        }

        .section-heading {
            font-size: 16px;
        }
    }
</style>
@endsection
