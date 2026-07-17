<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
    /** Tambah update progress baru untuk satu laporan. */
    public function store(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $laporan->tindakLanjuts()->create($data);

        return redirect()->route('admin.laporan.show', $laporan)
            ->with('success', 'Tindak lanjut berhasil ditambahkan');
    }

    /** Edit satu entry tindak lanjut (misal salah ketik). */
    public function update(Request $request, Laporan $laporan, TindakLanjut $tindakLanjut)
    {
        $data = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        $tindakLanjut->update($data);

        return redirect()->route('admin.laporan.show', $laporan)
            ->with('success', 'Tindak lanjut berhasil diperbarui');
    }

    /** Hapus satu entry tindak lanjut. */
    public function destroy(Laporan $laporan, TindakLanjut $tindakLanjut)
    {
        $tindakLanjut->delete();

        return redirect()->route('admin.laporan.show', $laporan)
            ->with('success', 'Tindak lanjut berhasil dihapus');
    }
}