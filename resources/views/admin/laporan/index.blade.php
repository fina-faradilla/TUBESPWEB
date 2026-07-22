@extends('layouts.admin')

@section('title', 'Kelola Laporan')
@section('page-title', 'KELOLA LAPORAN')

@section('top-bar-trailing')
    <button type="button" class="btn-gold" onclick="openTambahModal()">+ Tambah Manual</button>
@endsection

@section('content')

    <div class="card">
        {{-- Filter bar --}}
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-bar">
            <div class="filter-search">
                🔍
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari laporan, lokasi, atau ID..."
                       onchange="this.form.submit()">
            </div>

            <select name="kategori" class="filter-select" onchange="this.form.submit()">
                <option {{ $filterKategori === 'Semua Kategori' ? 'selected' : '' }}>Semua Kategori</option>
                @foreach ($kategoriOptions as $k)
                    <option value="{{ $k }}" {{ $filterKategori === $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option {{ $filterStatus === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                @foreach ($statusOptions as $s)
                    <option value="{{ $s }}" {{ $filterStatus === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </form>

        {{-- Tabel --}}
        <table class="laporan-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>JUDUL</th>
                <th>PELAPOR</th>
                <th>KATEGORI</th>
                <th>STATUS</th>
                <th>TANGGAL</th>
                <th>AKSI</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($laporans as $l)
                @php
                    $badgeClass = [
                        'BARU' => 'badge-baru',
                        'DIPROSES' => 'badge-diproses',
                        'DIVERIFIKASI' => 'badge-diverifikasi',
                        'SELESAI' => 'badge-selesai',
                    ][$l->status] ?? '';
                    $sudahSelesai = $l->status === 'SELESAI';
                @endphp
                <tr>
                    <td>{{ $l->kode }}</td>
                    <td>{{ $l->judul }}</td>
                    <td>{{ $l->pelapor }}</td>
                    <td>{{ $l->kategori }}</td>
                    <td><span class="badge {{ $badgeClass }}"><span class="dot"></span>{{ $l->status }}</span></td>
                    <td>{{ $l->tanggal->format('d M Y') }}</td>
                    <td>
                        @if (!$sudahSelesai)
                            <form method="POST" action="{{ route('admin.laporan.verifikasi', $l) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="aksi-link aksi-verifikasi">Verifikasi</button>
                            </form>
                        @else
                            <span class="aksi-link aksi-verifikasi disabled">Verifikasi</span>
                        @endif

                        <button type="button" class="aksi-link aksi-ubah"
                                onclick='openUbahModal(@json($l))'>Ubah</button>

                        <form method="POST" action="{{ route('admin.laporan.destroy', $l) }}" style="display:inline"
                              onsubmit="return confirm('Laporan &quot;{{ $l->judul }}&quot; ({{ $l->kode }}) akan dihapus permanen. Lanjutkan?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="aksi-link aksi-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; color:var(--text-secondary); padding:32px 0;">
                        Tidak ada laporan yang cocok dengan pencarian/filter.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <p style="color:var(--text-secondary); font-size:12px; margin-top:20px;">
            Menampilkan {{ $laporans->count() }} dari {{ $totalKeseluruhan }} laporan
        </p>

        <div class="pagination">
            {{ $laporans->links() }}
        </div>
    </div>

    {{-- ===== MODAL: Tambah / Ubah Laporan ===== --}}
    <div class="modal-overlay" id="laporanModal">
        <div class="modal-box">
            <div class="modal-title" id="modalTitle">Tambah Laporan</div>
            <form id="laporanForm" method="POST" action="{{ route('admin.laporan.store') }}">
                @csrf
                <div id="methodField"></div>

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="field_judul" required>
                </div>
                <div class="form-group">
                    <label>Pelapor</label>
                    <input type="text" name="pelapor" id="field_pelapor" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" id="field_kategori" required>
                        @foreach ($kategoriOptions as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="field_status" required>
                        @foreach ($statusOptions as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="field_tanggal" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-gold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const modal = document.getElementById('laporanModal');
    const form = document.getElementById('laporanForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    function openTambahModal() {
        modalTitle.textContent = 'Tambah Laporan';
        form.action = "{{ route('admin.laporan.store') }}";
        methodField.innerHTML = '';
        form.reset();
        document.getElementById('field_tanggal').value = new Date().toISOString().slice(0, 10);
        modal.classList.add('open');
    }

    function openUbahModal(laporan) {
        modalTitle.textContent = 'Ubah Laporan — ' + laporan.kode;
        form.action = "{{ url('admin/manage-report') }}/" + laporan.id;
        methodField.innerHTML = '@method("PUT")';
        document.getElementById('field_judul').value = laporan.judul;
        document.getElementById('field_pelapor').value = laporan.pelapor;
        document.getElementById('field_kategori').value = laporan.kategori;
        document.getElementById('field_status').value = laporan.status;
        document.getElementById('field_tanggal').value = laporan.tanggal.slice(0, 10);
        modal.classList.add('open');
    }

    function closeModal() {
        modal.classList.remove('open');
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
</script>
@endpush
