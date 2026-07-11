<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanController extends Controller
{
    /** Halaman "Kelola Laporan" — tabel + pencarian + filter. */
    public function index(Request $request)
    {
        $query = Laporan::query();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('pelapor', 'like', "%{$q}%")
                    ->orWhere('kode', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'Semua Kategori') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', $request->status);
        }

        $totalKeseluruhan = Laporan::count();
        $laporans = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(4)->withQueryString();
        $kategoriOptions = Kategori::orderBy('nama')->pluck('nama')->toArray();

        return view('admin.kelola-laporan', [
            'laporans' => $laporans,
            'totalKeseluruhan' => $totalKeseluruhan,
            'kategoriOptions' => $kategoriOptions,
            'statusOptions' => Laporan::STATUS_OPTIONS,
            'filterKategori' => $request->get('kategori', 'Semua Kategori'),
            'filterStatus' => $request->get('status', 'Semua Status'),
            'query' => $request->get('q', ''),
        ]);
    }

    /** Simpan laporan baru (dari modal "Tambah Manual"). */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        }

        $data['kode'] = Laporan::generateKode();

        Laporan::create($data);

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan baru berhasil ditambahkan');
    }

    /** Perbarui laporan (dari modal "Ubah"). */
    public function update(Request $request, Laporan $laporan)
    {
        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        }

        $laporan->update($data);

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /** Hapus laporan. */
    public function destroy(Laporan $laporan)
    {
        $laporan->delete();

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus');
    }

    /** Tombol "Verifikasi": maju satu tahap status. */
    public function verifikasi(Laporan $laporan)
    {
        $next = $laporan->statusBerikutnya();
        if ($next) {
            $laporan->update(['status' => $next]);
        }

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Status laporan diperbarui menjadi ' . $laporan->status);
    }

    /** Halaman detail satu laporan. */
    public function show(Laporan $laporan): \Illuminate\View\View
    {
        return view('admin.laporan-detail', compact('laporan'));
    }

    private function validated(Request $request): array
    {
        $kategoriTerdaftar = Kategori::pluck('nama')->push('Lainnya')->toArray();

        $data = $request->validate([
            'judul'            => ['required', 'string', 'max:255'],
            'pelapor'          => ['required', 'string', 'max:255'],
            'kategori'         => ['required', 'string', Rule::in($kategoriTerdaftar)],
            'kategori_lainnya' => ['required_if:kategori,Lainnya', 'nullable', 'string', 'max:100'],
            'status'           => ['required', Rule::in(Laporan::STATUS_OPTIONS)],
            'tanggal'          => ['required', 'date'],

            'alamat'    => ['nullable', 'string', 'max:255'],
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'foto'      => ['nullable', 'image', 'max:4096'],
        ]);

        // Kalau user pilih "Lainnya", pakai teks yang diketik manual sebagai kategori
        // sebenarnya, lalu daftarkan sebagai kategori baru supaya tidak "hilang" dan
        // langsung muncul sebagai pilihan baku untuk laporan berikutnya.
        if ($data['kategori'] === 'Lainnya' && ! empty($data['kategori_lainnya'])) {
            $namaBaru = trim($data['kategori_lainnya']);
            Kategori::firstOrCreate(['nama' => $namaBaru]);
            $data['kategori'] = $namaBaru;
        }
        unset($data['kategori_lainnya']);

        return $data;
    }
}