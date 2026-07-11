<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Tampilkan daftar laporan (Riwayat Laporan Saya).
     */
    public function index()
    {
        $laporans = Laporan::latest()->paginate(10);
        return view('laporan.index', compact('laporans'));
    }

    /**
     * Tampilkan form Buat Laporan Baru.
     */
    public function create()
    {
        return view('laporan.create');
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        }

        $validated['user_id'] = auth()->id();

        Laporan::create($validated);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }

    /**
     * Tampilkan detail satu laporan.
     */
    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    /**
     * Tampilkan form edit laporan.
     */
    public function edit(Laporan $laporan)
    {
        return view('laporan.edit', compact('laporan'));
    }

    /**
     * Update laporan.
     */
    public function update(Request $request, Laporan $laporan)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('foto')) {
            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('laporan-foto', 'public');
        }

        $laporan->update($validated);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Hapus laporan.
     */
    public function destroy(Laporan $laporan)
    {
        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }
        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Validasi input form laporan.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string', 'max:100'],
            'tingkat'   => ['required', 'in:Ringan,Sedang,Berat'],
            'alamat'    => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'foto'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'latitude'  => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);
    }

    public function trashed()
{
    $laporans = Laporan::onlyTrashed()
        ->when(Auth::check(), fn ($q) => $q->where('user_id', Auth::id()))
        ->latest('deleted_at')
        ->paginate(10);

    return view('laporan.trashed', compact('laporans'));
}

public function restore($id)
{
    $laporan = Laporan::onlyTrashed()->findOrFail($id);
    $laporan->restore();

    return redirect()
        ->route('laporan.trashed')
        ->with('success', 'Laporan berhasil dipulihkan.');
}

public function forceDelete($id)
{
    $laporan = Laporan::onlyTrashed()->findOrFail($id);

    if ($laporan->foto) {
        Storage::disk('public')->delete($laporan->foto);
    }

    $laporan->forceDelete();

    return redirect()
        ->route('laporan.trashed')
        ->with('success', 'Laporan dihapus permanen.');
}
}
