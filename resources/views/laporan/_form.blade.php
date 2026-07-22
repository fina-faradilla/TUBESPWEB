{{-- Partial form: dipakai oleh create.blade.php dan edit.blade.php --}}
<form action="{{ isset($laporan) ? route('laporan.update', $laporan->id) : route('laporan.store') }}"
      method="POST" enctype="multipart/form-data" id="form-laporan">
    @csrf
    @isset($laporan) @method('PUT') @endisset

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: FIELD UTAMA -->
        <div class="lg:col-span-2 bg-panel border border-border rounded-xl p-6 space-y-5">

            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">Judul Laporan</label>
                <input type="text" name="judul" value="{{ old('judul', $laporan->judul ?? '') }}"
                       placeholder="Contoh: Jalan berlubang besar dekat pasar"
                       class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-accent">
                @error('judul')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-200 mb-2">Kategori Kerusakan</label>
                    <select name="kategori" id="kategori-select" class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-accent">
                        @foreach($kategoriOptions as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $laporan->kategori ?? '') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                        <option value="Lainnya" {{ old('kategori', $laporan->kategori ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror

                    <div id="kategori-lainnya-wrap" class="mt-2 {{ old('kategori') == 'Lainnya' ? '' : 'hidden' }}">
                        <input type="text" name="kategori_lainnya" value="{{ old('kategori_lainnya') }}"
                               placeholder="Tulis kategori baru..."
                               class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-accent">
                        @error('kategori_lainnya')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-200 mb-2">Tingkat Kerusakan</label>
                    <select name="tingkat" class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-accent">
                        @foreach(['Ringan','Sedang','Berat'] as $tk)
                            <option value="{{ $tk }}" {{ old('tingkat', $laporan->tingkat ?? '') == $tk ? 'selected' : '' }}>{{ $tk }}</option>
                        @endforeach
                    </select>
                    @error('tingkat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">Alamat / Titik Lokasi</label>
                <div class="relative">
                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $laporan->alamat ?? '') }}"
                           placeholder="Jl. Merdeka No. 12, Kec. ..."
                           class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 pr-10 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-accent">
                    <button type="button" id="btn-locate" title="Gunakan lokasi GPS saat ini"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-accent hover:text-accent2">
                        <i class="fa-solid fa-location-crosshairs"></i>
                    </button>
                </div>
                <p id="geocode-status" class="text-xs text-slate-500 mt-1"></p>
                @error('alamat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          placeholder="Jelaskan kondisi kerusakan, sejak kapan, dan dampaknya bagi pengguna jalan..."
                          class="w-full bg-panel2 border border-border rounded-lg px-4 py-2.5 text-sm text-slate-100 placeholder-slate-500 resize-none focus:outline-none focus:ring-2 focus:ring-accent">{{ old('deskripsi', $laporan->deskripsi ?? '') }}</textarea>
                @error('deskripsi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- KOLOM KANAN: FOTO + LOKASI -->
        <div class="space-y-6">

            <!-- UPLOAD FOTO -->
            <div class="bg-panel border border-border rounded-xl p-6">
                <label class="block text-sm font-semibold text-slate-200 mb-3">Foto Bukti</label>

                <div id="drop-area"
                     class="relative border-2 border-dashed border-border rounded-lg h-40 flex flex-col items-center justify-center text-center cursor-pointer hover:border-accent transition-colors bg-panel2 overflow-hidden">
                    <img id="preview-img"
                         src="{{ isset($laporan) && $laporan->foto ? asset('storage/'.$laporan->foto) : '' }}"
                         class="{{ isset($laporan) && $laporan->foto ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover">
                    <div id="drop-placeholder" class="{{ isset($laporan) && $laporan->foto ? 'hidden' : '' }} flex flex-col items-center gap-2 px-4">
                        <i class="fa-solid fa-camera text-2xl text-slate-500"></i>
                        <p class="text-sm text-slate-300">Tarik foto ke sini atau <span class="text-accent underline">pilih file</span></p>
                        <p class="text-xs text-slate-500">JPG/PNG, maks. 5MB</p>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>
                @error('foto')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- PRATINJAU LOKASI -->
            <div class="bg-panel border border-border rounded-xl p-6">
                <label class="block text-sm font-semibold text-slate-200 mb-3">Pratinjau Lokasi</label>
                <div id="map" class="w-full h-48 rounded-lg border border-border"></div>
                <p class="text-xs text-slate-400 mt-2">
                    <span id="lat-lng-text">{{ old('latitude', $laporan->latitude ?? '-6.9147') }}, {{ old('longitude', $laporan->longitude ?? '107.6098') }}</span>
                    <span class="text-slate-500">(otomatis dari GPS perangkat)</span>
                </p>
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $laporan->latitude ?? '-6.9147') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $laporan->longitude ?? '107.6098') }}">
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="flex items-center gap-3 mt-6">
        <button type="submit" class="bg-accent hover:bg-accent2 text-panel font-bold px-6 py-2.5 rounded-lg text-sm transition-colors">
            <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Laporan
        </button>
        <a href="{{ route('laporan.index') }}" class="text-slate-300 hover:text-white font-semibold px-4 py-2.5 text-sm">
            Batalkan
        </a>
    </div>
