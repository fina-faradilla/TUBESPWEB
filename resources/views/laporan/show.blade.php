@extends('layouts.app')
@section('title', 'Detail Laporan - RoadFix')

@section('content')
    <p class="text-accent text-xs font-bold uppercase tracking-wider mb-1">
        Laporan #RF-{{ str_pad($laporan->id + 139, 4, '0', STR_PAD_LEFT) }}
    </p>

    <div class="flex items-start justify-between mb-1">
        <h1 class="text-2xl font-extrabold text-white uppercase">{{ $laporan->judul }}</h1>
        <div class="flex gap-2 whitespace-nowrap">
            @if ($laporan->status === 'Menunggu Verifikasi')
        <a href="{{ route('laporan.edit', $laporan->id) }}"
           class="bg-panel2 border border-border hover:border-accent text-slate-200 font-semibold px-4 py-2 rounded-lg text-sm">
        <i class="fa-solid fa-pen mr-2"></i>Edit
        </a>
           @endif
            <a href="{{ route('laporan.index') }}"
               class="text-slate-300 hover:text-white font-semibold px-4 py-2 text-sm">
                Kembali
            </a>
        </div>
    </div>

    <p class="text-sm text-slate-400 mb-6">
        {{ $laporan->alamat }} &middot; Dilaporkan {{ $laporan->created_at->format('d M Y') }}
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- KOLOM KIRI: FOTO + INFO --}}
        <div class="lg:col-span-2 bg-panel border border-border rounded-xl p-6 space-y-6">

            @if($laporan->foto)
                <img src="{{ asset('storage/'.$laporan->foto) }}"
                     class="w-full h-64 object-cover rounded-lg border border-border">
            @else
                <div class="w-full h-64 rounded-lg bg-panel2 border border-border flex items-center justify-center text-slate-500 text-sm">
                    <i class="fa-solid fa-image mr-2"></i>Foto bukti kerusakan
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500 text-xs uppercase mb-1">Kategori</p>
                    <p class="text-slate-100 font-medium">{{ $laporan->kategori }}</p>
                </div>
                <div>
                    <p class="text-slate-500 text-xs uppercase mb-1">Tingkat Kerusakan</p>
                    @php
                        $badge = match($laporan->tingkat) {
                            'Berat' => 'bg-red-500/15 text-red-400 border-red-500/40',
                            'Sedang' => 'bg-amber-500/15 text-amber-400 border-amber-500/40',
                            default => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/40',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase px-3 py-1 rounded-full border {{ $badge }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $laporan->tingkat }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-slate-500 text-xs uppercase mb-1">Deskripsi</p>
                <p class="text-slate-200 leading-relaxed text-sm">{{ $laporan->deskripsi ?: '-' }}</p>
            </div>

            @if($laporan->latitude && $laporan->longitude)
            <div>
                <p class="text-slate-500 text-xs uppercase mb-2">Lokasi</p>
                <div id="map" class="w-full h-44 rounded-lg border border-border"></div>
                <p class="text-xs text-slate-400 mt-2">{{ $laporan->latitude }}, {{ $laporan->longitude }}</p>
            </div>
            @endif
        </div>

        {{-- KOLOM KANAN: RIWAYAT TINDAK LANJUT --}}
        <div class="bg-panel border border-border rounded-xl p-6">
            <p class="text-accent text-xs font-bold uppercase tracking-wider mb-5">Riwayat Tindak Lanjut</p>

            @forelse($laporan->tindakLanjuts as $item)
                <div class="relative pl-6 {{ !$loop->last ? 'pb-6' : '' }}">
                    @if(!$loop->last)
                        <span class="absolute left-[5px] top-3 bottom-0 w-px bg-border"></span>
                    @endif
                    <span class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-accent"></span>

                    <p class="text-xs text-slate-500 mb-1">{{ $item->created_at->format('d M Y, H.i') }}</p>
                    <p class="text-white font-bold text-sm mb-1">{{ $item->judul }}</p>
                    @if($item->keterangan)
                        <p class="text-slate-400 text-sm">{{ $item->keterangan }}</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="fa-regular fa-clock text-slate-600 text-2xl mb-3"></i>
                    <p class="text-slate-500 text-sm">Belum ada tindak lanjut.</p>
                    <p class="text-slate-600 text-xs mt-1">
                        Status saat ini:
                        <span class="text-accent font-semibold">{{ $laporan->status ?? 'Menunggu Verifikasi' }}</span>
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="flex gap-4 mt-6">
        @if ($laporan->status === 'Menunggu Verifikasi')
    <form action="{{ route('laporan.destroy', $laporan->id) }}" method="POST"
          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
        @csrf @method('DELETE')
        <button type="submit"
                class="border border-red-500/40 text-red-400 hover:bg-red-500/10 font-semibold px-5 py-2.5 rounded-lg text-sm">
            <i class="fa-solid fa-trash mr-2"></i>Hapus Laporan
        </button>
    </form>
@endif
    </div>
@endsection

@push('scripts')
@if($laporan->latitude && $laporan->longitude)
<script>
    const lat = {{ $laporan->latitude }};
    const lng = {{ $laporan->longitude }};
    const map = L.map('map', { attributionControl: false }).setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([lat, lng]).addTo(map);
</script>
@endif
@endpush
