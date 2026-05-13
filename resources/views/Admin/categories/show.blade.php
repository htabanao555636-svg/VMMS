@extends('layouts.admin')

@section('content')
<section id="category-detail" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">{{ $category->name }}</h1>
        <div class="actions-header">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <a href="{{ route('admin.categories') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="content-box">
        <!-- Category Info -->
        <div class="info-card">
            <h2>Category Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Category ID</label>
                    <p>{{ $category->id }}</p>
                </div>
                <div class="info-item">
                    <label>Vehicle Type</label>
                    <p>
                        <span class="vehicle-type-label">
                            <i class="fas fa-{{ $category->vehicle_type === '3-wheeler' ? 'gopuram' : 'car' }}"></i>
                            {{ ucfirst($category->vehicle_type) }}
                        </span>
                    </p>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <p><span class="badge {{ strtolower($category->status) }}">{{ ucfirst($category->status) }}</span></p>
                </div>
                <div class="info-item">
                    <label>Created At</label>
                    <p>{{ $category->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="info-item">
                    <label>Last Updated</label>
                    <p>{{ $category->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <div class="description-section">
                <h3>Description</h3>
                <p>{{ $category->description ?? 'No description provided' }}</p>
            </div>
        </div>

        <!-- Services in this Category -->
        <div class="services-section">
            <h2>Services in this Category</h2>
            <p class="service-count">Total Services: <strong>{{ $category->services()->count() }}</strong></p>

            @if($category->services()->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->services as $service)
                    <tr>
                        <td>#{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
                        <td>₱{{ number_format($service->price, 2) }}</td>
                        <td><span class="badge {{ strtolower($service->status) }}">{{ ucfirst($service->status) }}</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.services.show', $service) }}" class="btn-action view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No services in this category yet</p>
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Service
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
    .actions-header {
        display: flex;
        gap: 10px;
    }

    .info-card {
        background: white;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .info-card h2 {
        color: #333;
        margin-bottom: 20px;
        font-size: 18px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-item {
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 6px;
        border-left: 3px solid #3498db;
    }

    .info-item label {
        display: block;
        font-weight: 600;
        color: #7f8c8d;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .info-item p {
        margin: 0;
        color: #333;
        font-size: 15px;
    }

    .description-section {
        padding: 15px;
        background-color: #f0f8ff;
        border-radius: 6px;
        border-left: 3px solid #2ecc71;
    }

    .description-section h3 {
        margin-top: 0;
        color: #333;
    }

    .description-section p {
        margin: 0;
        color: #555;
        line-height: 1.6;
    }

    .services-section {
        background: white;
        border-radius: 8px;
        padding: 25px;
    }

    .services-section h2 {
        color: #333;
        margin-bottom: 10px;
        font-size: 18px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    .service-count {
        color: #7f8c8d;
        margin-bottom: 20px;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .empty-state i {
        font-size: 48px;
        color: #bdc3c7;
        margin-bottom: 15px;
        display: block;
    }

    .empty-state p {
        color: #7f8c8d;
        margin-bottom: 20px;
    }

    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
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

    .btn-danger {
        background-color: #e74c3c;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge.active {
        background-color: #d4edda;
        color: #155724;
    }

    .badge.inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        color: white;
    }

    .btn-action.view {
        background-color: #3498db;
    }

    .btn-action.view:hover {
        background-color: #2980b9;
    }

    .btn-action.edit {
        background-color: #f39c12;
    }

    .btn-action.edit:hover {
        background-color: #d68910;
    }

    /* Vehicle Type Label */
    .vehicle-type-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .vehicle-type-label i {
        font-size: 14px;
    }
</style>
@endsection