</form>

@push('scripts')
<script>
(function () {
    // ---- Peta Leaflet ----
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const latLngText = document.getElementById('lat-lng-text');

    const initialLat = parseFloat(latInput.value) || -6.9147;
    const initialLng = parseFloat(lngInput.value) || 107.6098;

    const map = L.map('map', { attributionControl: false }).setView([initialLat, initialLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

    function updateLatLng(lat, lng) {
        latInput.value = lat.toFixed(6);
        lngInput.value = lng.toFixed(6);
        latLngText.textContent = lat.toFixed(4) + ', ' + lng.toFixed(4);
    }

    const alamatInput = document.getElementById('alamat');
    const geocodeStatus = document.getElementById('geocode-status');
    let geocodeTimer = null;
    let skipNextReverseGeocode = false;

    function reverseGeocode(lat, lng) {
        if (skipNextReverseGeocode) { skipNextReverseGeocode = false; return; }
        geocodeStatus.textContent = 'Mencari nama alamat...';
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    alamatInput.value = data.display_name;
                    geocodeStatus.textContent = 'Alamat diperbarui sesuai titik peta.';
                } else {
                    geocodeStatus.textContent = '';
                }
            })
            .catch(() => { geocodeStatus.textContent = ''; });
    }

    function geocodeAddress(query) {
        if (!query || query.trim().length < 3) return;
        geocodeStatus.textContent = 'Mencari lokasi di peta...';
        fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    skipNextReverseGeocode = true;
                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateLatLng(lat, lng);
                    geocodeStatus.textContent = 'Titik peta disesuaikan dengan alamat.';
                } else {
                    geocodeStatus.textContent = 'Alamat tidak ditemukan di peta, silakan geser pin manual.';
                }
            })
            .catch(() => { geocodeStatus.textContent = 'Gagal menghubungi layanan peta.'; });
    }

    alamatInput.addEventListener('input', function () {
        clearTimeout(geocodeTimer);
        geocodeTimer = setTimeout(() => geocodeAddress(alamatInput.value), 800);
    });

    marker.on('dragend', function (e) {
        const pos = e.target.getLatLng();
        updateLatLng(pos.lat, pos.lng);
        reverseGeocode(pos.lat, pos.lng);
    });

    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('btn-locate').addEventListener('click', function () {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung browser ini.');
            return;
        }
        geocodeStatus.textContent = 'Mengambil lokasi GPS...';
        navigator.geolocation.getCurrentPosition(function (pos) {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;
            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            updateLatLng(lat, lng);
            reverseGeocode(lat, lng);
        }, function () {
            alert('Gagal mengambil lokasi GPS.');
            geocodeStatus.textContent = '';
        });
    });

    // ---- Upload & Preview Foto (drag & drop) ----
    const dropArea = document.getElementById('drop-area');
    const fotoInput = document.getElementById('foto');
    const previewImg = document.getElementById('preview-img');
    const placeholder = document.getElementById('drop-placeholder');

    function showPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    fotoInput.addEventListener('change', function (e) {
        showPreview(e.target.files[0]);
    });

    ['dragenter', 'dragover'].forEach(evt => {
        dropArea.addEventListener(evt, function (e) {
            e.preventDefault();
            dropArea.classList.add('border-accent');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropArea.addEventListener(evt, function (e) {
            e.preventDefault();
            dropArea.classList.remove('border-accent');
        });
    });
    dropArea.addEventListener('drop', function (e) {
        const file = e.dataTransfer.files[0];
        if (file) {
            fotoInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    // ---- Toggle input "Kategori Lainnya" ----
    const kategoriSelect = document.getElementById('kategori-select');
    const kategoriLainnyaWrap = document.getElementById('kategori-lainnya-wrap');
    kategoriSelect.addEventListener('change', function () {
        kategoriLainnyaWrap.classList.toggle('hidden', this.value !== 'Lainnya');
    });
})();
</script>
@endpush