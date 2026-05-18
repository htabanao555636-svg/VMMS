<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" class="logo-img">
        <span class="logo-text">Easy Fix Garage</span>
    </div>

    <nav class="sidebar-nav">
        <ul class="menu-list">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.mechanics') }}"><i class="fas fa-users"></i> Mechanic List</a></li>
            <li><a href="{{ route('admin.service-request') }}"><i class="fas fa-tools"></i> Service Request</a></li>
            <li><a href="{{ route('admin.report') }}"><i class="fas fa-file-alt"></i> Report</a></li>
        </ul>

        <div class="section-label">Maintenance</div>
        <ul class="menu-list">
            <li><a href="{{ route('admin.category') }}"><i class="fas fa-list"></i> Category</a></li>
            <li><a href="{{ route('admin.service') }}"><i class="fas fa-cogs"></i> Service</a></li>
            <li><a href="{{ route('admin.users') }}"><i class="fas fa-user-tie"></i> Users</a></li>
        </ul>

        <ul class="menu-list">
            <li><a href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </nav>
</aside>