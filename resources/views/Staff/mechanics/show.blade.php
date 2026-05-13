@extends('layouts.admin')

@section('content')
<div class="mechanic-show-section">
    <!-- Back Navigation -->
    <div class="back-navigation">
        <a href="{{ route('staff.mechanics') }}" class="back-link-enhanced">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Mechanics</span>
        </a>
    </div>

    <!-- Main Container -->
    <div class="show-container-enhanced">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-background"></div>
            <div class="profile-content">
                <div class="profile-avatar-large">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="profile-header-info">
                    <h1 class="profile-name">{{ $mechanic->name }}</h1>
                    <div class="profile-badges">
                        <span class="badge-status badge-{{ $mechanic->status == 'active' ? 'success' : 'danger' }}">
                            <i class="fas fa-{{ $mechanic->status == 'active' ? 'check-circle' : 'times-circle' }}"></i>
                            {{ ucfirst($mechanic->status) }}
                        </span>
                        <span class="badge-id">ID: #{{ $mechanic->id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Information Grid -->
                <div class="info-grid-enhanced">
                    <h2 class="section-heading">Professional Information</h2>
                    
                    <div class="info-cards">
                        <div class="info-card">
                            <div class="info-card-icon email-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-card-content">
                                <label class="info-label">Email Address</label>
                                <p class="info-value">{{ $mechanic->email }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon phone-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-card-content">
                                <label class="info-label">Phone Number</label>
                                <p class="info-value">{{ $mechanic->phone ?? 'Not provided' }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon spec-icon">
                                <img src="{{ asset('images/specializations/' . $mechanic->specialization . '.svg') }}" 
                                     alt="{{ $mechanic->specialization }}"
                                     onerror="this.src='{{ asset('images/specializations/General.svg') }}'">
                            </div>
                            <div class="info-card-content">
                                <label class="info-label">Specialization</label>
                                <p class="info-value">{{ $mechanic->specialization }}</p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon date-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <label class="info-label">Date Added</label>
                                <p class="info-value">{{ $mechanic->date_added ? $mechanic->date_added->format('F d, Y') : 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Additional Sections -->
                @if($mechanic->certificate_path)
                <div class="info-section-enhanced">
                    <div class="section-header-small">
                        <i class="fas fa-certificate"></i>
                        <h3>Certification</h3>
                    </div>
                    <div class="certificate-box-enhanced">
                        <div class="certificate-content">
                            <i class="fas fa-file-pdf"></i>
                            <div class="cert-details">
                                <p class="cert-name">Certificate on File</p>
                                <small class="cert-desc">Professional certification document</small>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $mechanic->certificate_path) }}" target="_blank" class="btn-download">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                @endif

                @if($mechanic->notes)
                <div class="info-section-enhanced">
                    <div class="section-header-small">
                        <i class="fas fa-sticky-note"></i>
                        <h3>Notes</h3>
                    </div>
                    <div class="notes-box-enhanced">
                        <p>{{ $mechanic->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Status Summary -->
                <div class="info-section-enhanced">
                    <div class="section-header-small">
                        <i class="fas fa-info-circle"></i>
                        <h3>Status Summary</h3>
                    </div>
                    <div class="status-summary">
                        <div class="summary-item">
                            <span class="summary-label">Current Status</span>
                            <span class="summary-value status-{{ $mechanic->status }}">
                                {{ ucfirst($mechanic->status) }}
                            </span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Member Since</span>
                            <span class="summary-value">{{ $mechanic->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.mechanic-show-section {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.back-navigation {
    margin-bottom: 25px;
}

.back-link-enhanced {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: white;
    border-radius: 6px;
    color: #2d6b47;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.back-link-enhanced:hover {
    background: #2d6b47;
    color: white;
    border-color: #2d6b47;
    transform: translateX(-4px);
}

.show-container-enhanced {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* Profile Header Card */
.profile-header-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    position: relative;
}

.profile-background {
    height: 120px;
    background: linear-gradient(135deg, #1a472a 0%, #2d6b47 100%);
}

.profile-content {
    padding: 0 30px 30px 30px;
    display: flex;
    align-items: flex-end;
    gap: 25px;
    margin-top: -50px;
}

.profile-avatar-large {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #2d6b47, #1a472a);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
    box-shadow: 0 4px 12px rgba(45, 107, 71, 0.2);
    border: 4px solid white;
    flex-shrink: 0;
}

.profile-header-info {
    flex: 1;
    padding-bottom: 8px;
}

.profile-name {
    margin: 0 0 12px 0;
    font-size: 28px;
    font-weight: 700;
    color: #222;
}

.profile-badges {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.badge-status.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-status.badge-success i {
    color: #28a745;
}

.badge-status.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.badge-status.badge-danger i {
    color: #dc3545;
}

.badge-id {
    background: #f0f0f0;
    color: #666;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.info-grid-enhanced {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.section-heading {
    margin: 0 0 20px 0;
    font-size: 18px;
    font-weight: 700;
    color: #222;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.info-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.info-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 18px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.info-card:hover {
    background: white;
    border-color: #e9ecef;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.info-card-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.info-card-icon.email-icon {
    background: #fff3cd;
    color: #ff9800;
}

.info-card-icon.phone-icon {
    background: #cfe2ff;
    color: #0d6efd;
}

.info-card-icon.spec-icon {
    background: #d1e7dd;
    color: #198754;
}

.info-card-icon.spec-icon img {
    width: 24px;
    height: 24px;
}

.info-card-icon.date-icon {
    background: #f8d7da;
    color: #dc3545;
}

.info-card-content {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.info-value {
    margin: 0;
    font-size: 14px;
    color: #222;
    font-weight: 500;
    word-break: break-word;
}

/* Right Column */
.right-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-section-enhanced {
    background: white;
    border-radius: 12px;
    padding: 22px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.section-header-small {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header-small i {
    color: #2d6b47;
    font-size: 18px;
}

.section-header-small h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #222;
}

.certificate-box-enhanced {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.certificate-content {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.certificate-content i {
    font-size: 28px;
    color: #d32f2f;
}

.cert-details {
    display: flex;
    flex-direction: column;
}

.cert-name {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #222;
}

.cert-desc {
    margin: 4px 0 0 0;
    font-size: 12px;
    color: #999;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #2d6b47;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.btn-download:hover {
    background: #1a472a;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(45, 107, 71, 0.2);
    color: white;
}

.notes-box-enhanced {
    padding: 14px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #2d6b47;
}

.notes-box-enhanced p {
    margin: 0;
    font-size: 14px;
    color: #555;
    line-height: 1.6;
}

.status-summary {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 6px;
}

.summary-label {
    font-size: 13px;
    font-weight: 600;
    color: #666;
}

.summary-value {
    font-size: 13px;
    font-weight: 600;
    color: #222;
}

.summary-value.status-active {
    color: #28a745;
}

.summary-value.status-inactive {
    color: #dc3545;
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .info-cards {
        grid-template-columns: 1fr;
    }
    
    .profile-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-top: -40px;
    }
    
    .profile-header-info {
        padding-bottom: 0;
    }
    
    .profile-badges {
        justify-content: center;
    }
}
</style>
@endsection
