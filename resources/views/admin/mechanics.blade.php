@extends('layouts.admin')

@section('content')

<style>
/* ===== ANIMATIONS ===== */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
@keyframes scaleIn  { from { opacity: 0; transform: scale(0.95); }     to { opacity: 1; transform: scale(1); } }
@keyframes pulse    { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }

/* ===== HEADER ===== */
.content-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    padding-bottom: 1.25rem;
    border-bottom: 2px solid #e5e7eb;
    animation: fadeInUp 0.4s ease-out;
}

.page-title {
    font-size: 26px;
    font-weight: 800;
    color: #1f2937;
    margin: 0;
    letter-spacing: -0.3px;
}

.btn-add-new {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    border: none;
    cursor: pointer;
}

.btn-add-new:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
    color: white;
    text-decoration: none;
}

/* ===== FLASH ===== */
.flash-success {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    margin-bottom: 1.25rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border-left: 4px solid #10b981;
    animation: fadeInUp 0.4s ease-out;
}

/* ===== FILTER SECTION ===== */
.filter-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #f0f0f0;
    animation: fadeInUp 0.45s ease-out;
}

.filter-row {
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-item.search-item {
    flex: 1;
    min-width: 220px;
}

.filter-label {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.filter-input,
.filter-select {
    height: 40px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13.5px;
    font-family: inherit;
    color: #1f2937;
    background: #fafafa;
    transition: all 0.2s ease;
}

.filter-input {
    padding: 0 14px;
    width: 100%;
}

.filter-select {
    padding: 0 12px;
    min-width: 160px;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #059669;
    background: white;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.filter-input::placeholder { color: #9ca3af; }

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: flex-end;
}

.btn-filter {
    height: 40px;
    padding: 0 18px;
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
    font-family: inherit;
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.btn-clear-filter {
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
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    font-family: inherit;
}

.btn-clear-filter:hover {
    border-color: #dc2626;
    color: #dc2626;
    background: #fef2f2;
    text-decoration: none;
}

/* Active filter tags */
.active-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* ===== TABLE WRAPPER ===== */
.content-box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    overflow: hidden;
    animation: scaleIn 0.4s ease-out;
    border: 1px solid #f0f0f0;
}

.table-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* ===== TABLE ===== */
.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.data-table thead {
    background: #f8fafc;
    border-bottom: 2px solid #e5e7eb;
}

.data-table th {
    padding: 13px 14px;
    text-align: left;
    font-weight: 700;
    color: #374151;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    white-space: nowrap;
}

.data-table td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f4f6;
    font-size: 13px;
    color: #1f2937;
    vertical-align: middle;
}

.data-table tbody tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover td { background: #f9fafb; }

/* ===== CELL CONTENT ===== */
.mechanic-id {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    font-size: 12px;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 3px 8px;
    border-radius: 5px;
    display: inline-block;
}

.mechanic-name {
    font-weight: 700;
    color: #1f2937;
    font-size: 14px;
}

.text-muted {
    color: #9ca3af;
    font-size: 12px;
}

.specialization-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 5px 11px;
    border-radius: 7px;
    font-weight: 700;
    font-size: 12px;
}

.contact-row {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #4b5563;
    font-size: 12.5px;
    line-height: 1.9;
}

.contact-row i {
    width: 14px;
    color: #2563eb;
    font-size: 11px;
    flex-shrink: 0;
}

/* ===== STATUS BADGES ===== */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

.badge-active   { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.badge-inactive { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 6px;
    align-items: center;
}

.action-buttons form { margin: 0; }

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 7px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
    color: white;
    text-decoration: none;
}

.btn-action:hover { transform: translateY(-2px); filter: brightness(1.1); color: white; text-decoration: none; }
.btn-action.view   { background: #3b82f6; }
.btn-action.edit   { background: #f59e0b; }
.btn-action.delete { background: #ef4444; }

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #9ca3af;
}



/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(3px);
}

.modal-overlay.active { display: flex; }

.confirm-modal {
    background: white;
    padding: 30px;
    border-radius: 14px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    text-align: center;
    animation: scaleIn 0.3s ease-out;
}

.confirm-modal .modal-icon { font-size: 36px; margin-bottom: 12px; }
.confirm-modal h3 { margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #1f2937; }
.confirm-modal p  { margin: 0 0 22px; color: #6b7280; font-size: 14px; line-height: 1.6; }
.confirm-buttons  { display: flex; gap: 10px; justify-content: center; }

.confirm-btn {
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
}

.confirm-btn-danger     { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
.confirm-btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.3); }
.confirm-btn-secondary  { background: #f3f4f6; color: #374151; }
.confirm-btn-secondary:hover { background: #e5e7eb; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .content-header  { flex-direction: column; align-items: flex-start; gap: 12px; }
    .filter-row      { flex-direction: column; gap: 10px; }
    .filter-item     { width: 100%; }
    .filter-item.search-item { min-width: unset; }
    .filter-select   { min-width: unset; width: 100%; }
    .filter-actions  { width: 100%; }
    .btn-filter,
    .btn-clear-filter { flex: 1; justify-content: center; }
}
</style>

<section id="mechanics" class="content-section active">

    {{-- HEADER --}}
    <div class="content-header">
        <h1 class="page-title">
            <i class="fas fa-wrench" style="color:#059669;margin-right:8px;font-size:22px;"></i>
            Mechanic List
        </h1>
        <a href="{{ route('admin.mechanics.create') }}" class="btn-add-new">
            <i class="fas fa-plus"></i> Add New Mechanic
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="flash-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- FILTER SECTION --}}
    <div class="filter-section">
        <form method="GET" action="{{ route('admin.mechanics') }}">
            <div class="filter-row">

                {{-- Search --}}
                <div class="filter-item search-item">
                    <label class="filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Search</label>
                    <input
                        type="text"
                        name="search"
                        class="filter-input"
                        placeholder="Name, email, phone or specialization…"
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>

                {{-- Status --}}
                <div class="filter-item">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Specialization — pulled from DB so it's always accurate --}}
                <div class="filter-item">
                    <label class="filter-label">Specialization</label>
                    <select name="specialization" class="filter-select">
                        <option value="">All Specializations</option>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec }}" {{ request('specialization') === $spec ? 'selected' : '' }}>
                                {{ $spec }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    @if(request()->hasAny(['search','status','specialization']))
                        <a href="{{ route('admin.mechanics') }}" class="btn-clear-filter">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>

            </div>
        </form>

        {{-- Active filter tags --}}
        @if(request()->hasAny(['search','status','specialization']))
            <div class="active-filters">
                @if(request('search'))
                    <span class="filter-tag">
                        <i class="fas fa-search"></i> "{{ request('search') }}"
                    </span>
                @endif
                @if(request('status'))
                    <span class="filter-tag">
                        <i class="fas fa-circle"></i> {{ ucfirst(request('status')) }}
                    </span>
                @endif
                @if(request('specialization'))
                    <span class="filter-tag">
                        <i class="fas fa-tools"></i> {{ request('specialization') }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="content-box">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Contact</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mechanics as $mechanic)
                    <tr>
                        {{-- ID --}}
                        <td><span class="mechanic-id">#{{ $mechanic->id }}</span></td>

                        {{-- Name --}}
                        <td>
                            <div class="mechanic-name">{{ $mechanic->name }}</div>
                            <span class="text-muted">{{ $mechanic->email }}</span>
                        </td>

                        {{-- Specialization --}}
                        <td>
                            <span class="specialization-badge">
                                <i class="fas fa-tools"></i>
                                {{ $mechanic->specialization }}
                            </span>
                        </td>

                        {{-- Contact --}}
                        <td>
                            <div class="contact-row">
                                <i class="fas fa-phone"></i>
                                {{ $mechanic->phone ?? 'N/A' }}
                            </div>
                            <div class="contact-row">
                                <i class="fas fa-envelope"></i>
                                {{ $mechanic->email }}
                            </div>
                        </td>

                        {{-- Date Added --}}
                        <td>
                            <span style="color:#6b7280;font-size:13px;">
                                {{ $mechanic->date_added
                                    ? \Carbon\Carbon::parse($mechanic->date_added)->format('M d, Y')
                                    : '—' }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td>
                            <span class="badge badge-{{ $mechanic->status }}">
                                {{ ucfirst($mechanic->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.mechanics.show', $mechanic->id) }}"
                                   class="btn-action view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.mechanics.edit', $mechanic->id) }}"
                                   class="btn-action edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.mechanics.destroy', $mechanic->id) }}"
                                      method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn-action delete"
                                            title="Delete"
                                            onclick="event.preventDefault(); showConfirm(this.form)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div style="font-size:44px;margin-bottom:10px;opacity:0.35;">🔧</div>
                                <p style="margin:0;font-weight:700;color:#374151;">No mechanics found</p>
                                <p style="font-size:12px;margin:6px 0 0;color:#9ca3af;">
                                    Try adjusting your filters or
                                    <a href="{{ route('admin.mechanics.create') }}" style="color:#059669;">add a new mechanic</a>
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($mechanics->hasPages())
            {{ $mechanics->links('components.pagination') }}
        @endif
    </div>

</section>

{{-- DELETE CONFIRM MODAL --}}
<div id="confirmModal" class="modal-overlay">
    <div class="confirm-modal">
        <div class="modal-icon">🗑️</div>
        <h3>Delete Mechanic?</h3>
        <p>This will permanently remove the mechanic and cannot be undone. Any active service request assignments may be affected.</p>
        <div class="confirm-buttons">
            <button class="confirm-btn confirm-btn-danger" onclick="confirmAction()">
                <i class="fas fa-trash"></i> Yes, Delete
            </button>
            <button class="confirm-btn confirm-btn-secondary" onclick="cancelAction()">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
let pendingForm = null;

function showConfirm(form) {
    pendingForm = form;
    document.getElementById('confirmModal').classList.add('active');
}

function confirmAction() {
    document.getElementById('confirmModal').classList.remove('active');
    if (pendingForm) pendingForm.submit();
}

function cancelAction() {
    document.getElementById('confirmModal').classList.remove('active');
    pendingForm = null;
}

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) cancelAction();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cancelAction();
});

// Auto-submit on dropdown change
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

@endsection