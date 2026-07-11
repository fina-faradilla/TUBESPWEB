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
    <aside class="w-64 bg-panel border-r border-border min-h-screen flex-shrink-0">
        <div class="flex items-center gap-3 px-5 py-6 border-b border-border">
            <div class="w-9 h-9 rounded-md bg-accent flex items-center justify-center font-extrabold text-panel">RF</div>
            <div>
                <div class="font-extrabold tracking-wide text-white leading-tight">ROADFIX</div>
                <div class="text-[10px] text-slate-400 leading-tight uppercase">Lapor Jalan Rusak Cepat</div>
            </div>
        </div>

        <nav class="px-3 py-5 space-y-6">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase px-3 mb-2 tracking-wider">Halaman Publik</p>
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }} flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Beranda
                </a>
                <a href="{{ Route::has('login') ? route('login') : '#' }}" class="nav-link flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                   <i class="fa-solid fa-circle text-[6px]"></i> Masuk / Daftar
                </a>

            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase px-3 mb-2 tracking-wider">Portal Warga</p>
                <a href="{{ route('laporan.create') }}" class="nav-link {{ request()->routeIs('laporan.create') ? 'active' : '' }} flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Buat Laporan
                </a>
                <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }} flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Riwayat Laporan Saya
                </a>
                <a href="#" class="nav-link {{ request()->routeIs('laporan.show') ? 'active' : '' }} flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Detail Laporan
                </a>
            </div>

            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase px-3 mb-2 tracking-wider">Portal Admin / Dinas</p>
                <a href="#" class="nav-link flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Dashboard
                </a>
                <a href="{{ route('laporan.index') }}" class="nav-link flex items-center gap-2 px-3 py-2 rounded-md text-sm">
                    <i class="fa-solid fa-circle text-[6px]"></i> Kelola Laporan
                </a>
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
    @stack('scripts')
</body>
</html>
