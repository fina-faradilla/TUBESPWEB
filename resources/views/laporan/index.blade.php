<<<<<<< HEAD
@extends('layouts.app')
@section('title', 'Riwayat Laporan Saya - RoadFix')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-accent text-xs font-bold uppercase tracking-wider mb-1">Portal Warga</p>
            <h1 class="text-2xl font-extrabold text-white">Riwayat Laporan Saya</h1>
            <p class="text-slate-400 text-sm mt-1">Daftar semua laporan kerusakan jalan yang pernah Anda kirim.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laporan.trashed') }}"
               class="bg-panel2 border border-border hover:border-accent text-slate-200 font-semibold px-4 py-2.5 rounded-lg text-sm whitespace-nowrap inline-flex items-center">
                <i class="fa-solid fa-trash mr-2"></i>Sampah
            </a>
            <a href="{{ route('laporan.create') }}"
               class="bg-accent hover:bg-accent2 text-panel font-bold px-5 py-2.5 rounded-lg text-sm transition-colors whitespace-nowrap inline-flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>Buat Laporan
            </a>
        </div>
    </div>

    {{-- ===== SEARCH & FILTER ===== --}}
    <form method="GET" action="{{ route('laporan.index') }}"
          class="flex flex-wrap items-center gap-3 mb-5">
        <div class="flex-1 min-w-[220px] relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Cari laporan, lokasi, atau ID..."
                   class="w-full bg-panel2 border border-border rounded-lg pl-9 pr-3 py-2.5 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-accent">
        </div>

        <select name="kategori" onchange="this.form.submit()"
                class="bg-panel2 border border-border rounded-lg px-3 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-accent">
            <option {{ $filterKategori === 'Semua Kategori' ? 'selected' : '' }}>Semua Kategori</option>
            @foreach ($kategoriOptions as $opt)
                <option value="{{ $opt }}" {{ $filterKategori === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()"
                class="bg-panel2 border border-border rounded-lg px-3 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-accent">
            <option {{ $filterStatus === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
            @foreach ($statusOptions as $opt)
                <option value="{{ $opt }}" {{ $filterStatus === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-panel border border-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-panel2 text-slate-400 text-left uppercase text-xs tracking-wider">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Foto</th>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Tingkat</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($laporans as $laporan)
                    <tr class="hover:bg-panel2/60">
                        <td class="px-5 py-3 text-slate-300 font-medium">
                            {{ $laporan->kode_laporan }}
                        </td>
                        <td class="px-5 py-3">
                            @if($laporan->foto)
                                <img src="{{ asset('storage/'.$laporan->foto) }}" class="w-12 h-12 object-cover rounded-md border border-border">
                            @else
                                <div class="w-12 h-12 rounded-md bg-panel2 border border-border flex items-center justify-center text-slate-500">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-100 font-medium">{{ $laporan->judul }}</td>
                        <td class="px-5 py-3 text-slate-300">{{ $laporan->kategori }}</td>
                        <td class="px-5 py-3">
                            @php
                                $badge = match($laporan->tingkat) {
                                    'Berat' => 'bg-red-500/15 text-red-400 border-red-500/40',
                                    'Sedang' => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
                                    default => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
                                };
                            @endphp
                            <span class="text-xs font-semibold px-2 py-1 rounded-full border {{ $badge }}">{{ $laporan->tingkat }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $laporan->alamat }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full border {{ $laporan->statusBadgeColor() }}">
                                {{ $laporan->status ?? 'Menunggu Verifikasi' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('laporan.show', $laporan->id) }}" class="text-slate-300 hover:text-accent" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if ($laporan->status === 'Menunggu Verifikasi')
                                <a href="{{ route('laporan.edit', $laporan->id) }}" class="text-slate-300 hover:text-accent" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            @else
                                <span class="text-slate-600 cursor-not-allowed" title="Laporan sudah diverifikasi, tidak bisa diedit">
                                    <i class="fa-solid fa-pen"></i>
                                </span>
                            @endif

                            @if ($laporan->status === 'Menunggu Verifikasi')
                                <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus laporan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-300 hover:text-red-400" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-600 cursor-not-allowed" title="Laporan sudah diverifikasi, tidak bisa dihapus">
                                    <i class="fa-solid fa-trash"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                            Belum ada laporan. <a href="{{ route('laporan.create') }}" class="text-accent underline">Buat laporan pertama Anda</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($laporans instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="mt-5">{{ $laporans->links('vendor.pagination.custom-dark') }}</div>
    @endif
@endsection
=======
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
>>>>>>> origin/fina
