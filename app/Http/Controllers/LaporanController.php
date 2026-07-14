<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
=======
use Illuminate\Http\Request;
>>>>>>> origin/fina

class LaporanController extends Controller
{
    /**
<<<<<<< HEAD
     * Tampilkan daftar laporan (Riwayat Laporan Saya).
     */
    public function index(Request $request)
{
    $query = $request->input('q', '');
    $filterKategori = $request->input('kategori', 'Semua Kategori');
    $filterStatus = $request->input('status', 'Semua Status');

    $laporans = Laporan::query()
        ->where('user_id', auth()->id()) // hanya laporan milik warga yang login
        ->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('judul', 'like', "%{$query}%")
                    ->orWhere('alamat', 'like', "%{$query}%")
                    ->orWhere('kode', 'like', "%{$query}%");
            });
        })
        ->when($filterKategori !== 'Semua Kategori', function ($q) use ($filterKategori) {
            $q->where('kategori', $filterKategori);
        })
        ->when($filterStatus !== 'Semua Status', function ($q) use ($filterStatus) {
            $q->where('status', $filterStatus);
        })
        ->latest()
        ->paginate(3)
        ->withQueryString(); // supaya filter tetap terbawa saat pindah halaman pagination

    $kategoriOptions = Laporan::where('user_id', auth()->id())
        ->distinct()
        ->pluck('kategori');

    $statusOptions = Laporan::STATUS_OPTIONS;

    return view('laporan.index', compact(
        'laporans', 'query', 'filterKategori', 'filterStatus', 'kategoriOptions', 'statusOptions'
    ));
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
    $validated['pelapor'] = auth()->user()->name ?? 'Anonim';
    $validated['kode'] = 'JK-' . str_pad((Laporan::max('id') + 1), 4, '0', STR_PAD_LEFT);
    $validated['tanggal'] = now();

    $laporan = Laporan::create($validated);

    // Catat log pertama supaya langsung muncul di "Riwayat Tindak Lanjut"
    $laporan->tindakLanjuts()->create([
        'judul'      => 'Laporan diterima',
        'keterangan' => 'Masuk ke sistem dan menunggu verifikasi admin.',
    ]);

    return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
}
    /**
     * Tampilkan detail satu laporan.
     */
    public function show(Laporan $laporan)
{
    $laporan->load('tindakLanjuts');
    return view('laporan.show', compact('laporan'));
}

    /**
     * Tampilkan form edit laporan.
     */
    public function edit(Laporan $laporan)
{
    if ($laporan->status !== 'Menunggu Verifikasi') {
        return redirect()->route('laporan.index')
            ->with('error', 'Laporan yang sudah diverifikasi/ditolak tidak dapat diubah lagi.');
    }

    return view('laporan.edit', compact('laporan'));
}

    /**
     * Update laporan.
     */
   public function update(Request $request, Laporan $laporan)
{
    if ($laporan->status !== 'Menunggu Verifikasi') {
        return redirect()->route('laporan.index')
            ->with('error', 'Laporan yang sudah diverifikasi/ditolak tidak dapat diubah lagi.');
    }

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
    if ($laporan->status !== 'Menunggu Verifikasi') {
        return redirect()->route('laporan.index')
            ->with('error', 'Laporan yang sudah diverifikasi/ditolak tidak dapat dihapus.');
    }

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
=======
     * Riwayat Laporan Saya (placeholder — akan diganti dengan data laporan milik user).
     */
    public function index(Request $request)
    {
        return view('laporan.index');
    }

    /**
     * Buat Laporan (placeholder — akan diganti dengan form + penyimpanan laporan).
     */
    public function create(Request $request)
    {
        return view('laporan.create');
    }
>>>>>>> origin/fina
}
