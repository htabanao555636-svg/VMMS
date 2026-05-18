@extends('Layouts.user')

@section('content')

<style>
/* ── Hide old layout navbar on this page only ── */
nav.navbar.navbar-expand-lg { display: none !important; }

/* ── Reset & base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --green: #1D9E75;
    --green-dark: #0F6E56;
    --green-light: #E1F5EE;
    --green-mid: #9FE1CB;
    --text: #111111;
    --text-muted: #6B7280;
    --text-light: #9CA3AF;
    --border: #E5E7EB;
    --border-mid: #D1D5DB;
    --surface: #FFFFFF;
    --surface-alt: #F9FAFB;
    --surface-dark: #F3F4F6;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
    --radius: 10px;
    --radius-sm: 7px;
    --font: 'Poppins', system-ui, sans-serif;
}

body { font-family: var(--font); background: var(--surface-alt); color: var(--text); }

/* ── TOPBAR ── */
.cu-topbar {
    position: sticky;
    top: 0;
    z-index: 999;
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 2rem;
    box-shadow: var(--shadow-sm);
}

.cu-topbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cu-logo-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--green);
    display: flex;
    align-items: center;
    justify-content: center;
}

.cu-logo-icon svg {
    width: 18px;
    height: 18px;
    fill: white;
}

.cu-logo-text {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.3px;
}

.cu-logo-text span { color: var(--green); }

.cu-topbar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cu-nav-link {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-muted);
    text-decoration: none;
    padding: 6px 12px;
    border-radius: var(--radius-sm);
    transition: background 0.2s, color 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.cu-nav-link:hover {
    background: var(--surface-dark);
    color: var(--text);
}

.cu-nav-link i { font-size: 13px; }

.cu-user-pill {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--green-light);
    border: 1px solid var(--green-mid);
    border-radius: 50px;
    padding: 5px 14px 5px 5px;
    cursor: pointer;
    position: relative;
}

.cu-user-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--green);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: white;
}

.cu-user-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--green-dark);
}

.cu-user-chevron {
    font-size: 11px;
    color: var(--green-dark);
}

.cu-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    min-width: 200px;
    box-shadow: var(--shadow-md);
    display: none;
    z-index: 9999;
    overflow: hidden;
}

.cu-dropdown.open { display: block; }

.cu-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    font-size: 13px;
    color: var(--text);
    text-decoration: none;
    transition: background 0.15s;
    border: none;
    background: none;
    width: 100%;
    cursor: pointer;
    font-family: var(--font);
}

.cu-dropdown-item:hover { background: var(--surface-alt); }
.cu-dropdown-item i { font-size: 13px; color: var(--text-muted); width: 16px; }
.cu-dropdown-divider { height: 1px; background: var(--border); margin: 4px 0; }
.cu-dropdown-item.logout { color: #DC2626; }
.cu-dropdown-item.logout i { color: #DC2626; }
.cu-dropdown-item.logout:hover { background: #FEF2F2; }

/* ── HERO ── */
.cu-hero {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 2.5rem 2rem;
}

.cu-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
}

.cu-hero-left { flex: 1; min-width: 260px; }

.cu-welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.cu-welcome-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--green);
}

.cu-hero-title {
    font-size: clamp(20px, 3vw, 28px);
    font-weight: 700;
    color: var(--text);
    line-height: 1.25;
    margin-bottom: 0.5rem;
}

.cu-hero-title span { color: var(--green); }

.cu-hero-sub {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 1.25rem;
    max-width: 400px;
}

.cu-send-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--green);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font);
    transition: background 0.2s, transform 0.15s;
}

.cu-send-btn:hover { background: var(--green-dark); transform: translateY(-1px); }
.cu-send-btn:active { transform: translateY(0); }
.cu-send-btn i { font-size: 14px; }

.cu-hero-stats {
    display: flex;
    gap: 0;
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    flex-shrink: 0;
}

.cu-stat {
    padding: 1rem 1.5rem;
    text-align: center;
    border-right: 1px solid var(--border);
}

.cu-stat:last-child { border-right: none; }

.cu-stat-num {
    font-size: 22px;
    font-weight: 700;
    color: var(--green);
    line-height: 1;
    margin-bottom: 4px;
}

.cu-stat-label {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
    white-space: nowrap;
}

/* ── MAIN BODY ── */
.cu-body {
    max-width: 1100px;
    margin: 0 auto;
    padding: 2rem;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 1.5rem;
    align-items: start;
}

/* ── LEFT PANEL ── */
.cu-panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.cu-panel-header {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 8px;
}

.cu-panel-title {
    font-size: 12px;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.cu-cat-list { padding: 8px; }

.cu-cat-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 9px 12px;
    border: none;
    border-radius: var(--radius-sm);
    background: transparent;
    font-family: var(--font);
    font-size: 13px;
    font-weight: 500;
    color: var(--text-muted);
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    text-align: left;
}

.cu-cat-btn:hover {
    background: var(--surface-alt);
    color: var(--text);
}

.cu-cat-btn.active {
    background: var(--green-light);
    color: var(--green-dark);
    font-weight: 600;
}

