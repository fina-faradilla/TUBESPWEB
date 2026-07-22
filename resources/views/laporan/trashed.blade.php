@extends('layouts.app')
@section('title', 'Sampah - RoadFix')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-accent text-xs font-bold uppercase tracking-wider mb-1">Portal Warga</p>
            <h1 class="text-2xl font-extrabold text-white">Sampah</h1>
            <p class="text-slate-400 text-sm mt-1">Laporan yang dihapus dari Riwayat akan tersimpan di sini dan bisa dipulihkan.</p>
        </div>
        <a href="{{ route('laporan.index') }}"
           class="bg-panel2 border border-border hover:border-accent text-slate-200 font-semibold px-4 py-2 rounded-lg text-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Riwayat
        </a>
    </div>

    <div class="bg-panel border border-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-panel2 text-slate-400 text-left uppercase text-xs tracking-wider">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Judul</th>
                    <th class="px-5 py-3">Kategori</th>
                    <th class="px-5 py-3">Dihapus Pada</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($laporans as $laporan)
                    <tr class="hover:bg-panel2/60">
                        <td class="px-5 py-3 text-slate-300 font-medium">
                            RF-{{ str_pad($laporan->id + 139, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-3 text-slate-100 font-medium">{{ $laporan->judul }}</td>
                        <td class="px-5 py-3 text-slate-300">{{ $laporan->kategori }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $laporan->deleted_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3 text-right space-x-3 whitespace-nowrap">
                            <form action="{{ route('laporan.restore', $laporan->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Pulihkan laporan ini?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-emerald-400 hover:underline text-xs font-semibold">
                                    <i class="fa-solid fa-rotate-left mr-1"></i>Pulihkan
                                </button>
                            </form>
                            <form action="{{ route('laporan.forceDelete', $laporan->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus permanen? Aksi ini tidak bisa diurungkan!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:underline text-xs font-semibold">
                                    <i class="fa-solid fa-trash mr-1"></i>Hapus Permanen
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            Sampah kosong.
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
