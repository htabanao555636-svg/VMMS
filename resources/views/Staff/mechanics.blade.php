@extends('layouts.admin')

@section('content')
<div class="mechanics-section">
    <!-- Header Section -->
    <div class="section-header-enhanced">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="header-text">
                <h1 class="section-title">Mechanic Management</h1>
                <p class="section-subtitle">Manage and view your mechanics team</p>
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="header-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $mechanics->total() ?? 0 }}</div>
                <div class="stat-label">Total Mechanics</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ ($mechanics ?? collect())->where('status', 'active')->count() ?? 0 }}</div>
                <div class="stat-label">Active</div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-content">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter/Search Bar -->
    <div class="filter-bar-enhanced">
        <div class="search-box-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-mechanics" class="form-control" placeholder="Search by name, email, or specialization...">
            </div>
        </div>
        <div class="filter-group">
            <div class="filter-item">
                <label class="filter-label">Status</label>
                <select id="filter-status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Specialization</label>
                <select id="filter-specialization" class="form-control">
                    <option value="">All Specializations</option>
                    @foreach(['Engine', 'Brake', 'Electrical', 'Transmission', 'Suspension', 'General'] as $spec)
                        <option value="{{ $spec }}">{{ $spec }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Mechanics Table -->
    <div class="table-container-enhanced">
        <table class="mechanics-table-enhanced">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-name">Name</th>
                    <th class="col-spec">Specialization</th>
                    <th class="col-contact">Contact</th>
                    <th class="col-date">Date Added</th>
                    <th class="col-status">Status</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mechanics ?? [] as $mechanic)
                <tr class="mechanic-row" data-mechanic-id="{{ $mechanic->id }}">
                    <td class="id-cell">
                        <span class="id-badge">#{{ $mechanic->id }}</span>
                    </td>

                    <td class="name-cell">
                        <div class="mechanic-avatar">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="mechanic-name-info">
                            <strong>{{ $mechanic->name }}</strong>
                            <small class="text-muted">{{ $mechanic->email ?? '—' }}</small>
                        </div>
                    </td>

                    <td class="specialization-cell">
                        <div class="specialization-badge-enhanced">
                            <img src="{{ asset('images/specializations/' . $mechanic->specialization . '.svg') }}"
                                 alt="{{ $mechanic->specialization }}"
                                 class="spec-icon"
                                 onerror="this.src='{{ asset('images/specializations/General.svg') }}'">
                            <span class="spec-name">{{ $mechanic->specialization }}</span>
                        </div>
                    </td>

                    {{-- FIX: use $mechanic->phone not phone_number --}}
                    <td class="contact-cell">
                        <div class="contact-info-enhanced">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $mechanic->phone ?? '—' }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="date-cell">
                        <span class="date-badge">{{ $mechanic->created_at->format('M d, Y') }}</span>
                    </td>

                    {{-- FIX: text color only, no background --}}
                    <td class="status-cell">
                        @if($mechanic->status === 'active')
                            <span class="status-text status-active">
                                <i class="fas fa-circle" style="font-size:8px;"></i> Active
                            </span>
                        @else
                            <span class="status-text status-inactive">
                                <i class="fas fa-circle" style="font-size:8px;"></i> Inactive
                            </span>
                        @endif
                    </td>

                    <td class="actions-cell">
                        <div class="action-buttons-enhanced">
                            <a href="{{ route('staff.mechanics.show', $mechanic) }}"
                               class="btn-action-enhanced view"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Certificate button — only show if certificate exists --}}
                            @if($mechanic->certificate_path)
                                <button type="button"
                                    class="btn-action-enhanced certificate"
                                    title="View Certificate"
                                    onclick="viewCertificate('{{ asset('storage/' . $mechanic->certificate_path) }}', '{{ $mechanic->name }}')">
                                    <i class="fas fa-certificate"></i>
                                </button>
                            @else
                                <button type="button"
                                    class="btn-action-enhanced certificate disabled"
                                    title="No certificate uploaded"
                                    disabled>
                                    <i class="fas fa-file-slash"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center empty-state">
                        <div class="empty-state-content">
                            <i class="fas fa-inbox"></i>
                            <p class="empty-message">No mechanics found</p>
                            <small class="text-muted">Try adjusting your search filters</small>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($mechanics, 'links'))
        <div class="pagination-wrapper">
            {{ $mechanics->links('components.pagination') }}
        </div>
    @endif
