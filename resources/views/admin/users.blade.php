@extends('layouts.admin')

@section('content')
<section id="users" class="content-section active">
    <div class="content-header">
        <div class="header-left">
            <h1 class="page-title">
                <i class="fas fa-users"></i> User Management
            </h1>
            <p class="header-subtitle">Manage system users and their roles</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-add-new">
            <i class="fas fa-plus-circle"></i> Add New User
        </a>
    </div>

    <div class="content-box">
        <!-- Stats Section -->
        <div class="list-stats">
            <div class="stat-item">
                <div class="stat-icon admin">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Admins</span>
                    <span class="stat-value">{{ $admins ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon staff">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Staff</span>
                    <span class="stat-value">{{ $staff ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon user">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Customers</span>
                    <span class="stat-value">{{ $customers ?? 0 }}</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon total">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total</span>
                    <span class="stat-value">{{ $total ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="list-controls">
            <div class="search-box">
                <input 
                    type="text" 
                    id="userSearch" 
                    class="search-input" 
                    placeholder="Search by name, email, or phone..."
                >
            </div>
            <div class="filter-group">
                <select id="roleFilter" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-wrapper">
            <table class="data-table users-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">
                            <i class="fas fa-user"></i> Name
                        </th>
                        <th class="col-email">
                            <i class="fas fa-envelope"></i> Email
                        </th>
                        <th class="col-phone">
                            <i class="fas fa-phone"></i> Phone
                        </th>
                        <th class="col-role">
                            <i class="fas fa-tag"></i> Role
                        </th>
                        <th class="col-status">
                            <i class="fas fa-circle"></i> Status
                        </th>
                        <th class="col-date">
                            <i class="fas fa-calendar"></i> Joined
                        </th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-phone="{{ strtolower($user->phone ?? '') }}" data-role="{{ $user->role }}">
                        <td class="col-id">{{ $user->id }}</td>
                        <td class="col-name">
                            <div class="user-info-cell">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="col-email">
                            <span class="email-badge">{{ $user->email }}</span>
                        </td>
                        <td class="col-phone">
                            <span class="phone-badge">{{ $user->phone ?? '—' }}</span>
                        </td>
                        <td class="col-role">
                            <span class="role-badge role-{{ $user->role }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'shield-alt' : ($user->role === 'staff' ? 'user-tie' : 'user') }}"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="col-status">
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Suspended</span>
                            @endif
                        </td>
                        <td class="col-date">
                            <span class="date-text" title="{{ $user->created_at->format('M d, Y H:i') }}">
                                {{ $user->created_at->diffForHumans() }}
                            </span>
                        </td>
                        <td class="col-actions">
                            <div class="action-buttons">
                                {{-- View --}}
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit User">
                                    <i class="fas fa-pen"></i>
                                </a>

                                {{-- Suspend / Unsuspend --}}
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}" title="{{ $user->is_active ? 'Suspend' : 'Reactivate' }}"
                                            onclick="return confirm('{{ $user->is_active ? 'Suspend' : 'Reactivate' }} this user?')">
                                            <i class="fas fa-{{ $user->is_active ? 'lock' : 'lock-open' }}"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Delete --}}
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete User"
                                            onclick="return confirm('Delete this user permanently?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p>No users found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($users) && $users->hasPages())
            {{ $users->links('components.pagination') }}
        @endif
    </div>
</section>

<style>
    /* User List Enhancements */
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

    /* Stats Section */
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

    .stat-icon.admin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-icon.staff {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-icon.user {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .stat-content {
        display: flex;
        flex-direction: column;
    }

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

    /* Filter & Search */
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

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
        pointer-events: none;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px 12px 42px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-group {
        display: flex;
        gap: 10px;
    }

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
        border-color: #3b82f6;
        outline: none;
    }

    /* Table Wrapper */
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

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f9fafb;
        box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.02);
    }

    /* Column Sizing */
    .col-id {
        width: 50px;
    }

    .col-name {
        width: 18%;
    }

    .col-email {
        width: 22%;
    }

    .col-phone {
        width: 12%;
    }

    .col-role {
        width: 10%;
    }

    .col-status {
        width: 10%;
    }

    .col-date {
        width: 10%;
    }

    .col-actions {
        width: 12%;
        text-align: center;
    }

    /* User Info Cell */
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    /* Email & Phone Badges */
    .email-badge,
    .phone-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #f3f4f6;
        border-radius: 6px;
        font-size: 13px;
        color: #4b5563;
        font-family: 'Monaco', 'Menlo', monospace;
    }

    /* Role Badge */
    .role-badge {
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

    .role-badge.role-admin {
        background: #fce7f3;
        color: #be185d;
    }

    .role-badge.role-staff {
        background: #dcfce7;
        color: #166534;
    }

    .role-badge.role-user {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Date */
    .date-text {
        color: #6b7280;
        font-size: 13px;
    }

    /* Action Buttons */
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
    }

    .btn-action.view {
        background: #3b82f6;
    }

    .btn-action.view:hover {
        background: #2563eb;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-action.edit {
        background: #f59e0b;
    }

    .btn-action.edit:hover {
        background: #d97706;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-action.delete {
        background: #ef4444;
    }

    .btn-action.delete:hover {
        background: #dc2626;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Bootstrap Button Styles for Actions */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    /* Badge Styles */
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

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 16px;
        margin: 0;
    }
    .btn-add-new {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #1a5c42, #2d9b6f);
    color: white;
    padding: 11px 22px;
    border-radius: 10px;
    border: none;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(45,155,111,0.3);
    transition: opacity 0.2s, transform 0.15s;
    white-space: nowrap;
    text-decoration: none;
}

.btn-add-new:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(45,155,111,0.4);
}

@media (max-width: 768px) {
    .btn-add-new {
        width: 100%;
        justify-content: center;
    }
}

    /* Responsive */
    @media (max-width: 1024px) {
        .list-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .col-name, .col-email, .col-phone, .col-role, .col-status, .col-date {
            width: auto;
        }

        /* Stack controls vertically on tablets to prevent overlap */
        .list-controls {
            flex-direction: column;
        }

        .search-box {
            width: 100%;
            min-width: unset;
            flex: unset;
        }

        .filter-group {
            width: 100%;
            flex-wrap: wrap;
        }

        .filter-select {
            flex: 1;
            min-width: 150px;
        }
    }

    @media (max-width: 768px) {
        .content-header {
            flex-direction: column;
            align-items: stretch;
        }

        .list-stats {
            grid-template-columns: 1fr;
        }

        .list-controls {
            flex-direction: column;
        }

        .search-box {
            min-width: 100%;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
            font-size: 12px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
    }
</style>

<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('userSearch');
        const roleFilter = document.getElementById('roleFilter');
        const userRows = document.querySelectorAll('.user-row');

        function filterUsers() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const selectedRole = roleFilter ? roleFilter.value : '';

            userRows.forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const phone = row.dataset.phone || '';
                const role = row.dataset.role || '';

                const matchesSearch = !searchTerm || 
                    name.includes(searchTerm) || 
                    email.includes(searchTerm) || 
                    phone.includes(searchTerm);

                const matchesRole = !selectedRole || role === selectedRole;

                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', filterUsers);
        }

        if (roleFilter) {
            roleFilter.addEventListener('change', filterUsers);
        }
    });
</script>
@endsection