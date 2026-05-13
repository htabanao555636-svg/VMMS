@extends('layouts.admin')

@section('content')
<style>
.stat-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 110px;
    position: relative;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}
.stat-card .card-icon {
    align-self: flex-start;
}
.stat-card .card-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
    margin-top: auto;
}
.stat-card .card-value {
    font-size: 2rem;
    font-weight: 600;
    line-height: 1.1;
}
.stat-card .card-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.7;
    margin-bottom: 4px;
    position: absolute;
    top: 28px;
    left: 60px;
}
.stat-card .card-sub {
    font-size: 11px;
    opacity: 0.6;
    margin-top: 2px;
}
</style>

<section id="dashboard" class="content-section active">
    <div class="content-header">
        <h1 class="page-title">Vehicle Maintenance Management System</h1>
    </div>
    <div class="stats-grid">
        <div class="stat-card" onclick="openKpiModal('categories')">
            <div class="card-icon">
                <i class="fas fa-th"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Total Categories</div>
                <div class="card-value">{{ $totalCategories }}</div>
            </div>
        </div>
        <div class="stat-card" onclick="openKpiModal('mechanics')">
            <div class="card-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Active Mechanics</div>
                <div class="card-value">{{ $activeMechanics }}</div>
            </div>
        </div>
        <div class="stat-card" onclick="openKpiModal('services')">
            <div class="card-icon">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Available Services</div>
                <div class="card-value">{{ $availableServices }}</div>
            </div>
        </div>
        <div class="stat-card" onclick="openKpiModal('completed')">
            <div class="card-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Completed Requests</div>
                <div class="card-value">{{ $completedRequests }}</div>
            </div>
        </div>
        <div class="stat-card" onclick="openKpiModal('total_requests')">
            <div class="card-icon">
                <i class="fas fa-list-alt"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Total Requests</div>
                <div class="card-value">{{ $totalRequests }}</div>
                <span class="card-sub">{{ $newThisWeek }} new this week</span>
            </div>
        </div>
        <div class="stat-card card-success" onclick="openKpiModal('revenue')">
            <div class="card-icon">
                <i class="fas fa-peso-sign"></i>
            </div>
            <div class="card-content">
                <div class="card-label">Revenue this month</div>
                <div class="card-value">₱{{ number_format($revenueThisMonth, 2) }}</div>
                <span class="card-sub">vs last month: ₱{{ number_format($revenueLastMonth, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="dashboard-panels">
        <div class="panel">
            <div class="panel-header">
                <h2>Recent Service Requests</h2>
            </div>
            <div class="table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Request #</th>
                            <th>Vehicle Registration</th>
                            <th>Vehicle</th>
                            <th>Service(s)</th>
                            <th>Mechanic</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($recentRequests->isEmpty())
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">No recent requests.</td>
                            </tr>
                        @else
                            @foreach($recentRequests as $request)
                                <tr>
                                    <td>{{ $request->request_number ?? 'SR-' . str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $request->vehicle_registration ?? '—' }}</td>
                                    <td>{{ trim(($request->vehicle_name ?? '') . ' ' . ($request->vehicle_model ?? '')) ?: '—' }}</td>
                                    <td>{{ $request->services->pluck('name')->join(', ') ?: '—' }}</td>
                                    <td>{{ $request->mechanic->name ?? 'Unassigned' }}</td>
                                    <td><span class="status-badge status-{{ $request->status }}">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($recentRequests->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-summary">
                    Showing <strong>{{ $recentRequests->firstItem() }}</strong>–<strong>{{ $recentRequests->lastItem() }}</strong>
                    of <strong>{{ $recentRequests->total() }}</strong> results
                </div>

                <nav class="pagination-nav">
                    <ul class="pagination-list">

                        {{-- Previous --}}
                        @if ($recentRequests->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-btn">&#8592; Prev</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-btn" href="{{ $recentRequests->previousPageUrl() }}">&#8592; Prev</a>
                            </li>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach (range(1, $recentRequests->lastPage()) as $page)
                            @if ($page == $recentRequests->currentPage())
                                <li class="page-item active">
                                    <span class="page-btn">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-btn" href="{{ $recentRequests->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($recentRequests->hasMorePages())
                            <li class="page-item">
                                <a class="page-btn" href="{{ $recentRequests->nextPageUrl() }}">Next &#8594;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-btn">Next &#8594;</span>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>

            <style>
            .pagination-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 16px 20px;
                border-top: 1px solid #e5e7eb;
                flex-wrap: wrap;
                gap: 12px;
                background: #fff;
            }
            .pagination-summary {
                font-size: 13px;
                color: #6b7280;
            }
            .pagination-summary strong {
                color: #1a2e1a;
                font-weight: 700;
            }
            .pagination-nav { display: flex; justify-content: flex-end; }
            .pagination-list {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
                gap: 4px;
                align-items: center;
            }
            .page-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 36px;
                height: 36px;
                padding: 0 12px;
                border-radius: 8px;
                border: 1.5px solid #e5e7eb;
                background: #fff;
                color: #374151;
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.18s ease;
                cursor: pointer;
                white-space: nowrap;
            }
            .page-btn:hover {
                border-color: #2d9b6f;
                background: #f0fdf4;
                color: #1a5c42;
            }
            .page-item.active .page-btn {
                background: linear-gradient(135deg, #1a5c42, #2d9b6f);
                border-color: transparent;
                color: #fff;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(45,155,111,0.3);
            }
            .page-item.disabled .page-btn {
                color: #c9cdd4;
                border-color: #f0f0f0;
                background: #fafafa;
                cursor: not-allowed;
                pointer-events: none;
            }
            @media (max-width: 640px) {
                .pagination-wrapper { flex-direction: column; align-items: center; }
                .pagination-summary { font-size: 12px; }
                .page-btn { min-width: 32px; height: 32px; font-size: 12px; padding: 0 8px; }
            }
            </style>
            @endif

        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Top Services — Revenue This Month</h2>
            </div>
            <div class="top-services-bars">
                @if($topServices->isEmpty())
                    <p class="empty-state">No data for this month.</p>
                @else
                    @foreach($topServices as $serviceName => $revenue)
                        <div class="bar-row">
                            <span class="bar-label">{{ $serviceName }}</span>
                            <div class="bar-track">
                                <div class="bar-fill"
                                    style="width: {{ $maxServiceRevenue > 0 ? ($revenue / $maxServiceRevenue) * 100 : 0 }}%"
                                    title="₱{{ number_format($revenue, 2) }}">
                                </div>
                            </div>
                            <span class="bar-amount">₱{{ number_format($revenue, 2) }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>

<!-- KPI Modal -->
<div id="kpiModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center; backdrop-filter:blur(4px);">
    <div style="background:white; border-radius:14px; padding:28px; width:90%; max-width:700px; max-height:85vh; overflow-y:auto; position:relative; box-shadow: 0 20px 60px rgba(0,0,0,0.25);">
        <button onclick="closeKpiModal()" style="position:absolute; top:14px; right:14px; border:none; background:#f3f4f6; border-radius:8px; width:32px; height:32px; font-size:18px; cursor:pointer; color:#6b7280;">×</button>
        <h2 id="kpiModalTitle" style="margin:0 0 6px; font-size:20px; font-weight:800; color:#1f2937;"></h2>
        <p id="kpiModalSubtitle" style="margin:0 0 20px; font-size:13px; color:#6b7280;"></p>
        <div id="kpiModalBody"></div>
    </div>
</div>

<!-- KPI Data JSON -->
<script>
const kpiData = {
    categories: @json($categoriesList),
    mechanics: @json($mechanicsList),
    services: @json($servicesList),
    completed: @json($completedList),
    total_requests: @json($allRequestsList),
    in_progress: @json($inProgressList),
    pending_payment: @json($pendingPaymentList),
    revenue: {
        monthly: {{ $revenueThisMonth }},
        lastMonth: {{ $revenueLastMonth }},
        breakdown: @json($revenueBreakdown)
    }
};
</script>

<!-- KPI Modal Functions -->
<script>
function openKpiModal(key) {
    const modal = document.getElementById('kpiModal');
    const title = document.getElementById('kpiModalTitle');
    const subtitle = document.getElementById('kpiModalSubtitle');
    const body = document.getElementById('kpiModalBody');
    const data = kpiData[key];

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    const table = (cols, rows, emptyMsg = 'No records found.') => {
        if (!rows || rows.length === 0)
            return `<p style="color:#9ca3af;text-align:center;padding:2rem;">${emptyMsg}</p>`;
        return `<div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead><tr>${cols.map(c =>
                    `<th style="padding:10px 12px;text-align:left;font-size:11px;
                    font-weight:800;color:#374151;text-transform:uppercase;
                    letter-spacing:.05em;border-bottom:2px solid #e5e7eb;
                    background:#f9fafb;white-space:nowrap;">${c}</th>`
                ).join('')}</tr></thead>
                <tbody>${rows}</tbody>
            </table></div>`;
    };

    const td = (val) =>
        `<td style="padding:10px 12px;border-bottom:1px solid #f3f4f6;
        color:#111;vertical-align:middle;">${val ?? '—'}</td>`;

    const badge = (label, color) =>
        `<span style="padding:3px 10px;border-radius:20px;font-size:11px;
        font-weight:700;background:${color.bg};color:${color.text};">${label}</span>`;

    const statusColor = (status) => {
        const map = {
            completed:   { bg:'#dcfce7', text:'#166534' },
            in_progress: { bg:'#dbeafe', text:'#1e40af' },
            pending:     { bg:'#fef9c3', text:'#854d0e' },
            cancelled:   { bg:'#fee2e2', text:'#991b1b' },
            approved:    { bg:'#e0e7ff', text:'#3730a3' },
        };
        return map[status] || { bg:'#f3f4f6', text:'#374151' };
    };

    const peso = (n) => '₱' + Number(n || 0).toLocaleString('en-PH',
        { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const configs = {
        categories: {
            title: 'Total Categories',
            subtitle: `${data.length} wheeler categories registered`,
            render: () => table(
                ['Category Name', 'Services Count'],
                data.map(c => `<tr>
                    ${td(`<strong>${c.name}</strong>`)}
                    ${td(c.services_count ?? 0)}
                </tr>`).join('')
            )
        },
        mechanics: {
            title: 'Active Mechanics',
            subtitle: `${data.length} mechanics currently active`,
            render: () => table(
               ['ID', 'Name', 'Specialization', 'Contact'],
                data.length === 0 ? [] : data.map(m => `<tr>
                    ${td(m.id)}
                    ${td(`<strong>${m.name}</strong>`)}
                    ${td(
                        m.specialization
                            ? `<span style="padding:3px 10px;border-radius:20px;font-size:11px;
                            font-weight:700;background:#e0f2fe;color:#0369a1;">
                            ${m.specialization}</span>`
                            : '—'
                    )}
                    ${td(`
                        <div style="font-size:12px;line-height:1.8;">
                            <div>📞 ${m.phone ?? '—'}</div>
                            <div>✉️ ${m.email ?? '—'}</div>
                        </div>
                    `)}
                </tr>`).join(''),
                'No active mechanics found.'
            )
        },
        services: {
            title: 'Available Services',
            subtitle: `${data.length} services currently active`,
            render: () => table(
                ['Service Name', 'Category', 'Price'],
                data.map(s => `<tr>
                    ${td(`<strong>${s.name}</strong>`)}
                    ${td(s.wheeler_category?.name ?? '—')}
                    ${td(peso(s.price))}
                </tr>`).join('')
            )
        },
        completed: {
            title: 'Completed Requests',
            subtitle: 'Last 20 completed service requests',
            render: () => table(
                ['Request #', 'Customer', 'Services', 'Mechanic', 'Completed'],
                data.map(r => `<tr>
                    ${td(`<strong>${r.request_number ?? 'SR-' + String(r.id).padStart(4,'0')}</strong>`)}
                    ${td(r.user?.name ?? '—')}
                    ${td(r.services?.map(s=>s.name).join(', ') || '—')}
                    ${td(r.mechanic_assignments?.[0]?.mechanic?.name ?? '—')}
                    ${td(r.completed_date ?? '—')}
                </tr>`).join('')
            )
        },
        total_requests: {
            title: 'Total Requests',
            subtitle: 'Last 20 service requests across all statuses',
            render: () => table(
                ['Request #', 'Customer', 'Services', 'Status', 'Date'],
                data.map(r => `<tr>
                    ${td(`<strong>${r.request_number ?? 'SR-' + String(r.id).padStart(4,'0')}</strong>`)}
                    ${td(r.user?.name ?? '—')}
                    ${td(r.services?.map(s=>s.name).join(', ') || '—')}
                    ${td(badge(r.status.replace('_',' '), statusColor(r.status)))}
                    ${td(r.created_at?.substring(0,10) ?? '—')}
                </tr>`).join('')
            )
        },
        revenue: {
            title: 'Revenue This Month',
            subtitle: `${peso(data.monthly)} collected · vs last month: ${peso(data.lastMonth)}`,
            render: () => {
                const diff = data.monthly - data.lastMonth;
                const diffColor = diff >= 0 ? '#059669' : '#dc2626';
                const diffLabel = (diff >= 0 ? '▲ +' : '▼ ') + peso(Math.abs(diff));
                return `
                <div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
                    <div style="flex:1;min-width:140px;padding:16px;background:#f0fdf4;
                    border-radius:10px;border-left:4px solid #10b981;">
                        <div style="font-size:11px;color:#6b7280;font-weight:700;
                        text-transform:uppercase;">This Month</div>
                        <div style="font-size:22px;font-weight:800;color:#059669;">
                        ${peso(data.monthly)}</div>
                    </div>
                    <div style="flex:1;min-width:140px;padding:16px;background:#f9fafb;
                    border-radius:10px;border-left:4px solid #d1d5db;">
                        <div style="font-size:11px;color:#6b7280;font-weight:700;
                        text-transform:uppercase;">Last Month</div>
                        <div style="font-size:22px;font-weight:800;color:#374151;">
                        ${peso(data.lastMonth)}</div>
                    </div>
                    <div style="flex:1;min-width:140px;padding:16px;background:#fff;
                    border-radius:10px;border-left:4px solid ${diffColor};">
                        <div style="font-size:11px;color:#6b7280;font-weight:700;
                        text-transform:uppercase;">Difference</div>
                        <div style="font-size:22px;font-weight:800;color:${diffColor};">
                        ${diffLabel}</div>
                    </div>
                </div>
                ${table(
                    ['Service', 'Revenue This Month'],
                    data.breakdown.map(s => `<tr>
                        ${td(`<strong>${s.name}</strong>`)}
                        ${td(`<span style="color:#059669;font-weight:700;">
                        ${peso(s.total)}</span>`)}
                    </tr>`).join(''),
                    'No revenue data for this month.'
                )}`;
            }
        }
    };

    const cfg = configs[key];
    if (!cfg) return;
    title.textContent = cfg.title;
    subtitle.textContent = cfg.subtitle;
    body.innerHTML = cfg.render();
}

function closeKpiModal() {
    document.getElementById('kpiModal').style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('kpiModalBody').innerHTML = '';
}

document.getElementById('kpiModal').addEventListener('click', function(e) {
    if (e.target === this) closeKpiModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeKpiModal();
});
</script>

@endsection