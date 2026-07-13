<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RoadFix — Jembatan Lapor Jalan Rusak</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0b0e14] text-white min-h-screen">

    <header class="border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-orange-500 text-[#0b0e14] font-extrabold text-sm">RF</span>
                <span>
                    <span class="block font-extrabold tracking-wide leading-none">RoadFix</span>
                    <span class="block text-[10px] text-slate-500 tracking-wider leading-none mt-1">JEMBATAN LAPOR JALAN RUSAK</span>
                </span>
            </a>

            <nav class="flex items-center gap-2 sm:gap-3 text-sm">
                @auth
                    <a href="{{ route('laporan.index') }}" class="px-3 py-2 text-slate-300 hover:text-white hidden sm:inline">Riwayat Laporan</a>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg border border-slate-700 text-slate-200 hover:border-slate-500">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-semibold">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg border border-slate-700 text-slate-200 hover:border-slate-500">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-semibold">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <section class="mt-6 mb-14 rounded-3xl bg-[#131722] border border-slate-800 flex flex-row items-center justify-between gap-6 px-6 py-10 sm:px-12 sm:py-14">
            <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm font-semibold tracking-[0.2em] text-orange-500 uppercase mb-4">
                    Layanan Pelaporan Infrastruktur Jalan
                </p>
                <h1 class="text-2xl sm:text-4xl xl:text-6xl font-extrabold leading-tight mb-6">
                    LIHAT JALAN RUSAK? LAPORKAN LEWAT SINI.
                </h1>
                <p class="text-slate-400 max-w-2xl mb-8 leading-relaxed">
                    RoadFix menjembatani laporan warga ke dinas terkait. Sertakan lokasi dan foto,
                    pantau statusnya sampai selesai ditangani — tanpa perlu datang ke kantor dinas.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('laporan.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-bold">
                        + Buat Laporan
                    </a>
                    <a href="{{ route('laporan.index') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-slate-700 text-slate-200 hover:border-slate-500 font-semibold">
                        Lacak Laporan Saya
                    </a>
                </div>
            </div>

            <div class="flex items-center justify-center w-16 h-16 sm:w-32 sm:h-32 lg:w-48 lg:h-48 shrink-0">
                <svg viewBox="0 0 440 340" class="w-full h-full" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <!-- garis jalan panjang -->
                    <rect x="120" y="90" width="30" height="200" rx="4" fill="#e6a817"/>
                    <!-- garis jalan putus-putus tengah -->
                    <rect x="200" y="90"  width="26" height="45" rx="4" fill="#e6a817"/>
                    <rect x="200" y="150" width="26" height="45" rx="4" fill="#e6a817"/>
                    <rect x="200" y="210" width="26" height="45" rx="4" fill="#e6a817"/>
                    <!-- garis jalan pendek -->
                    <rect x="280" y="90" width="30" height="120" rx="4" fill="#e6a817"/>
                    <!-- tanda plus -->
                    <path d="M255 235 h35 v-35 h30 v35 h35 v30 h-35 v35 h-30 v-35 h-35 z" fill="#e6a817"/>
                </svg>
            </div>
        </section>

        <section class="py-14 sm:py-16">
            <p class="text-xs font-semibold tracking-[0.2em] text-slate-500 uppercase mb-8">Bagaimana Alurnya</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#131722] border border-slate-800 rounded-xl p-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-400 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span> 01 MASUK
                    </span>
                    <h3 class="font-bold mb-2">Warga Melapor</h3>
                    <p class="text-sm text-slate-400">Isi lokasi, kategori kerusakan, dan foto bukti.</p>
                </div>

                <div class="bg-[#131722] border border-slate-800 rounded-xl p-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> 02 VERIFIKASI
                    </span>
                    <h3 class="font-bold mb-2">Dinas Meninjau</h3>
                    <p class="text-sm text-slate-400">Admin memeriksa kevalidan laporan yang masuk.</p>
                </div>

                <div class="bg-[#131722] border border-slate-800 rounded-xl p-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-500 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> 03 DIPROSES
                    </span>
                    <h3 class="font-bold mb-2">Penjadwalan Perbaikan</h3>
                    <p class="text-sm text-slate-400">Laporan diteruskan ke tim lapangan terkait.</p>
                </div>

                <div class="bg-[#131722] border border-slate-800 rounded-xl p-5">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 04 SELESAI
                    </span>
                    <h3 class="font-bold mb-2">Jalan Diperbaiki</h3>
                    <p class="text-sm text-slate-400">Status ditutup, warga mendapat notifikasi.</p>
                </div>
            </div>
        </section>

    </main>

    <footer class="border-t border-slate-800 py-6 mt-6">
        <p class="text-center text-xs text-slate-500">&copy; {{ date('Y') }} RoadFix. Semua hak dilindungi.</p>
    </footer>

</body>
</html>