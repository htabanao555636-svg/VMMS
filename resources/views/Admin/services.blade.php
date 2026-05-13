@extends('layouts.admin')

@section('content')
<section id="service" class="content-section active">
    <div class="content-header">
        <div class="header-left">
            <h1 class="page-title">
                <i class="fas fa-cogs"></i> Service Management
            </h1>
            <p class="header-subtitle">Manage system services and pricing</p>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn-add-new">
            <i class="fas fa-plus-circle"></i> Add New Service
        </a>
    </div>

    <div class="content-box">
        <!-- Stats Section -->
        <div class="list-stats">
            <div class="stat-item">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Active</span>
                    <span class="stat-value">{{ $activeCount }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon inactive">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Inactive</span>
                    <span class="stat-value">{{ $inactiveCount }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon price">
                    <i class="fas fa-peso-sign"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Avg Price</span>
                    <span class="stat-value">₱{{ number_format($avgPrice, 0) }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon total">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total</span>
                    <span class="stat-value">{{ $totalServices }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="list-controls">
            <div class="search-box">
                <input
                    type="text"
                    id="serviceSearch"
                    class="search-input"
                    placeholder="Search by service name or category..."
                >
            </div>
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($wheelerCategories as $category)
                    <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Services Table -->
        <div class="table-wrapper">
            <table class="data-table services-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">
                            <i class="fas fa-briefcase"></i> Service Name
                        </th>
                        <th class="col-category">
                            <i class="fas fa-folder"></i> Category
                        </th>
                        <th class="col-price">
                            <i class="fas fa-tag"></i> Price
                        </th>
                        <th class="col-status">
                            <i class="fas fa-circle"></i> Status
                        </th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr class="service-row"
                        data-name="{{ strtolower($service->name) }}"
                        data-category="{{ strtolower(optional($service->wheelerCategory)->name ?? 'uncategorized') }}"
                        data-status="{{ $service->status }}">
                        <td class="col-id">{{ $service->id }}</td>
                        <td class="col-name">
                            <div class="service-info-cell">
                                <div class="service-icon">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <div class="service-name">{{ $service->name }}</div>
                                    <div class="service-description">
                                        {{ substr($service->description ?? 'No description', 0, 50) }}{{ strlen($service->description ?? '') > 50 ? '...' : '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="col-category">
                            <span class="category-badge">
                                {{ optional($service->wheelerCategory)->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="col-price">
                            <span class="price-badge">₱{{ number_format($service->price, 2) }}</span>
                        </td>
                        <td class="col-status">
                            <span class="status-badge status-{{ strtolower($service->status) }}">
                                {{ ucfirst($service->status) }}
                            </span>
                        </td>
                        <td class="col-actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.services.show', $service) }}" class="btn-action view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn-action edit" title="Edit Service">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete Service"
                                        onclick="return confirm('Are you sure you want to delete this service?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No services found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($services->hasPages())
            {{ $services->links('components.pagination') }}
        @endif
    </div>
</section>

<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
        padding: 20px 0;
        border-bottom: 2px solid #f0f0f0;
    }

    .header-left h1 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 28px;
        margin-bottom: 8px;
    }

    .header-subtitle {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    .list-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #fafafa;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        background: #f0f9ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
    }

    .stat-icon.active   { background: linear-gradient(135deg, #34d399 0%, #10b981 100%); }
    .stat-icon.inactive { background: linear-gradient(135deg, #fca5a5 0%, #ef4444 100%); }
    .stat-icon.price    { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); }
    .stat-icon.total    { background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%); }

    .stat-content { display: flex; flex-direction: column; }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
    }

    .list-controls {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        padding: 15px 0;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 250px;
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #2d9b6f;
        box-shadow: 0 0 0 3px rgba(45, 155, 111, 0.1);
    }

    .filter-group { display: flex; gap: 10px; }

    .filter-select {
        padding: 12px 15px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        background-color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:hover,
    .filter-select:focus {
        border-color: #2d9b6f;
        outline: none;
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .data-table thead {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #1f2937;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #374151;
        font-size: 14px;
    }

    .data-table tbody tr { transition: all 0.2s ease; }
    .data-table tbody tr:hover { background-color: #f9fafb; }

    .col-id      { width: 50px; }
    .col-name    { width: 35%; }
    .col-category{ width: 20%; }
    .col-price   { width: 15%; }
    .col-status  { width: 12%; }
    .col-actions { width: 8%; text-align: center; }

    .service-info-cell {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .service-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .service-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .service-description {
        color: #9ca3af;
        font-size: 12px;
        line-height: 1.4;
    }

    .category-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #f3f4f6;
        border-radius: 6px;
        font-size: 13px;
        color: #4b5563;
        font-weight: 500;
    }

    .price-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        color: #92400e;
        font-weight: 700;
        font-size: 14px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.status-active   { color: #065f46; }
    .status-badge.status-inactive { color: #991b1b; }

    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: all 0.2s ease;
        color: white;
        text-decoration: none;
    }

    .btn-action.view   { background: #3b82f6; }
    .btn-action.view:hover   { background: #2563eb; transform: scale(1.1); box-shadow: 0 4px 12px rgba(59,130,246,0.4); color: white; }
    .btn-action.edit   { background: #f59e0b; }
    .btn-action.edit:hover   { background: #d97706; transform: scale(1.1); box-shadow: 0 4px 12px rgba(245,158,11,0.4); color: white; }
    .btn-action.delete { background: #ef4444; }
    .btn-action.delete:hover { background: #dc2626; transform: scale(1.1); box-shadow: 0 4px 12px rgba(239,68,68,0.4); color: white; }

    .empty-state { padding: 60px 20px; }
    .empty-icon  { font-size: 48px; color: #d1d5db; margin-bottom: 15px; }
    .empty-state p { color: #6b7280; font-size: 16px; margin: 0; }

    .btn-add-new {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        background: linear-gradient(135deg, #1a5c42, #2d9b6f) !important;
        color: white !important;
        padding: 11px 22px !important;
        border-radius: 10px !important;
        border: none !important;
        text-decoration: none !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(45,155,111,0.3) !important;
        transition: opacity 0.2s, transform 0.15s !important;
        white-space: nowrap !important;
    }

    .btn-add-new:hover {
        opacity: 0.9 !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(45,155,111,0.4) !important;
        color: white !important;
    }

    @media (max-width: 1024px) {
        .list-stats { grid-template-columns: repeat(2, 1fr) !important; }
        .list-controls { flex-direction: column !important; gap: 12px !important; }
        .search-box { width: 100% !important; min-width: unset !important; flex: unset !important; }
        .filter-group { width: 100% !important; flex-wrap: wrap !important; }
        .filter-select { flex: 1 !important; min-width: 150px !important; }
        .table-wrapper { overflow-x: auto !important; }
        .services-table { min-width: 650px; }
    }

    @media (max-width: 768px) {
        .content-header { flex-direction: column !important; align-items: flex-start !important; gap: 12px !important; }
        .btn-add-new { width: 100% !important; justify-content: center !important; }
        .list-stats { grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
        .stat-item { padding: 12px !important; gap: 10px !important; }
        .stat-icon { width: 36px !important; height: 36px !important; font-size: 16px !important; }
        .stat-value { font-size: 16px !important; }
        .stat-label { font-size: 11px !important; }
        .list-controls { flex-direction: column !important; gap: 8px !important; }
        .search-box { width: 100% !important; min-width: unset !important; flex: unset !important; }
        .search-input { width: 100% !important; }
        .filter-group { display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 8px !important; width: 100% !important; }
        .filter-select { width: 100% !important; padding: 10px 12px !important; font-size: 13px !important; }
        .services-table { min-width: 600px !important; }
        .data-table th, .data-table td { padding: 10px 8px !important; font-size: 12px !important; white-space: nowrap; }
        .service-icon { width: 32px !important; height: 32px !important; font-size: 13px !important; }
        .service-name { font-size: 12px !important; }
        .service-description { display: none !important; }
        .action-buttons { gap: 4px !important; }
        .btn-action { width: 30px !important; height: 30px !important; font-size: 11px !important; }
    }

    @media (max-width: 480px) {
        .list-stats { grid-template-columns: repeat(2, 1fr) !important; }
        .filter-group { grid-template-columns: 1fr !important; }
        .services-table { min-width: 520px !important; }
        .data-table th, .data-table td { padding: 8px 6px !important; font-size: 11px !important; }
        .stat-item { padding: 10px !important; }
        .stat-value { font-size: 15px !important; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput  = document.getElementById('serviceSearch');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const serviceRows  = document.querySelectorAll('.service-row');

    function filterServices() {
        const searchTerm       = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedStatus   = statusFilter ? statusFilter.value : '';
        const selectedCategory = categoryFilter ? categoryFilter.value : '';

        serviceRows.forEach(row => {
            const name     = row.dataset.name || '';
            const status   = row.dataset.status || '';
            const category = row.dataset.category || '';

            const matchesSearch   = !searchTerm || name.includes(searchTerm);
            const matchesStatus   = !selectedStatus || status === selectedStatus;
            const matchesCategory = !selectedCategory || category === selectedCategory;

            row.style.display = matchesSearch && matchesStatus && matchesCategory ? '' : 'none';
        });
    }

    if (searchInput)    searchInput.addEventListener('keyup', filterServices);
    if (statusFilter)   statusFilter.addEventListener('change', filterServices);
    if (categoryFilter) categoryFilter.addEventListener('change', filterServices);
});
</script>
@endsection