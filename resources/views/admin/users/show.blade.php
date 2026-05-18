@extends('layouts.admin')

@section('content')
<section id="user-details" class="content-section active">
    <div class="content-header">
        <div class="header-left">
            <a href="{{ route('admin.users') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <h1 class="page-title">
                <i class="fas fa-user"></i> User Details
            </h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if(auth()->id() !== $user->id)
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user permanently?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="content-box">
        <div class="user-details-container">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-avatar-large">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>

            <!-- User Information -->
            <div class="details-grid">
                <div class="details-section">
                    <h3>Personal Information</h3>
                    <div class="detail-item">
                        <label>Full Name</label>
                        <div class="detail-value">{{ $user->name }}</div>
                    </div>
                    <div class="detail-item">
                        <label>Email Address</label>
                        <div class="detail-value">{{ $user->email }}</div>
                    </div>
                    <div class="detail-item">
                        <label>Phone Number</label>
                        <div class="detail-value">{{ $user->phone ?? '—' }}</div>
                    </div>
                    <div class="detail-item">
                        <label>Address</label>
                        <div class="detail-value">{{ $user->address ?? '—' }}</div>
                    </div>
                </div>

                <div class="details-section">
                    <h3>Account Status</h3>
                    <div class="detail-item">
                        <label>Role</label>
                        <div class="detail-value">
                            <span class="role-badge role-{{ $user->role }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : ($user->role === 'staff' ? 'user-tie' : 'user') }}"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Status</label>
                        <div class="detail-value">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Suspended</span>
                            @endif
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Member Since</label>
                        <div class="detail-value">
                            {{ $user->created_at->format('F d, Y') }}
                            <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                        </div>
                    </div>
                    <div class="detail-item">
                        <label>Last Updated</label>
                        <div class="detail-value">
                            {{ $user->updated_at->format('F d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicles -->
            @if($user->vehicles && $user->vehicles->count() > 0)
            <div class="details-section full-width">
                <h3>Vehicles ({{ $user->vehicles->count() }})</h3>
                <div class="vehicles-list">
                    @foreach($user->vehicles as $vehicle)
                    <div class="vehicle-card">
                        <div class="vehicle-info">
                            <h4>{{ $vehicle->name ?? 'Vehicle' }}</h4>
                            <p>{{ $vehicle->plate_number ?? '—' }}</p>
                            <small class="text-muted">{{ $vehicle->category ?? '—' }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Service Requests -->
            @if($user->serviceRequests && $user->serviceRequests->count() > 0)
            <div class="details-section full-width">
                <h3>Service Requests ({{ $user->serviceRequests->count() }})</h3>
                <div class="requests-table">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Services</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->serviceRequests as $request)
                            <tr>
                                <td>#{{ $request->id }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge" style="background-color: #e3f2fd; color: #1e40af;">
                                        {{ ucfirst($request->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    @if($request->services && $request->services->count() > 0)
                                        {{ implode(', ', $request->services->pluck('name')->toArray()) }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f0f0f0;
        border: 1px solid #ddd;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        margin-bottom: 20px;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background: #e0e0e0;
        color: #1f2937;
    }

    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .header-left {
        flex: 1;
    }

    .header-left h1 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 28px;
        margin: 10px 0 0 0;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .user-details-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .profile-card {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        color: white;
    }

    .profile-avatar-large {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
    }

    .profile-info h2 {
        margin: 0 0 5px 0;
        font-size: 24px;
    }

    .profile-info p {
        margin: 0;
        opacity: 0.9;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .details-section {
        background: #f9fafb;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .details-section h3 {
        margin: 0 0 20px 0;
        font-size: 16px;
        color: #1f2937;
    }

    .details-section.full-width {
        grid-column: 1 / -1;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-item label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 14px;
        color: #374151;
    }

    .detail-value small {
        display: block;
        margin-top: 5px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
    }

    .role-badge.role-admin {
        background: #fce7f3;
        color: #be185d;
    }

    .role-badge.role-staff {
        background: #dcfce7;
        color: #166534;
    }

    .role-badge.role-customer {
        background: #dbeafe;
        color: #1e40af;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .bg-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .bg-secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .vehicles-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }

    .vehicle-card {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .vehicle-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .vehicle-info h4 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #1f2937;
    }

    .vehicle-info p {
        margin: 0 0 5px 0;
        font-size: 13px;
        color: #6b7280;
    }

    .requests-table {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead {
        background: #f3f4f6;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table th {
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #1f2937;
        font-size: 12px;
        text-transform: uppercase;
    }

    .data-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
    }

    .text-muted {
        color: #6b7280;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
        border-radius: 6px;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }
</style>
@endsection
