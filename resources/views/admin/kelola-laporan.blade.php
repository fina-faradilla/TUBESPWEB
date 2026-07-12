@extends('layouts.admin')

@section('title', 'Kelola Laporan')
@section('page-title', 'KELOLA LAPORAN')

@section('top-bar-trailing')
    <button class="btn-gold" onclick="openModal('modal-tambah')">+ Tambah Manual</button>
@endsection

@section('content')
    <div class="card">

        {{-- ===== FILTER BAR ===== --}}
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-bar">
            <div class="filter-search">
                🔍 <input type="text" name="q" value="{{ $query }}" placeholder="Cari laporan, lokasi, atau ID...">
            </div>
            <select name="kategori" class="filter-select" onchange="this.form.submit()">
                <option {{ $filterKategori === 'Semua Kategori' ? 'selected' : '' }}>Semua Kategori</option>
                @foreach ($kategoriOptions as $opt)
                    <option value="{{ $opt }}" {{ $filterKategori === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option {{ $filterStatus === 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                @foreach ($statusOptions as $opt)
                    <option value="{{ $opt }}" {{ $filterStatus === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
        </form>

        {{-- ===== TABEL ===== --}}
        <table class="laporan-table">
            <thead>
                <tr>
                    <th>ID</th><th>JUDUL</th><th>PELAPOR</th><th>KATEGORI</th>
                    <th>DESKRIPSI</th><th>STATUS</th><th>TANGGAL</th><th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $laporan)
                    <tr>
                        <td>{{ $laporan->kode_laporan }}</td>
                        <td>
                            <a href="{{ route('admin.laporan.show', $laporan) }}"
                               style="color:var(--text-primary); text-decoration:none;">
                                {{ $laporan->judul }}
                            </a>
                        </td>
                        <td>
                            {{ $laporan->nama_pelapor }}
                            @if ($laporan->user_id)
                                <span title="Laporan dari akun warga" style="color:var(--text-secondary); font-size:11px;">(warga)</span>
                            @endif
                        </td>
                        <td>{{ $laporan->kategori }}</td>
                        <td style="max-width:220px; white-space:normal; color:var(--text-secondary); font-size:12px;">
                            {{ $laporan->deskripsi ? \Illuminate\Support\Str::limit($laporan->deskripsi, 80) : '—' }}
                        </td>
                        <td>
                            <span class="badge {{ $laporan->statusColorClass() }}">
                                <span class="dot"></span>{{ $laporan->status }}
                            </span>
                        </td>
                        <td>{{ $laporan->created_at->translatedFormat('d M Y') }}</td>
                        <td>
                            {{-- Detail --}}
                           <a href="{{ route('admin.laporan.show', $laporan) }}"
                            class="aksi-link aksi-detail">
                                Detail
                            </a>

                            {{-- Verifikasi --}}
                            <form method="POST" action="{{ route('admin.laporan.verifikasi', $laporan) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="aksi-link aksi-verifikasi {{ in_array($laporan->status, ['Selesai', 'Ditolak']) ? 'disabled' : '' }}"
                                        {{ in_array($laporan->status, ['Selesai', 'Ditolak']) ? 'disabled' : '' }}>
                                    Verifikasi
                                </button>
                            </form>

                            {{-- Ubah --}}
                            <button type="button" class="aksi-link aksi-ubah"
                                    onclick='openEditModal(@json($laporan))'>
                                Ubah
                            </button>

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.laporan.destroy', $laporan) }}"
                                  onsubmit="return confirm('Laporan &quot;{{ $laporan->judul }}&quot; ({{ $laporan->kode_laporan }}) akan dihapus permanen. Lanjutkan?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="aksi-link aksi-hapus">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:var(--text-secondary); padding:32px 0;">
                            Tidak ada laporan yang cocok dengan pencarian/filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p style="color:var(--text-secondary); font-size:12px; margin-top:20px;">
            Menampilkan {{ $laporans->count() }} dari {{ $totalKeseluruhan }} laporan
        </p>

        {{ $laporans->links('vendor.pagination.custom-dark') }}
    </div>

    {{-- ===== MODAL TAMBAH ===== --}}
    <div class="modal-overlay" id="modal-tambah">
        <div class="modal-box">
            <div class="modal-title">Tambah Laporan Manual</div>
            <form method="POST" action="{{ route('admin.laporan.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.partials.laporan-form-fields', ['mapId' => 'map-tambah'])
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-tambah')">Batal</button>
                    <button type="submit" class="btn-gold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL UBAH ===== --}}
    <div class="modal-overlay" id="modal-ubah">
        <div class="modal-box">
            <div class="modal-title">Ubah Laporan</div>
            <form method="POST" id="form-ubah" action="" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('admin.partials.laporan-form-fields', ['prefix' => 'edit_', 'mapId' => 'map-ubah'])
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('modal-ubah')">Batal</button>
                    <button type="submit" class="btn-gold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const KATEGORI_OPTIONS_BAKU = @json($kategoriOptions);

        // Tampilkan/sembunyikan kolom "Kategori Lainnya" sesuai pilihan select.
        function toggleKategoriLain(selectEl) {
            const prefix = selectEl.id.replace('kategori', '');
            const wrap = document.getElementById(prefix + 'kategori_lainnya_wrap');
            const input = document.getElementById(prefix + 'kategori_lainnya');
            if (!wrap || !input) return;

            if (selectEl.value === 'Lainnya') {
                wrap.style.display = '';
                input.required = true;
            } else {
                wrap.style.display = 'none';
                input.required = false;
                input.value = '';
            }
        }

        function openModal(id) { document.getElementById(id).classList.add('open'); initMapFor(id); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }

        function openEditModal(laporan) {
            const form = document.getElementById('form-ubah');
            form.action = "{{ url('admin/manage-report') }}/" + laporan.id;
            document.getElementById('edit_judul').value = laporan.judul;

            // Pelapor: kalau laporan berasal dari akun warga, tampilkan nama akunnya
            // tapi kunci (readonly) supaya tidak bisa "dipalsukan" lewat form admin.
            const pelaporInput = document.getElementById('edit_pelapor');
            pelaporInput.value = laporan.nama_pelapor || laporan.pelapor || '';
            if (laporan.user_id) {
                pelaporInput.setAttribute('readonly', 'readonly');
                pelaporInput.title = 'Laporan ini berasal dari akun warga, nama pelapor mengikuti akun tersebut.';
            } else {
                pelaporInput.removeAttribute('readonly');
                pelaporInput.title = '';
            }

            const kategoriSelect = document.getElementById('edit_kategori');
            if (KATEGORI_OPTIONS_BAKU.includes(laporan.kategori)) {
                // Kategori baku (mis. Berlubang, Retak, dst).
                kategoriSelect.value = laporan.kategori;
                toggleKategoriLain(kategoriSelect);
            } else {
                // Kategori kustom hasil isian manual sebelumnya -> pilih "Lainnya" + isi teksnya.
                kategoriSelect.value = 'Lainnya';
                toggleKategoriLain(kategoriSelect);
                document.getElementById('edit_kategori_lainnya').value = laporan.kategori;
            }

            document.getElementById('edit_tingkat').value = laporan.tingkat || 'Sedang';
            document.getElementById('edit_status').value = laporan.status;
            document.getElementById('edit_deskripsi').value = laporan.deskripsi || '';
            document.getElementById('edit_alamat').value = laporan.alamat || '';
            document.getElementById('edit_latitude').value = laporan.latitude || '';
            document.getElementById('edit_longitude').value = laporan.longitude || '';
            openModal('modal-ubah');

            // Kalau laporan sudah punya titik lokasi, taruh marker di posisi itu.
            if (laporan.latitude && laporan.longitude) {
                setTimeout(() => setMarker('map-ubah', laporan.latitude, laporan.longitude), 150);
            }
        }

        // ---- Peta klik-untuk-pilih-lokasi (Leaflet + OpenStreetMap, gratis tanpa API key) ----
        const mapInstances = {};
        const markerInstances = {};

        function initMapFor(modalId) {
            const mapId = modalId === 'modal-tambah' ? 'map-tambah' : 'map-ubah';
            if (mapInstances[mapId]) {
                setTimeout(() => mapInstances[mapId].invalidateSize(), 200);
                return;
            }
            const defaultLat = -6.9147, defaultLng = 107.6098; // default: Bandung
            const map = L.map(mapId).setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);

            map.on('click', function (e) {
                setMarker(mapId, e.latlng.lat, e.latlng.lng);
                const prefix = mapId === 'map-tambah' ? '' : 'edit_';
                document.getElementById(prefix + 'latitude').value = e.latlng.lat.toFixed(7);
                document.getElementById(prefix + 'longitude').value = e.latlng.lng.toFixed(7);
            });

            mapInstances[mapId] = map;
            setTimeout(() => map.invalidateSize(), 200);
        }

        function setMarker(mapId, lat, lng) {
            const map = mapInstances[mapId];
            if (!map) return;
            if (markerInstances[mapId]) {
                markerInstances[mapId].setLatLng([lat, lng]);
            } else {
                markerInstances[mapId] = L.marker([lat, lng]).addTo(map);
            }
            map.setView([lat, lng], 15);
        }

        // ---- Alamat -> peta otomatis (geocoding via Nominatim/OpenStreetMap, gratis) ----
        const geocodeTimers = new WeakMap();

        function initGeocodeInputs() {
            document.querySelectorAll('.geocode-alamat').forEach(function (input) {
                input.addEventListener('input', function () {
                    clearTimeout(geocodeTimers.get(input));
                    geocodeTimers.set(input, setTimeout(function () {
                        geocodeAlamat(input);
                    }, 700)); // tunggu user berhenti ngetik ~0.7 detik
                });
            });
        }

        function geocodeAlamat(input) {
            const alamat = input.value.trim();
            const mapId = input.dataset.mapId;
            const prefix = input.dataset.prefix || '';
            const status = document.getElementById(mapId + '-status');

            if (alamat.length < 5 || !mapInstances[mapId]) return;
            if (status) status.textContent = 'Mencari lokasi...';

            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&q=' + encodeURIComponent(alamat))
                .then(function (res) { return res.json(); })
                .then(function (results) {
                    if (!results || !results.length) {
                        if (status) status.textContent = 'Lokasi tidak ditemukan otomatis — silakan klik peta untuk memilih manual.';
                        return;
                    }
                    const lat = parseFloat(results[0].lat);
                    const lng = parseFloat(results[0].lon);

                    setMarker(mapId, lat, lng);
                    document.getElementById(prefix + 'latitude').value = lat.toFixed(7);
                    document.getElementById(prefix + 'longitude').value = lng.toFixed(7);
                    if (status) status.textContent = 'Lokasi ditemukan: ' + results[0].display_name;
                })
                .catch(function () {
                    if (status) status.textContent = 'Gagal menghubungi layanan pencarian lokasi.';
                });
        }

        document.addEventListener('DOMContentLoaded', initGeocodeInputs);
    </script>
    @endpush
@endsection