.cu-cat-btn i { font-size: 13px; width: 16px; }

.cu-cat-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--green);
    margin-left: auto;
    display: none;
}

.cu-cat-btn.active .cu-cat-dot { display: block; }

/* ── RIGHT PANEL ── */
.cu-right { display: flex; flex-direction: column; gap: 1rem; }

.cu-search-bar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: var(--shadow-sm);
}

.cu-search-bar i { color: var(--text-light); font-size: 14px; }

.cu-search-input {
    flex: 1;
    border: none;
    outline: none;
    font-family: var(--font);
    font-size: 14px;
    color: var(--text);
    background: transparent;
}

.cu-search-input::placeholder { color: var(--text-light); }

.cu-results-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}

/* ── SERVICES GRID ── */
.cu-services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 12px;
}

.cu-service-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.1rem 1.1rem 1rem;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.cu-service-card:hover {
    border-color: var(--green);
    box-shadow: 0 4px 16px rgba(29,158,117,0.12);
    transform: translateY(-2px);
}

.cu-service-tag {
    display: inline-flex;
    align-items: center;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 10px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    width: fit-content;
}

.cu-service-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.3;
    margin-top: 2px;
}

.cu-service-desc {
    font-size: 12px;
    color: var(--text-muted);
    line-height: 1.5;
    flex: 1;
}

.cu-service-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 6px;
    padding-top: 10px;
    border-top: 1px solid var(--border);
}

.cu-service-price {
    font-size: 16px;
    font-weight: 700;
    color: var(--green);
}

.cu-service-more {
    font-size: 11px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 4px;
}

.cu-service-more i { font-size: 10px; }

/* Empty state */
.cu-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
}

.cu-empty i { font-size: 32px; color: var(--border-mid); margin-bottom: 10px; }
.cu-empty p { font-size: 14px; }

/* Hidden card */
.cu-service-card.cu-hidden { display: none; }

/* ── FOOTER ── */
.cu-footer {
    border-top: 1px solid var(--border);
    background: var(--surface);
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 2rem;
}

.cu-footer-text { font-size: 12px; color: var(--text-light); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .cu-body { grid-template-columns: 1fr; padding: 1rem; }
    .cu-hero { padding: 1.5rem 1rem; }
    .cu-hero-inner { flex-direction: column; }
    .cu-hero-stats { width: 100%; }
    .cu-stat { flex: 1; }
    .cu-topbar { padding: 0 1rem; }
    .cu-nav-link span { display: none; }
}
</style>

{{-- ── TOPBAR ── --}}
<nav class="cu-topbar">
    <div class="cu-topbar-left">
        <div class="cu-logo-icon">
            <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>
        <span class="cu-logo-text">VM<span>MS</span></span>
    </div>
    <div class="cu-topbar-right">
        <a href="{{ route('customer.payments') }}" class="cu-nav-link">
            <i class="fas fa-receipt"></i><span>Payment History</span>
        </a>
        <a href="{{ route('customer.services') }}" class="cu-nav-link">
            <i class="fas fa-tools"></i><span>My Services</span>
        </a>
        <a href="{{ route('customer.payables') }}" class="cu-nav-link">
            <i class="fas fa-credit-card"></i><span>My Payables</span>
        </a>
        <div class="cu-user-pill" onclick="toggleCuDropdown(event)">
            <div class="cu-user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="cu-user-name">{{ Auth::user()->name }}</span>
            <i class="fas fa-chevron-down cu-user-chevron"></i>
            <div class="cu-dropdown" id="cuDropdown">
                <a href="{{ route('customer.payments') }}" class="cu-dropdown-item">
                    <i class="fas fa-receipt"></i> Payment History
                </a>
                <a href="{{ route('customer.services') }}" class="cu-dropdown-item">
                    <i class="fas fa-tools"></i> My Services
                </a>
                <a href="{{ route('customer.payables') }}" class="cu-dropdown-item">
                    <i class="fas fa-credit-card"></i> My Payables
                </a>
                <div class="cu-dropdown-divider"></div>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="cu-dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="cu-hero">
    <div class="cu-hero-inner">
        <div class="cu-hero-left">
            <div class="cu-welcome-badge">
                <span class="cu-welcome-badge-dot"></span>
                Customer Dashboard
            </div>
            <h1 class="cu-hero-title">
                Welcome back, <span>{{ Auth::user()->name }}</span>!
            </h1>
            <p class="cu-hero-sub">
                Browse our services below and submit a request. We'll take care of the rest.
            </p>
            <button class="cu-send-btn" id="sendRequestBtn">
                <i class="fas fa-plus"></i>
                New Service Request
            </button>
        </div>
        <div class="cu-hero-stats">
            <div class="cu-stat">
                <div class="cu-stat-num">{{ $services->count() }}</div>
                <div class="cu-stat-label">Services available</div>
            </div>
            <div class="cu-stat">
                <div class="cu-stat-num">{{ $categories->count() }}</div>
                <div class="cu-stat-label">Categories</div>
            </div>
            <div class="cu-stat">
                <div class="cu-stat-num">24h</div>
                <div class="cu-stat-label">Avg turnaround</div>
            </div>
        </div>
    </div>
