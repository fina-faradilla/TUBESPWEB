@extends('layouts.admin')

@section('title', 'Detail Laporan — ' . $laporan->kode_laporan)
@section('page-title', 'DETAIL LAPORAN')

@section('top-bar-trailing')
    <a href="{{ route('admin.laporan.index') }}" class="btn-secondary" style="text-decoration:none;">
        &larr; Kembali ke Kelola Laporan
    </a>
@endsection

@section('content')
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:16px; align-items:start;">

        {{-- ===== KOLOM KIRI: Data + Peta ===== --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="color:var(--text-secondary); font-size:12px;">{{ $laporan->kode_laporan }}</div>
                        <h2 style="margin:4px 0 0; font-size:20px;">{{ $laporan->judul }}</h2>
                    </div>
                    <span class="badge {{ $laporan->statusColorClass() }}">
                        <span class="dot"></span>{{ $laporan->status }}
                    </span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-top:20px;">
                    <div>
                        <div style="color:var(--text-secondary); font-size:11px; font-weight:bold;">PELAPOR</div>
                        <div style="margin-top:4px;">
                            {{ $laporan->nama_pelapor }}
                            @if ($laporan->user_id)
                                <span style="color:var(--text-secondary); font-size:11px;">(akun warga)</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div style="color:var(--text-secondary); font-size:11px; font-weight:bold;">KATEGORI</div>
                        <div style="margin-top:4px;">{{ $laporan->kategori }}</div>
                    </div>
                    <div>
                        <div style="color:var(--text-secondary); font-size:11px; font-weight:bold;">TINGKAT KERUSAKAN</div>
                        <div style="margin-top:4px;">{{ $laporan->tingkat ?: '—' }}</div>
                    </div>
                    <div>
                        <div style="color:var(--text-secondary); font-size:11px; font-weight:bold;">TANGGAL LAPOR</div>
                        <div style="margin-top:4px;">{{ $laporan->created_at->translatedFormat('d F Y') }}</div>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <div style="color:var(--text-secondary); font-size:11px; font-weight:bold;">ALAMAT</div>
                        <div style="margin-top:4px;">{{ $laporan->alamat ?: '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- ===== DESKRIPSI (sama seperti yang diisi warga saat membuat laporan) ===== --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:12px;">DESKRIPSI</div>
                <p style="margin:0; color:var(--text-primary); font-size:13px; line-height:1.6; white-space:pre-line;">
                    {{ $laporan->deskripsi ?: 'Tidak ada deskripsi untuk laporan ini.' }}
                </p>
            </div>

            {{-- ===== PETA LOKASI ===== --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:16px;">LOKASI DI PETA</div>
                @if ($laporan->punyaLokasi())
                    <div id="detail-map" style="height:320px; border-radius:8px; overflow:hidden;"></div>
                    <p style="color:var(--text-secondary); font-size:12px; margin-top:10px;">
                        Koordinat: {{ $laporan->latitude }}, {{ $laporan->longitude }}
                    </p>
                @else
                    <div style="height:120px; display:flex; align-items:center; justify-content:center; color:var(--text-secondary); font-size:13px; border:1px dashed var(--card-border); border-radius:8px;">
                        Belum ada titik lokasi untuk laporan ini.
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Foto ===== --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">FOTO KERUSAKAN</div>
            @if ($laporan->fotoUrl())
                <img src="{{ $laporan->fotoUrl() }}" alt="Foto laporan {{ $laporan->kode_laporan }}"
                     style="width:100%; border-radius:8px; display:block;">
            @else
                <div style="height:200px; display:flex; align-items:center; justify-content:center; color:var(--text-secondary); font-size:13px; border:1px dashed var(--card-border); border-radius:8px;">
                    Belum ada foto untuk laporan ini.
                </div>
            @endif
        </div>
    </div>

    @if ($laporan->punyaLokasi())
        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        @endpush
        @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const map = L.map('detail-map').setView([{{ $laporan->latitude }}, {{ $laporan->longitude }}], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);
            L.marker([{{ $laporan->latitude }}, {{ $laporan->longitude }}])
                .addTo(map)
                .bindPopup(@json($laporan->judul))
                .openPopup();
        </script>
        @endpush
    @endif
@endsection
