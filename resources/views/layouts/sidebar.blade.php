<ul class="navbar-nav bg-white sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon text-primary">
            <i class="fas fa-building fa-2x"></i>
        </div>
        <div class="sidebar-brand-text mx-2">VMS</div>
    </a>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-home text-primary"></i>
            <span class="ml-2">Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Monitor -->
    <li class="nav-item {{ request()->routeIs('monitor.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('monitor.index') }}" target="_blank">
            <i class="fas fa-desktop text-primary"></i>
            <span class="ml-2">Live Monitor</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Laporan & Aktivitas</div>

    <!-- Nav Item - Laporan -->
    <li class="nav-item {{ request()->is('access-logs*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('access-logs.index') }}">
            <i class="fas fa-file-alt text-primary"></i>
            <span class="ml-2">Log Tap Pintu</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Manajemen Data</div>

    <!-- Nav Item - Manajemen Data (Collapsable) -->
    @php
        $isDataActive = request()->is('employees*') || request()->is('cards*') || request()->is('gates*');
    @endphp
    <li class="nav-item {{ $isDataActive ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData">
            <i class="fas fa-database text-primary"></i>
            <span class="ml-2">Database Sistem</span>
        </a>
        <div id="collapseData" class="collapse {{ $isDataActive ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('employees*') ? 'active' : '' }}" href="{{ route('employees.index') }}">Data Karyawan</a>
                <a class="collapse-item {{ request()->is('cards*') ? 'active' : '' }}" href="{{ route('cards.index') }}">Manajemen Kartu</a>
                <a class="collapse-item {{ request()->is('gates*') ? 'active' : '' }}" href="{{ route('gates.index') }}">Manajemen Gate</a>
            </div>
        </div>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">Pengaturan</div>

    <!-- Nav Item - Pengaturan (Collapsable) -->
    @php
        $isSettingsActive = request()->is('users*');
    @endphp
    <li class="nav-item {{ $isSettingsActive ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings">
            <i class="fas fa-cogs text-primary"></i>
            <span class="ml-2">Pengaturan Sistem</span>
        </a>
        <div id="collapseSettings" class="collapse {{ $isSettingsActive ? 'show' : '' }}" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">Manajemen Akun Login</a>
                {{-- Placeholder untuk menu pengaturan lainnya --}}
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 bg-light" id="sidebarToggle"></button>
    </div>

</ul>
