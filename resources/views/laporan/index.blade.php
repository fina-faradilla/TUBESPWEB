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
                            RF-{{ str_pad($laporan->id + 139, 4, '0', STR_PAD_LEFT) }}
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
                            <a href="{{ route('laporan.edit', $laporan->id) }}" class="text-slate-300 hover:text-accent" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus laporan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-red-400" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
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
        <div class="mt-5">{{ $laporans->links() }}</div>
    @endif
@endsection
