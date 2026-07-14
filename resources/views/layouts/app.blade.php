<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RoadFix')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/detail-laporan.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        base: '#0f1420',
                        panel: '#161d2e',
                        panel2: '#1b2436',
                        border: '#2a3348',
                        accent: '#f5a623',
                        accent2: '#f7b733',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f1420; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #2a3348; border-radius: 4px; }
        .nav-link { transition: all .15s ease; }
        .nav-link:hover { background-color: #1f2740; }
        .nav-link.active { background-color: #f5a623; color: #14181f; font-weight: 700; }
    </style>
    @stack('styles')
</head>
<body class="text-slate-200 font-sans min-h-screen flex">

<!-- SIDEBAR -->
<aside id="sidebar" class="relative bg-panel border-r border-border min-h-screen flex-shrink-0 transition-all duration-300 ease-in-out" style="width: 16rem;">

    <!-- Tombol toggle (panah) -->
    <button id="sidebar-toggle"
            class="absolute -right-3 top-8 w-6 h-6 bg-accent hover:bg-accent2 text-panel rounded-full flex items-center justify-center shadow-md z-10 transition-transform duration-300">
        <i id="sidebar-toggle-icon" class="fa-solid fa-chevron-left text-xs"></i>
    </button>

    <div class="flex items-center gap-3 px-5 py-6 border-b border-border overflow-hidden">
        <div class="w-9 h-9 rounded-md bg-accent flex items-center justify-center font-extrabold text-panel flex-shrink-0">RF</div>
        <div id="sidebar-brand-text" class="whitespace-nowrap">
            <div class="font-extrabold tracking-wide text-white leading-tight">ROADFIX</div>
            <div class="text-[10px] text-slate-400 leading-tight uppercase">Lapor Jalan Rusak Cepat</div>
        </div>
    </div>

    <nav class="px-3 py-5 flex flex-col justify-between" style="min-height: calc(100vh - 89px);">
    <div>
        <p id="sidebar-section-label" class="text-[11px] font-bold text-slate-500 uppercase px-3 mb-2 tracking-wider whitespace-nowrap overflow-hidden">Portal Warga</p>

        <a href="{{ route('laporan.create') }}" title="Buat Laporan"
           class="nav-link {{ request()->routeIs('laporan.create') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-md text-sm whitespace-nowrap overflow-hidden">
            <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
            <i class="fa-solid fa-file-circle-plus w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-label">Buat Laporan</span>
        </a>
        <a href="{{ route('laporan.index') }}" title="Riwayat Laporan Saya"
           class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-md text-sm whitespace-nowrap overflow-hidden">
            <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
            <i class="fa-solid fa-clock-rotate-left w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-label">Riwayat Laporan Saya</span>
        </a>
        <a href="#" title="Detail Laporan"
           class="nav-link {{ request()->routeIs('laporan.show') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-md text-sm whitespace-nowrap overflow-hidden">
            <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
            <i class="fa-solid fa-file-lines w-4 text-center flex-shrink-0"></i>
            <span class="sidebar-label">Detail Laporan</span>
        </a>
    </div>

        <!-- Tombol Logout -->
        <div class="border-t border-border pt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout"
                        class="w-full nav-link flex items-center gap-2 px-3 py-2 rounded-md text-sm whitespace-nowrap overflow-hidden text-red-400 hover:bg-red-500/10">
                    <i class="fa-solid fa-right-from-bracket text-sm flex-shrink-0"></i>
                    <span class="sidebar-label">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 px-10 py-8">
        @if(session('success'))
            <div class="mb-5 px-4 py-3 rounded-md bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 text-sm">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 px-4 py-3 rounded-md bg-red-500/10 border border-red-500/40 text-red-300 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const toggleIcon = document.getElementById('sidebar-toggle-icon');
        const brandText = document.getElementById('sidebar-brand-text');
        const sectionLabel = document.getElementById('sidebar-section-label');
        const labels = document.querySelectorAll('.sidebar-label');

        function setSidebarState(collapsed) {
            if (collapsed) {
                sidebar.style.width = '4.5rem';
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                brandText.style.display = 'none';
                sectionLabel.style.display = 'none';
                labels.forEach(el => el.style.display = 'none');
            } else {
                sidebar.style.width = '16rem';
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                brandText.style.display = '';
                sectionLabel.style.display = '';
                labels.forEach(el => el.style.display = '');
            }
            localStorage.setItem('sidebar-collapsed', collapsed ? '1' : '0');
        }

        // Terapkan state tersimpan saat halaman dimuat
        const savedState = localStorage.getItem('sidebar-collapsed') === '1';
        setSidebarState(savedState);

        toggleBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.style.width === '4.5rem';
            setSidebarState(!isCollapsed);
        });
    </script>

    @stack('scripts')
</body>
</html>
    @stack('scripts')
</body>
</html>
