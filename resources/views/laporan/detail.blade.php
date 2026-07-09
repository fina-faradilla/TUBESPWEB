@extends('layouts.app')

@section('content')

<div class="wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">
                JK
            </div>

            <div>
                <h2>JALANKITA</h2>
                <small>Jembatan Lapor Jalan Rusak</small>
            </div>
        </div>

        <div class="menu-title">
            HALAMAN PUBLIK
        </div>

        <ul>
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="{{ route('login') }}">Masuk / Daftar</a></li>
        </ul>

        <div class="menu-title">
            PORTAL WARGA
        </div>

        <ul>
            <li><a href="{{ route('laporan.create') }}">Buat Laporan</a></li>
            <li><a href="{{ route('laporan.index') }}">Riwayat Laporan Saya</a></li>
            <li class="active">
                <a href="#">Detail Laporan</a>
            </li>
        </ul>

        <div class="menu-title">
            PORTAL ADMIN / DINAS
        </div>

        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Kelola Laporan</a></li>
        </ul>

    </aside>

    <!-- Content -->
    <main class="content">

        <span class="kode">
            LAPORAN #{{ $laporan->kode_laporan ?? 'JK-2026-0143' }}
        </span>

        <h1>
            {{ strtoupper($laporan->judul) }}
        </h1>

        <p class="alamat">
            {{ $laporan->alamat }}
            • Dilaporkan
            {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y') }}
        </p>

        <div class="grid">

            <!-- Detail Laporan -->
            <div class="card">

                <div class="foto">

                    @if($laporan->foto)

                        <img src="{{ asset('storage/'.$laporan->foto) }}"
                             alt="Foto Laporan"
                             style="width:100%;height:100%;object-fit:cover;border-radius:10px;">

                    @else

                        Foto bukti kerusakan

                    @endif

                </div>

                <div class="row">

                    <div>

                        <small>Kategori</small>

                        <h3>{{ $laporan->kategori }}</h3>

                    </div>

                    <div>

                        <small>Tingkat</small>

                        <span class="badge">

                            {{ strtoupper($laporan->tingkat) }}

                        </span>

                    </div>

                </div>

                <div class="deskripsi">

                    <small>Deskripsi</small>

                    <p>
                        {{ $laporan->deskripsi }}
                    </p>

                </div>

            </div>

            <!-- Timeline -->
            <div class="card">

                <h3 class="timeline-title">
                    RIWAYAT TINDAK LANJUT
                </h3>

                <div class="timeline">

                    <div class="item">

                        <span class="dot"></span>

                        <div>

                            <small>
                                {{ \Carbon\Carbon::parse($laporan->created_at)->format('d M Y H:i') }}
                            </small>

                            <h4>Laporan diterima</h4>

                            <p>
                                Laporan berhasil dikirim ke sistem.
                            </p>

                        </div>

                    </div>

                    <div class="item">

                        <span class="dot"></span>

                        <div>

                            <small>
                                {{ \Carbon\Carbon::parse($laporan->created_at)->addHours(2)->format('d M Y H:i') }}
                            </small>

                            <h4>Diverifikasi Admin</h4>

                            <p>
                                Laporan telah diverifikasi oleh admin.
                            </p>

                        </div>

                    </div>

                    <div class="item">

                        <span class="dot"></span>

                        <div>

                            <small>
                                {{ \Carbon\Carbon::parse($laporan->created_at)->addDay()->format('d M Y H:i') }}
                            </small>

                            <h4>Menunggu Perbaikan</h4>

                            <p>
                                Laporan diteruskan ke Dinas PUPR.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

@endsection
