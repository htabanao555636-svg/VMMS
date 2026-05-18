@extends('layouts.admin')

@section('content')
<section id="category" class="content-section active">

    <div class="content-header">
        <div class="header-left">
            <h1 class="page-title">
                <i class="fas fa-car"></i> Wheeler Category Management
            </h1>
            <p class="header-subtitle">Manage vehicle categories and their services</p>
        </div>
        <button class="btn-add-new" onclick="toggleAddForm()">
            <i class="fas fa-plus-circle"></i> Add New Category
        </button>
    </div>

    {{-- ADD FORM --}}
    <div id="addFormContainer" class="form-card" style="display:none;">
        <div class="form-card-header">
            <h3><i class="fas fa-plus-circle"></i> Add New Wheeler Category</h3>
            <button type="button" class="btn-close-form" onclick="toggleAddForm()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.wheeler-categories.store') }}" method="POST" class="inline-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Category Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., 2-Wheelers" required>
                    @error('name') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" placeholder="e.g., Services for motorcycles">
                    @error('description') <span class="error-text">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group form-submit-group">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-plus"></i> Add Category
                    </button>
                    <button type="button" class="btn-cancel" onclick="toggleAddForm()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- EDIT FORM --}}
    <div id="editFormContainer" class="form-card form-card-edit" style="display:none;">
        <div class="form-card-header">
            <h3><i class="fas fa-edit"></i> Edit Wheeler Category</h3>
            <button type="button" class="btn-close-form" onclick="cancelEdit()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editWheelerForm" method="POST" class="inline-form">
            @csrf
            @method('PATCH')
            <div class="form-row">
                <div class="form-group">
                    <label>Category Name <span class="required">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" id="edit_description" name="description" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select id="edit_status" name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group form-submit-group">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="button" class="btn-cancel" onclick="cancelEdit()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="flash-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="content-box">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wheelerCategories as $wheeler)
                    <tr>
                        <td>{{ $wheeler->id }}</td>
                        <td>
                            <div class="category-name">{{ $wheeler->name }}</div>
                        </td>
                        <td>
                            <span class="category-desc">{{ $wheeler->description ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower($wheeler->status) }}">
                                {{ ucfirst($wheeler->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action edit" title="Edit"
                                    onclick="editWheelerCategory(this, {{ $wheeler->id }}, '{{ addslashes($wheeler->name) }}', '{{ addslashes($wheeler->description ?? '') }}', '{{ $wheeler->status }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.wheeler-categories.destroy', $wheeler) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center empty-state">
                            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                            <p>No categories found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($wheelerCategories->hasPages())
            {{ $wheelerCategories->links('components.pagination') }}
        @endif
    </div>

</section>

<style>
    .content-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        padding: 20px 0;
        border-bottom: 2px solid #f0f0f0;
    }

    .header-left h1 {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 6px;
        color: #1f2937;
    }

    .header-subtitle {
        color: #6b7280;
        font-size: 14px;
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
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(45,155,111,0.3);
        transition: opacity 0.2s, transform 0.15s;
        white-space: nowrap;
    }

    .btn-add-new:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(45,155,111,0.4);
        color: white;
    }

    /* ===== FORM CARD ===== */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        border: 1px solid #f0f0f0;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .form-card-edit {
        border-left: 4px solid #f59e0b;
    }

    .form-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-bottom: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close-form {
        background: none;
        border: none;
        font-size: 18px;
        color: #9ca3af;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-close-form:hover { background: #fee2e2; color: #dc2626; }

    .inline-form { padding: 20px 24px; }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-control {
        height: 40px;
        padding: 0 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13.5px;
        font-family: inherit;
        color: #1f2937;
        background: #fafafa;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2d9b6f;
        background: white;
        box-shadow: 0 0 0 3px rgba(45,155,111,0.1);
    }

    .form-submit-group {
        display: flex;
        gap: 8px;
        align-items: flex-end;
    }

    .btn-save {
        height: 40px;
        padding: 0 18px;
        background: linear-gradient(135deg, #1a5c42, #2d9b6f);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: inherit;
    }

    .btn-save:hover { opacity: 0.9; transform: translateY(-1px); }

    .btn-cancel {
        height: 40px;
        padding: 0 14px;
        background: white;
        color: #6b7280;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: inherit;
    }

    .btn-cancel:hover { border-color: #dc2626; color: #dc2626; background: #fef2f2; }

    .error-text { color: #dc2626; font-size: 12px; }
    .required   { color: #dc2626; }

    /* ===== FLASH ===== */
    .flash-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 18px;
        margin-bottom: 16px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border-left: 4px solid #10b981;
    }

    /* ===== TABLE ===== */
    .content-box {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.07);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
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
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f0f0;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .data-table tbody tr { transition: all 0.2s ease; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover td { background-color: #f9fafb; }

    .category-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }

    .category-desc {
        color: #6b7280;
        font-size: 13px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.status-active   { color: #065f46; }
    .status-badge.status-inactive { color: #991b1b; }

    /* ===== ACTIONS ===== */
    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-buttons form { margin: 0; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
        color: white;
        text-decoration: none;
    }

    .btn-action.edit   { background: #f59e0b; }
    .btn-action.edit:hover   { background: #d97706; transform: scale(1.1); box-shadow: 0 4px 12px rgba(245,158,11,0.4); color: white; }
    .btn-action.delete { background: #ef4444; }
    .btn-action.delete:hover { background: #dc2626; transform: scale(1.1); box-shadow: 0 4px 12px rgba(239,68,68,0.4); color: white; }

    /* ===== EMPTY STATE ===== */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon  { font-size: 48px; color: #d1d5db; margin-bottom: 15px; }
    .empty-state p { color: #6b7280; font-size: 16px; margin: 0; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .content-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .btn-add-new { width: 100%; justify-content: center; }
        .form-row { grid-template-columns: 1fr; }
        .form-submit-group { flex-direction: column; }
        .btn-save, .btn-cancel { width: 100%; justify-content: center; }
        .data-table th, .data-table td { padding: 10px 8px; font-size: 12px; }
        .btn-action { width: 30px; height: 30px; font-size: 11px; }
    }
</style>

<script>
function toggleAddForm() {
    const container = document.getElementById('addFormContainer');
    const isVisible = container.style.display !== 'none';
    container.style.display = isVisible ? 'none' : 'block';
    if (!isVisible) container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function editWheelerCategory(button, id, name, description, status) {
    document.getElementById('edit_name').value        = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_status').value      = status;

    const form = document.getElementById('editWheelerForm');
    form.action = '{{ url("admin/categories") }}/' + id;

    const container = document.getElementById('editFormContainer');
    container.style.display = 'block';
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function cancelEdit() {
    document.getElementById('editFormContainer').style.display = 'none';
    document.getElementById('editWheelerForm').reset();
}
</script>

@endsection
