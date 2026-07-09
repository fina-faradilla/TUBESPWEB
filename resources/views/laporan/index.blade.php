<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Laporan Saya — JalanKita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0b0e14] text-white min-h-screen">
    <header class="border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-orange-500 text-[#0b0e14] font-extrabold text-sm">JK</span>
                <span class="font-extrabold tracking-wide">JALANKITA</span>
            </a>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('laporan.create') }}" class="px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-400 text-[#0b0e14] font-semibold">+ Buat Laporan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-4 py-2 rounded-lg border border-slate-700 text-slate-200 hover:border-slate-500">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
        <p class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase mb-2">Portal Warga</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold mb-6">Riwayat Laporan Saya</h1>

        <div class="bg-[#131722] border border-slate-800 rounded-xl p-8 text-slate-400">
            Halo, {{ auth()->user()->name }}. Belum ada data laporan untuk ditampilkan di sini —
            halaman ini akan menampilkan daftar laporan Anda beserta status (dengan pencarian &amp; paginasi)
            setelah fitur CRUD laporan dibuat.
        </div>
    </main>
</body>
</html>