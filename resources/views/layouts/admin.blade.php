<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ Auth::user()->role === 'admin' ? 'Admin Dashboard' : 'Staff Portal' }} - Vehicle Maintenance Management System</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Easy Fix Garage Logo" class="logo-img">
            <span class="logo-text">Easy Fix Garage</span>
        </div>

        <nav class="sidebar-nav">
            <div class="menu-section">
                <ul class="menu-list">
                    <!-- Dashboard (Both Admin & Staff) -->
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Request::is('staff/dashboard') ? 'active' : '' }}">
                            <a href="{{ route('staff.dashboard') }}" class="menu-link">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                    <!-- Mechanic List (Both Admin & Staff) -->
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-item {{ Request::is('admin/mechanics*') ? 'active' : '' }}">
                            <a href="{{ route('admin.mechanics') }}" class="menu-link">
                                <i class="fas fa-users"></i>
                                <span>Mechanic List</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Request::is('staff/mechanics*') ? 'active' : '' }}">
                            <a href="{{ route('staff.mechanics') }}" class="menu-link">
                                <i class="fas fa-users"></i>
                                <span>Mechanic List</span>
                            </a>
                        </li>
                    @endif

                    <!-- Service Request (Both Admin & Staff) -->
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-item {{ Request::is('admin/service-requests*') ? 'active' : '' }}">
                            <a href="{{ route('admin.service-request') }}" class="menu-link">
                                <i class="fas fa-tools"></i>
                                <span>Service Request</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Request::is('staff/service-requests*') ? 'active' : '' }}">
                            <a href="{{ route('staff.service-request') }}" class="menu-link">
                                <i class="fas fa-tools"></i>
                                <span>Service Request</span>
                            </a>
                        </li>
                    @endif

                    <!-- Billing Management (Both Admin & Staff) -->
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-item {{ Request::is('admin/billing*') ? 'active' : '' }}">
                            <a href="{{ route('admin.billing') }}" class="menu-link">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Billing Management</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Request::is('staff/billing*') ? 'active' : '' }}">
                            <a href="{{ route('staff.billing') }}" class="menu-link">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Billing Management</span>
                            </a>
                        </li>
                    @endif

                    <!-- Report (Both Admin & Staff) -->
                    @if (Auth::user()->role === 'admin')
                        <li class="menu-item {{ Request::is('admin/report*') ? 'active' : '' }}">
                            <a href="{{ route('admin.report') }}" class="menu-link">
                                <i class="fas fa-file-alt"></i>
                                <span>Report</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item {{ Request::is('staff/report*') ? 'active' : '' }}">
                            <a href="{{ route('staff.report') }}" class="menu-link">
                                <i class="fas fa-file-alt"></i>
                                <span>Report</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Maintenance Section (Admin Only) -->
            @if (Auth::user()->role === 'admin')
                <div class="menu-section">
                    <div class="section-label">Maintenance</div>
                    <ul class="menu-list">
                        <li class="menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories') }}" class="menu-link">
                                <i class="fas fa-list"></i>
                                <span>Category List</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Request::is('admin/services*') ? 'active' : '' }}">
                            <a href="{{ route('admin.services') }}" class="menu-link">
                                <i class="fas fa-cogs"></i>
                                <span>Service List</span>
                            </a>
                        </li>
                        <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}" class="menu-link">
                                <i class="fas fa-user-tie"></i>
                                <span>User List</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>
    </aside>

    <!-- Main Container -->
    <div class="main-wrapper">
        <header class="top-header">
            <div class="header-left" style="display:flex; align-items:center; gap:8px;">
                <button class="hamburger-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <h2 class="portal-title">
                    @if (Auth::user()->role === 'admin')
                        Administrator Portal
                    @else
                        Staff Portal
                    @endif
                </h2>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <span class="username">
                        {{ Auth::user()->name ?? (Auth::user()->role === 'admin' ? 'Admin' : 'Staff') }}
                    </span>
                    <div class="user-dropdown">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @yield('modals')

    @yield('scripts')
    

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar       = document.querySelector('.sidebar');
    const overlay       = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        sidebarToggle.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        sidebarToggle.classList.remove('active');
        document.body.style.overflow = '';
    }

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.menu-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) closeSidebar();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeSidebar();
    });
    </script>

</body>
</html>