</div>

{{-- Certificate Modal --}}
<div id="certificateModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.75);
    z-index:9999; justify-content:center; align-items:center; backdrop-filter:blur(4px);">
    <div style="background:white; border-radius:14px; width:90%; max-width:700px;
        max-height:90vh; overflow-y:auto; position:relative;
        box-shadow:0 20px 60px rgba(0,0,0,0.3);">

        <!-- Modal Header -->
        <div style="padding:20px 24px 16px; border-bottom:1px solid #e5e7eb;
            display:flex; align-items:center; justify-content:space-between;">
            <div>
                <h3 style="margin:0; font-size:17px; font-weight:800; color:#1a2e1a;">
                    <i class="fas fa-file-certificate" style="color:#2d9b6f; margin-right:8px;"></i>
                    Certificate
                </h3>
                <p id="certMechanicName" style="margin:4px 0 0; font-size:13px; color:#6b7280;"></p>
            </div>
            <button onclick="closeCertificate()"
                style="border:none; background:#f3f4f6; border-radius:8px;
                width:32px; height:32px; font-size:18px; cursor:pointer;
                color:#6b7280; display:flex; align-items:center; justify-content:center;">
                ×
            </button>
        </div>

        <!-- Modal Body -->
        <div style="padding:20px 24px 24px; text-align:center;">
            <div id="certContent"></div>
            <a id="certDownloadLink" href="#" download
                style="display:inline-flex; align-items:center; gap:8px; margin-top:16px;
                padding:10px 20px; background:linear-gradient(135deg,#1a5c42,#2d9b6f);
                color:white; border-radius:8px; text-decoration:none;
                font-size:13px; font-weight:700;
                box-shadow:0 3px 8px rgba(45,155,111,0.3);">
                <i class="fas fa-download"></i> Download Certificate
            </a>
        </div>
    </div>
</div>

<style>
.mechanics-section {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.section-header-enhanced {
    background: linear-gradient(135deg, #1a472a 0%, #2d6b47 100%);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    font-size: 40px;
    opacity: 0.9;
}

.header-text .section-title {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.header-text .section-subtitle {
    margin: 5px 0 0 0;
    font-size: 14px;
    opacity: 0.85;
}

.header-stats {
    display: flex;
    gap: 20px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 15px 25px;
    text-align: center;
    min-width: 140px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.alert {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-content i {
    font-size: 18px;
}

.filter-bar-enhanced {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 20px;
    align-items: flex-end;
}

.search-box-container {
    flex: 1;
    min-width: 300px;
}

.search-box {
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

.search-box input {
    width: 100%;
    padding: 10px 15px 10px 42px;
    border: 1px solid #ddd;
    border-radius: 6px;
    height: 40px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    border-color: #2d6b47;
    box-shadow: 0 0 0 3px rgba(45, 107, 71, 0.1);
    outline: none;
}

.filter-group {
    display: flex;
    gap: 15px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    min-width: 180px;
}

.filter-label {
    font-size: 12px;
    font-weight: 600;
    color: #333;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-item select {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 8px 12px;
    font-size: 14px;
    height: 40px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-item select:focus {
    border-color: #2d6b47;
    box-shadow: 0 0 0 3px rgba(45, 107, 71, 0.1);
    outline: none;
}

.table-container-enhanced {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
}

.mechanics-table-enhanced {
    width: 100%;
    border-collapse: collapse;
}

.mechanics-table-enhanced thead {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
}

.mechanics-table-enhanced thead th {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.col-id { width: 7%; }
.col-name { width: 23%; }
.col-spec { width: 16%; }
.col-contact { width: 14%; }
.col-date { width: 12%; }
.col-status { width: 10%; }
.col-actions { width: 10%; }

.mechanic-row {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.mechanic-row:hover {
    background: #f9fafb;
}

.mechanics-table-enhanced tbody td {
    padding: 16px 12px;
    font-size: 14px;
    vertical-align: middle;
}

.id-cell { color: #666; }

.id-badge {
    background: #f0f0f0;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 12px;
    color: #555;
}

.name-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.mechanic-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #2d6b47, #1a472a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.mechanic-name-info {
    display: flex;
    flex-direction: column;
}

.mechanic-name-info strong {
    color: #222;
    font-size: 14px;
}

.mechanic-name-info small {
    font-size: 12px;
    margin-top: 3px;
}

.specialization-badge-enhanced {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8f9fa;
    padding: 6px 12px;
    border-radius: 6px;
    width: fit-content;
}

.spec-icon { width: 20px; height: 20px; }

.spec-name {
    font-size: 13px;
    font-weight: 500;
    color: #555;
}

.contact-info-enhanced {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #374151;
    font-weight: 500;
}

.contact-item i { color: #2d6b47; }

/* FIX: Text-only status */
.status-text {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 13px;
    font-weight: 700;
}

.status-text.status-active { color: #059669; }
.status-text.status-inactive { color: #dc2626; }

.action-buttons-enhanced {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action-enhanced {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: none;
    background: #f0f0f0;
    color: #555;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    font-size: 14px;
}

.btn-action-enhanced.view:hover {
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(59,130,246,0.3);
}

.btn-action-enhanced.certificate:hover {
    background: linear-gradient(135deg, #1a5c42, #2d9b6f);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(45,155,111,0.3);
}

.btn-action-enhanced.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.btn-action-enhanced.disabled:hover {
    background: #f0f0f0;
    color: #555;
    transform: none;
    box-shadow: none;
}

.date-badge {
    background: #e8f4f8;
    color: #0c5460;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.empty-state { padding: 60px 20px !important; }

.empty-state-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.empty-state-content i { font-size: 48px; color: #ddd; }

.empty-message {
    margin: 0;
    font-size: 16px;
    color: #666;
    font-weight: 600;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

@media (max-width: 1200px) {
    .header-stats { display: none; }
    .filter-bar-enhanced { flex-direction: column; align-items: stretch; }
    .filter-group { flex-wrap: wrap; }
}

@media (max-width: 768px) {
    .section-header-enhanced { flex-direction: column; text-align: center; gap: 20px; }
    .header-content { flex-direction: column; }
    .col-contact, .col-date { display: none; }
    .mechanics-table-enhanced tbody td:nth-child(4),
    .mechanics-table-enhanced tbody td:nth-child(5) { display: none; }
    .table-container-enhanced { overflow-x: auto; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-mechanics');
    const statusFilter = document.getElementById('filter-status');
    const specFilter = document.getElementById('filter-specialization');
    const rows = document.querySelectorAll('.mechanic-row');

    function filterRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const specValue = specFilter.value;

        rows.forEach(row => {
            let show = true;

            if (searchTerm) {
                const name = row.querySelector('.mechanic-name-info strong').textContent.toLowerCase();
                const email = row.querySelector('.mechanic-name-info small').textContent.toLowerCase();
                show = name.includes(searchTerm) || email.includes(searchTerm);
            }

            if (show && statusValue) {
                const status = row.querySelector('.status-text').textContent.toLowerCase();
                show = status.includes(statusValue);
            }

            if (show && specValue) {
                const spec = row.querySelector('.spec-name').textContent.toLowerCase();
                show = spec.toLowerCase().includes(specValue.toLowerCase());
            }

            row.style.display = show ? '' : 'none';
        });
    }

    searchInput.addEventListener('keyup', filterRows);
    statusFilter.addEventListener('change', filterRows);
    specFilter.addEventListener('change', filterRows);
});

// Certificate modal
function viewCertificate(url, mechanicName) {
    document.getElementById('certMechanicName').textContent = mechanicName;
    document.getElementById('certDownloadLink').href = url;

    const ext = url.split('.').pop().toLowerCase();
    const content = document.getElementById('certContent');

    if (['jpg', 'jpeg', 'png', 'webp', 'gif'].includes(ext)) {
        content.innerHTML = `<img src="${url}" alt="Certificate"
            style="max-width:100%;border-radius:8px;border:1px solid #e5e7eb;">`;
    } else if (ext === 'pdf') {
        content.innerHTML = `<iframe src="${url}" width="100%" height="500px"
            style="border:none;border-radius:8px;border:1px solid #e5e7eb;"></iframe>`;
    } else {
        content.innerHTML = `<p style="color:#6b7280;padding:2rem;">
            Cannot preview this file type. Please download it.
        </p>`;
    }

    const modal = document.getElementById('certificateModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCertificate() {
    document.getElementById('certificateModal').style.display = 'none';
    document.getElementById('certContent').innerHTML = '';
    document.body.style.overflow = '';
}

document.getElementById('certificateModal').addEventListener('click', function(e) {
    if (e.target === this) closeCertificate();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCertificate();
});
</script>
@endsection