@extends('layouts.admin')

@section('content')
<div class="show-section">
    <!-- Header -->
    <div class="show-header">
        <a href="{{ route('admin.mechanics') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Mechanics
        </a>
        
        <div class="header-actions">
            <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.mechanics.destroy', $mechanic->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this mechanic?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Main Container -->
    <div class="show-container">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="profile-identifiers">
                    <h1 class="profile-name">{{ $mechanic->name }}</h1>
                    <div class="profile-meta">
                        <span class="badge badge-{{ $mechanic->status == 'active' ? 'success' : 'danger' }}">
                            <i class="fas fa-{{ $mechanic->status == 'active' ? 'check-circle' : 'times-circle' }}"></i>
                            {{ ucfirst($mechanic->status) }}
                        </span>
                        <span class="profile-id">ID: #{{ $mechanic->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Information Grid -->
            <div class="info-grid">
                <div class="info-box">
                    <div class="info-icon email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <label>Email Address</label>
                        <p>{{ $mechanic->email }}</p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon phone">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <label>Phone Number</label>
                        <p>{{ $mechanic->phone ?? 'Not provided' }}</p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon spec">
                        <img src="{{ asset('images/specializations/' . $mechanic->specialization . '.svg') }}" 
                             alt="{{ $mechanic->specialization }}"
                             onerror="this.src='{{ asset('images/specializations/General.svg') }}'">
                    </div>
                    <div class="info-content">
                        <label>Specialization</label>
                        <p>{{ $mechanic->specialization }}</p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon date">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="info-content">
                        <label>Date Added</label>
                        <p>{{ $mechanic->date_added ? $mechanic->date_added->format('F d, Y') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="additional-info">
            @if($mechanic->certificate_path)
            <div class="info-section">
                <h2 class="section-title">Certification</h2>
                <div class="certificate-box">
                    <i class="fas fa-file-pdf"></i>
                    <div class="cert-info">
                        <p class="cert-name">Certificate on File</p>
                        <a href="{{ asset('storage/' . $mechanic->certificate_path) }}" target="_blank" class="cert-link">
                            <i class="fas fa-download"></i> Download Certificate
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Service Requests -->
            <div class="info-section">
                <h2 class="section-title">Service Assignments</h2>
                @if($mechanic->serviceRequests && $mechanic->serviceRequests->count() > 0)
                    <div class="assignment-list">
                        @foreach($mechanic->serviceRequests->take(5) as $request)
                        <div class="assignment-item">
                            <div class="assignment-header">
                                <span class="request-id">Request #{{ $request->id }}</span>
                                <span class="request-status badge badge-{{ $request->status == 'completed' ? 'success' : 'pending' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            <div class="assignment-details">
                                <small>Customer: <strong>{{ $request->customer->name ?? '-' }}</strong></small><br>
                                <small>Vehicle: <strong>{{ $request->vehicle->model ?? '-' }}</strong></small>
                            </div>
                        </div>
                        @endforeach
                        @if($mechanic->serviceRequests->count() > 5)
                            <div class="view-more">
                                <a href="#">View all {{ $mechanic->serviceRequests->count() }} assignments</a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No service assignments yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.show-section {
    padding: 20px;
}

.show-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    gap: 20px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #3b82f6;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.back-link:hover {
    color: #1e40af;
}

.header-actions {
    display: flex;
    gap: 12px;
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

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #991b1b;
}

.show-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.profile-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    padding: 30px;
    grid-column: 1 / -1;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e5e7eb;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    flex-shrink: 0;
}

.profile-identifiers h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #1f2937;
}

.profile-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #ecfdf5;
    color: #065f46;
}

.badge-danger {
    background: #fef2f2;
    color: #991b1b;
}

.profile-id {
    color: #6b7280;
    font-size: 13px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
    transition: all 0.2s;
}

.info-box:hover {
    background: #f3f4f6;
}

.info-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
    color: white;
}

.info-icon.email {
    background: #dbeafe;
    color: #1e40af;
}

.info-icon.phone {
    background: #fce7f3;
    color: #be185d;
}

.info-icon.spec {
    background: #e0e7ff;
    color: #3730a3;
    padding: 5px;
}

.info-icon.spec img {
    width: 100%;
    height: 100%;
}

.info-icon.date {
    background: #fef3c7;
    color: #92400e;
}

.info-content label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6b7280;
    margin-bottom: 4px;
}

.info-content p {
    margin: 0;
    font-size: 15px;
    font-weight: 500;
    color: #1f2937;
}

.additional-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    grid-column: 1 / -1;
}

.info-section {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    padding: 20px;
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.certificate-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 4px solid #f59e0b;
}

.certificate-box i {
    font-size: 30px;
    color: #f59e0b;
}

.cert-info {
    flex: 1;
}

.cert-name {
    margin: 0;
    font-weight: 600;
    color: #374151;
}

.cert-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #3b82f6;
    text-decoration: none;
    font-size: 13px;
    margin-top: 5px;
}

.cert-link:hover {
    color: #1e40af;
}

.assignment-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.assignment-item {
    padding: 12px;
    background: #f9fafb;
    border-left: 4px solid #3b82f6;
    border-radius: 6px;
}

.assignment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.request-id {
    font-weight: 600;
    color: #374151;
    font-size: 13px;
}

.request-status {
    font-size: 11px;
}

.assignment-details {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.5;
}

.badge-pending {
    background: #fef3c7;
    color: #92400e;
}

.view-more {
    text-align: center;
    padding-top: 10px;
    border-top: 1px solid #e5e7eb;
}

.view-more a {
    color: #3b82f6;
    text-decoration: none;
    font-size: 13px;
}

.view-more a:hover {
    text-decoration: underline;
}

.empty-state {
    text-align: center;
    padding: 30px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 32px;
    color: #d1d5db;
    margin-bottom: 10px;
    display: block;
}

@media (max-width: 1024px) {
    .show-container {
        grid-template-columns: 1fr;
    }

    .additional-info {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .show-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
    }

    .btn {
        flex: 1;
        justify-content: center;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection
