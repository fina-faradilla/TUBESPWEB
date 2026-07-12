@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'DASHBOARD')

@section('top-bar-trailing')
    <div class="search-box">🔍 Cari cepat...</div>
    <div class="avatar">AD</div>
@endsection

@section('content')

    {{-- Kartu statistik --}}
    <div class="stat-grid">
        <div class="card">
            <div class="stat-label">TOTAL LAPORAN</div>
            <div class="stat-value">{{ $total }}</div>
            <div class="stat-note" style="color:var(--green)">Data saat ini</div>
        </div>
        <div class="card">
            <div class="stat-label">MENUNGGU VERIFIKASI</div>
            <div class="stat-value">{{ $menungguVerifikasi }}</div>
            <div class="stat-note" style="color:var(--gold)">Perlu ditinjau</div>
        </div>
        <div class="card">
            <div class="stat-label">SEDANG DIPROSES</div>
            <div class="stat-value">{{ $sedangDiproses }}</div>
            <div class="stat-note" style="color:var(--blue)">Ditangani tim</div>
        </div>
        <div class="card">
            <div class="stat-label">SELESAI</div>
            <div class="stat-value">{{ $selesai }}</div>
            <div class="stat-note" style="color:var(--green)">{{ $persenSelesai }}% dari total</div>
        </div>
    </div>

    {{-- Tren + laporan terbaru --}}
    <div class="dashboard-grid">
        <div class="card">
            <div class="card-title">TREN LAPORAN PER BULAN</div>
            <div class="bar-chart">
                @foreach ($trend as $t)
                    <div class="bar-col">
                        <div class="bar" style="height: {{ max(4, round(($t['value'] / $maxTrend) * 100)) }}%"></div>
                        <div class="bar-label">{{ $t['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="card-title">LAPORAN TERBARU</div>
            @forelse ($terbaru as $l)
                <div class="terbaru-item">
                    <div class="terbaru-text">{{ $l->judul }} — {{ $l->nama_pelapor }}</div>
                    <span class="badge {{ $l->statusColorClass() }}"><span class="dot"></span>{{ $l->status }}</span>
                </div>
            @empty
                <p style="color:var(--text-secondary); font-size:13px;">Belum ada laporan.</p>
            @endforelse
        </div>
    </div>

@endsection