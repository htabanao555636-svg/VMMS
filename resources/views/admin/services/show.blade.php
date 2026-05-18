@extends('layouts.admin')

@section('content')
<div class="detail-section">
    <!-- Header -->
    <div class="detail-header">
        <div class="header-content">
            <a href="{{ route('admin.services') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>
            <h1 class="detail-title">
                <i class="fas fa-wrench"></i> {{ $service->name }}
            </h1>
            <p class="detail-subtitle">Service details and information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this service?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Detail Container -->
    <div class="detail-container">
        <!-- Service Information -->
        <div class="detail-section-box">
            <h2 class="section-heading">
                <i class="fas fa-info-circle"></i> Service Information
            </h2>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Service ID</div>
                    <div class="info-value">#{{ $service->id }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Vehicle Type</div>
                    <div class="info-value">
                        <span class="badge badge-info">
                            {{ $service->wheelerCategory->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Price</div>
                    <div class="info-value price-display">₱{{ number_format($service->price, 2) }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge badge-{{ strtolower($service->status) }}">
                            {{ ucfirst($service->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="detail-section-box">
            <h2 class="section-heading">
                <i class="fas fa-file-alt"></i> Description
            </h2>
            
            <div class="description-content">
                {{ $service->description ?? 'No description provided' }}
            </div>
        </div>

        <!-- Timeline Information -->
        <div class="detail-section-box">
            <h2 class="section-heading">
                <i class="fas fa-calendar-alt"></i> Timeline Information
            </h2>

            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Created At</div>
                    <div class="info-value">{{ $service->created_at->format('M d, Y h:i A') }}</div>
                </div>

                <div class="info-card">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ $service->updated_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="detail-section-box">
            <h2 class="section-heading">
                <i class="fas fa-lightning-bolt"></i> Quick Actions
            </h2>

            <div class="action-grid">
                <a href="{{ route('admin.services.edit', $service) }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Edit Details</div>
                        <div class="action-desc">Update service information</div>
                    </div>
                </a>

                <a href="{{ route('admin.services') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">View All Services</div>
                        <div class="action-desc">Back to services list</div>
                    </div>
                </a>

                <a href="{{ route('admin.categories') }}" class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">View Category</div>
                        <div class="action-desc">View related categories</div>
                    </div>
                </a>
            </div>
        </div>
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
        --success: #16a34a;
    }

    .detail-section {
        padding: 20px;
    }

    .detail-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
    }

    .header-content {
        flex: 1;
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

    .detail-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 28px;
        font-weight: 600;
        color: var(--text);
        margin: 15px 0 0 0;
    }

    .detail-title i {
        color: var(--primary);
        font-size: 32px;
    }

    .detail-subtitle {
        color: var(--text-muted);
        font-size: 14px;
        margin: 8px 0 0 0;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .detail-container {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 30px;
    }

    .detail-section-box {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
    }

    .detail-section-box:last-child {
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

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-card {
        background: #ffffff; 
        padding: 16px;
        border-radius: 8px;
        border: 1px solid var(--primary);
        border-left: 4px solid var(--primary);
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 15px;
        color: var(--text);
        font-weight: 500;
    }

    .price-display {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary);
    }

    .description-content {
        background: var(--surface-alt);
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
        line-height: 1.6;
        color: var(--text-muted);
        font-size: 14px;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-info {
        background: rgba(30, 122, 84, 0.1);
        color: var(--primary);
    }

    .badge-active {
        background: rgba(22, 163, 74, 0.1);
        color: var(--success);
    }

    .badge-inactive {
        background: rgba(220, 38, 38, 0.1);
        color: var(--error);
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: var(--surface-alt);
        border: 2px solid var(--border);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-card:hover {
        border-color: var(--primary);
        background: var(--surface);
        box-shadow: 0 4px 12px rgba(30, 122, 84, 0.15);
        transform: translateY(-2px);
    }

    .action-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .action-content {
        flex: 1;
    }

    .action-title {
        font-weight: 600;
        color: var(--text);
        font-size: 14px;
    }

    .action-desc {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
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

    .btn-danger {
        background: var(--error);
        color: white;
    }

    .btn-danger:hover {
        background: #b91c1c;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .detail-header {
            flex-direction: column;
            gap: 15px;
        }

        .header-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .detail-container {
            padding: 20px;
        }

        .info-grid,
        .action-grid {
            grid-template-columns: 1fr;
        }

        .detail-title {
            font-size: 24px;
        }

        .section-heading {
            font-size: 16px;
        }

        .action-card {
            flex-direction: column;
            text-align: center;
            gap: 12px;
        }

        .action-icon {
            margin: 0 auto;
        }
    }
</style>
@endsection
