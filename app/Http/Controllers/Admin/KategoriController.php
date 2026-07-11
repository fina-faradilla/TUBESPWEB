<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    /** Halaman "Kelola Kategori" — daftar + tambah/ubah/hapus. */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama')->get()->map(function ($kategori) {
            $kategori->jumlah_laporan = $kategori->jumlahLaporan();
            return $kategori;
        });

        return view('admin.kelola-kategori', compact('kategoris'));
    }

    /** Simpan kategori baru. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategoris', 'nama')],
        ]);

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori "' . $data['nama'] . '" berhasil ditambahkan');
    }

    /** Ubah nama kategori (otomatis ikut memperbarui laporan yang memakainya). */
    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kategoris', 'nama')->ignore($kategori->id)],
        ]);

        $namaLama = $kategori->nama;

        $kategori->update($data);

        // Sinkronkan laporan lama yang masih memakai nama kategori sebelumnya.
        if ($namaLama !== $data['nama']) {
            Laporan::where('kategori', $namaLama)->update(['kategori' => $data['nama']]);
        }

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /** Hapus kategori (ditolak kalau masih dipakai laporan). */
    public function destroy(Kategori $kategori)
    {
        if ($kategori->jumlahLaporan() > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama . '" masih dipakai ' . $kategori->jumlahLaporan() . ' laporan, tidak bisa dihapus.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
