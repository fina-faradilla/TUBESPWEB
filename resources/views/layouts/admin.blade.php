<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Portal Admin') — RoadFix</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @stack('styles')
</head>
<body>
<div class="admin-layout">

    {{-- Overlay untuk mobile, klik di luar sidebar untuk menutup --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">

    {{-- Tombol collapse (khusus desktop) --}}
    <button type="button" class="sidebar-collapse-btn" id="sidebarCollapseBtn" aria-label="Collapse sidebar">
        <i class="fa-solid fa-chevron-left" id="sidebarCollapseIcon"></i>
    </button>

    <div class="sidebar-logo">
        <div class="sidebar-logo-badge">RF</div>
        <div>
            <div class="sidebar-logo-title">ROADFIX</div>
            <div class="sidebar-logo-sub">JEMBATAN LAPOR JALAN<br>RUSAK</div>
        </div>
    </div>

    <div class="sidebar-section-label">PORTAL ADMIN / DINAS</div>
    <a href="{{ route('admin.dashboard') }}"
       class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="dot"></span>
        <i class="fa-solid fa-chart-line nav-icon"></i>
        <span class="nav-label">Dashboard</span>
    </a>
    <a href="{{ route('admin.laporan.index') }}"
       class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
        <span class="dot"></span>
        <i class="fa-solid fa-clipboard-list nav-icon"></i>
        <span class="nav-label">Kelola Laporan</span>
    </a>
    <a href="{{ route('admin.kategori.index') }}"
       class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
        <span class="dot"></span>
        <i class="fa-solid fa-tags nav-icon"></i>
        <span class="nav-label">Kelola Kategori</span>
    </a>

    {{-- ===== LOGOUT ===== --}}
    <div class="sidebar-footer">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
        <a href="#" class="nav-item nav-item-logout"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-right-from-bracket nav-icon"></i>
            <span class="nav-label">Logout</span>
        </a>
    </div>
</aside>

    {{-- ===== MAIN ===== --}}
    <div class="admin-main">
        <div class="top-bar">
            <div class="top-bar-leading">
                {{-- Tombol hamburger --}}
                <button type="button" class="hamburger-btn" id="sidebarToggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div>
                    <div class="breadcrumb">PORTAL ADMIN / DINAS</div>
                    <div class="page-title">@yield('page-title')</div>
                </div>
            </div>
            <div class="top-bar-trailing">
                @yield('top-bar-trailing')
            </div>
        </div>

        <div class="content-area">
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const collapseBtn = document.getElementById('sidebarCollapseBtn');
    const collapseIcon = document.getElementById('sidebarCollapseIcon');

    // ---- Mobile: slide in/out (sudah ada sebelumnya) ----
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-open');
        sidebarOverlay.classList.toggle('active');
    });

    sidebarOverlay.addEventListener('click', () => {
        sidebar.classList.remove('sidebar-open');
        sidebarOverlay.classList.remove('active');
    });

    // ---- Desktop: collapse ke mode ikon ----
    function setCollapsed(collapsed) {
        sidebar.classList.toggle('collapsed', collapsed);
        collapseIcon.classList.toggle('fa-chevron-left', !collapsed);
        collapseIcon.classList.toggle('fa-chevron-right', collapsed);
        localStorage.setItem('admin-sidebar-collapsed', collapsed ? '1' : '0');
    }

    setCollapsed(localStorage.getItem('admin-sidebar-collapsed') === '1');

    collapseBtn.addEventListener('click', () => {
        setCollapsed(!sidebar.classList.contains('collapsed'));
    });
</script>

@stack('scripts')
</body>
</html>