</section>

{{-- ── MAIN BODY ── --}}
<div class="cu-body">

    {{-- LEFT: Category Filter --}}
    <aside>
        <div class="cu-panel">
            <div class="cu-panel-header">
                <i class="fas fa-filter" style="font-size:12px;color:var(--text-muted)"></i>
                <span class="cu-panel-title">Filter by type</span>
            </div>
            <div class="cu-cat-list">
                <button class="cu-cat-btn active" data-category="all" onclick="filterCategory(this, 'all')">
                    <i class="fas fa-th-large"></i>
                    All Services
                    <span class="cu-cat-dot"></span>
                </button>
                @forelse($categories ?? [] as $category)
                <button class="cu-cat-btn" data-category="{{ $category->name }}" onclick="filterCategory(this, '{{ $category->name }}')">
                    <i class="fas fa-wrench"></i>
                    {{ $category->name }}
                    <span class="cu-cat-dot"></span>
                </button>
                @empty
                <p style="font-size:12px;color:var(--text-muted);padding:8px 12px;">No categories yet</p>
                @endforelse
            </div>
        </div>
    </aside>

    {{-- RIGHT: Services --}}
    <div class="cu-right">

        {{-- Search bar --}}
        <div class="cu-search-bar">
            <i class="fas fa-search"></i>
            <input
                type="text"
                class="cu-search-input"
                id="cuServiceSearch"
                placeholder="Search services..."
                oninput="filterSearch(this.value)"
            >
            <span class="cu-results-label" id="cuResultsLabel">
                {{ count($services ?? []) }} services
            </span>
        </div>

        {{-- Services grid --}}
        <div class="cu-services-grid" id="cuServicesGrid">
            @if($services && count($services) > 0)
                @foreach($services as $service)
                    @if($service && isset($service->name))
                    <div class="cu-service-card"
                         data-name="{{ strtolower($service->name) }}"
                         data-category="{{ $service->wheelerCategory?->name ?? 'General' }}"
                         data-service="{{ $service->name }}"
                         data-description="{{ $service->description ?? 'Professional ' . $service->name . ' service' }}"
                         data-price="{{ number_format($service->price ?? 0, 2) }}"
                         onclick="openServiceModal(this)">
                        <span class="cu-service-tag">
                            {{ $service->wheelerCategory?->name ?? 'General' }}
                        </span>
                        <div class="cu-service-name">{{ $service->name }}</div>
                        <div class="cu-service-desc">
                            {{ $service->description ?? 'Professional maintenance service' }}
                        </div>
                        <div class="cu-service-footer">
                            <span style="flex:1;"></span>
                            <span class="cu-service-price">₱{{ number_format($service->price ?? 0, 2) }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="cu-empty">
                    <i class="fas fa-tools"></i>
                    <p>No services available at the moment.</p>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- ── FOOTER ── --}}
<footer class="cu-footer">
    <span class="cu-footer-text">VMMS · Vehicle Maintenance Management System</span>
    <span class="cu-footer-text">Davao, Philippines · &copy; {{ date('Y') }}</span>
</footer>

<script>
function toggleCuDropdown(e) {
    e.stopPropagation();
    document.getElementById('cuDropdown').classList.toggle('open');
}
document.addEventListener('click', function() {
    const d = document.getElementById('cuDropdown');
    if (d) d.classList.remove('open');
});

function filterSearch(val) {
    const q = val.toLowerCase().trim();
    const cards = document.querySelectorAll('.cu-service-card');
    let visible = 0;
    cards.forEach(c => {
        const name = c.dataset.name || '';
        const desc = (c.querySelector('.cu-service-desc')?.textContent || '').toLowerCase();
        const match = name.includes(q) || desc.includes(q);
        c.classList.toggle('cu-hidden', !match);
        if (match) visible++;
    });
    const label = document.getElementById('cuResultsLabel');
    if (label) label.textContent = visible + ' service' + (visible !== 1 ? 's' : '');
}

function filterCategory(btn, category) {
    document.querySelectorAll('.cu-cat-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const cards = document.querySelectorAll('.cu-service-card');
    let visible = 0;
    cards.forEach(c => {
        const match = category === 'all' || c.dataset.category === category;
        c.classList.toggle('cu-hidden', !match);
        if (match) visible++;
    });
    const label = document.getElementById('cuResultsLabel');
    if (label) label.textContent = visible + ' service' + (visible !== 1 ? 's' : '');
    const search = document.getElementById('cuServiceSearch');
    if (search) search.value = '';
}

function openServiceModal(card) {
    document.getElementById('serviceModalTitle').textContent       = card.dataset.service;
    document.getElementById('serviceModalDescription').textContent = card.dataset.description;
    document.getElementById('serviceModalTag').textContent         = card.dataset.category;
    document.getElementById('serviceModalPrice').textContent       = '₱' + card.dataset.price;

    const overlay = document.getElementById('serviceModalOverlay');
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeServiceModal() {
    document.getElementById('serviceModalOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeServiceModal();
});
</script>

@endsection