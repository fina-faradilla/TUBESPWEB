@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'KELOLA KATEGORI')

@section('top-bar-trailing')
    <button class="btn-gold" onclick="openModal('modal-tambah-kategori')">+ Tambah Kategori</button>
@endsection

@section('content')
    <div class="card">

        @if (session('error'))
            <div class="alert-success" style="background:rgba(220,53,69,.12); color:#ff6b7a; border:1px solid rgba(220,53,69,.3);">
                {{ session('error') }}
            </div>
        @endif

        <p style="color:var(--text-secondary); font-size:12px; margin-bottom:16px;">
            Kategori di sini otomatis dipakai sebagai pilihan dropdown di form Tambah/Ubah Laporan.
            Kategori baru juga otomatis tersimpan di sini saat admin mengetik kategori manual lewat pilihan "Lainnya".
        </p>

        {{-- ===== TABEL ===== --}}
        <table class="laporan-table">
            <thead>
                <tr>
                    <th>NAMA KATEGORI</th><th>JUMLAH LAPORAN</th><th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $kategori)
                    <tr>
                        <td>{{ $kategori->nama }}</td>
                        <td>{{ $kategori->jumlah_laporan }}</td>
                        <td>
                            {{-- Ubah --}}
                            <button type="button" class="aksi-link aksi-ubah"
                                    onclick='openEditModal(@json($kategori))'>
                                Ubah
                            </button>

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Hapus kategori &quot;{{ $kategori->nama }}&quot;?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="aksi-link aksi-hapus">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; color:var(--text-secondary); padding:32px 0;">
                            Belum ada kategori.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ===== MODAL TAMBAH ===== --}}
    <div class="modal-overlay" id="modal-tambah-kategori">
        <div class="modal-box">
            <div class="modal-title">Tambah Kategori</div>
            <form method="POST" action="{{ route('admin.kategori.store') }}">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Kategori</label>
                    <input type="text" id="nama" name="nama" required maxlength="100" placeholder="mis. Trotoar Rusak">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-tambah-kategori')">Batal</button>
                    <button type="submit" class="btn-gold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL UBAH ===== --}}
    <div class="modal-overlay" id="modal-ubah-kategori">
        <div class="modal-box">
            <div class="modal-title">Ubah Kategori</div>
            <form method="POST" id="form-ubah-kategori" action="">
                @csrf @method('PUT')
                <div class="form-group">
                    <label for="edit_nama">Nama Kategori</label>
                    <input type="text" id="edit_nama" name="nama" required maxlength="100">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-ubah-kategori')">Batal</button>
                    <button type="submit" class="btn-gold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openModal(id) { document.getElementById(id).classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }

        function openEditModal(kategori) {
            const form = document.getElementById('form-ubah-kategori');
            form.action = "{{ url('admin/kategori') }}/" + kategori.id;
            document.getElementById('edit_nama').value = kategori.nama;
            openModal('modal-ubah-kategori');
        }
    </script>
    @endpush
@endsection
