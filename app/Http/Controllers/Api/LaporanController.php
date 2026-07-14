<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * GET /api/laporan
     * Riwayat laporan milik user login
     */
    public function index(Request $request)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $laporan = Laporan::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil diambil',
            'data' => $laporan
        ]);
    }

    /**
     * GET /api/laporan/{id}
     * Detail laporan
     */
    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * POST /api/laporan
     * Tambah laporan
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('laporan', 'public');
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $laporan = Laporan::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tingkat' => $request->tingkat,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Menunggu Verifikasi'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data' => $laporan
        ], 201);
    }

    /**
     * PUT /api/laporan/{id}
     * Edit laporan
     */
    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'tingkat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        if ($request->hasFile('foto')) {

            if ($laporan->foto) {
                Storage::disk('public')->delete($laporan->foto);
            }

            $laporan->foto = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->judul = $request->judul;
        $laporan->kategori = $request->kategori;
        $laporan->tingkat = $request->tingkat;
        $laporan->alamat = $request->alamat;
        $laporan->deskripsi = $request->deskripsi;
        $laporan->latitude = $request->latitude;
        $laporan->longitude = $request->longitude;

        $laporan->save();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diupdate',
            'data' => $laporan
        ]);
    }

    /**
     * DELETE /api/laporan/{id}
     * Soft Delete
     */
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dipindahkan ke sampah'
        ]);
    }

    /**
     * GET /api/laporan-sampah
     */
    public function trash()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $laporan = Laporan::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * PUT /api/laporan/{id}/restore
     */
    public function restore($id)
    {
        $laporan = Laporan::onlyTrashed()->findOrFail($id);

        $laporan->restore();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikembalikan'
        ]);
    }

    /**
     * DELETE /api/laporan/{id}/force-delete
     */
    public function forceDelete($id)
    {
        $laporan = Laporan::onlyTrashed()->findOrFail($id);

        if ($laporan->foto) {
            Storage::disk('public')->delete($laporan->foto);
        }

        $laporan->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus permanen'
        ]);
    }
}