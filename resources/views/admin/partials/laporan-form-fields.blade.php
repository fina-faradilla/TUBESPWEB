@php $prefix = $prefix ?? ''; @endphp

<div class="form-group">
    <label for="{{ $prefix }}judul">Judul</label>
    <input type="text" id="{{ $prefix }}judul" name="judul" required>
</div>

<div class="form-group">
    <label for="{{ $prefix }}pelapor">Pelapor</label>
    <input type="text" id="{{ $prefix }}pelapor" name="pelapor" required>
</div>

<div class="form-group">
    <label for="{{ $prefix }}kategori">Kategori</label>
    <select id="{{ $prefix }}kategori" name="kategori" required onchange="toggleKategoriLain(this)">
        @foreach (($kategoriOptions ?? []) as $opt)
            <option value="{{ $opt }}">{{ $opt }}</option>
        @endforeach
        <option value="Lainnya">Lainnya (isi manual)</option>
    </select>
</div>

<div class="form-group" id="{{ $prefix }}kategori_lainnya_wrap" style="display:none;">
    <label for="{{ $prefix }}kategori_lainnya">Kategori Lainnya (isi manual)</label>
    <input type="text" id="{{ $prefix }}kategori_lainnya" name="kategori_lainnya"
           placeholder="mis. Trotoar Rusak" maxlength="255">
</div>

<div class="form-group">
    <label for="{{ $prefix }}status">Status</label>
    <select id="{{ $prefix }}status" name="status" required>
        @foreach (\App\Models\Laporan::STATUS_OPTIONS as $opt)
            <option value="{{ $opt }}">{{ $opt }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="{{ $prefix }}tanggal">Tanggal</label>
    <input type="date" id="{{ $prefix }}tanggal" name="tanggal" required value="{{ now()->toDateString() }}">
</div>

<div class="form-group">
    <label for="{{ $prefix }}foto">Foto Kerusakan</label>
    <input type="file" id="{{ $prefix }}foto" name="foto" accept="image/*">
</div>

<div class="form-group">
    <label for="{{ $prefix }}alamat">Alamat / Lokasi (teks)</label>
    <input type="text" id="{{ $prefix }}alamat" name="alamat"
           class="geocode-alamat"
           data-map-id="{{ $mapId ?? 'map' }}"
           data-prefix="{{ $prefix ?? '' }}"
           placeholder="mis. Jl. Merdeka No. 12, Bandung" autocomplete="off">
    <small id="{{ $mapId ?? 'map' }}-status" style="display:block; color:var(--text-secondary); font-size:11px; margin-top:4px;"></small>
</div>

<div class="form-group">
    <label>Titik Lokasi di Peta (klik peta untuk memilih)</label>
    <div id="{{ $mapId ?? 'map' }}" style="height:220px; border-radius:8px; overflow:hidden; border:1px solid var(--card-border);"></div>
    <input type="hidden" id="{{ $prefix }}latitude" name="latitude">
    <input type="hidden" id="{{ $prefix }}longitude" name="longitude">
</div>