<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanApiController extends Controller
{
    /**
     * Menampilkan seluruh data laporan
     */
    public function index()
    {
        $laporan = Laporan::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar laporan berhasil diambil',
            'data' => $laporan
        ], 200);
    }

    /**
     * Menyimpan laporan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'kategori'   => 'required|string|max:255',
            'tingkat'    => 'required|string',
            'alamat'     => 'required|string',
            'deskripsi'  => 'required|string',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'foto'       => 'nullable|string',
        ]);

        $laporan = Laporan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil ditambahkan',
            'data' => $laporan
        ], 201);
    }

    /**
     * Menampilkan detail laporan berdasarkan ID
     */
    public function show(string $id)
    {
        $laporan = Laporan::with('user')->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $laporan
        ], 200);
    }

    /**
     * Memperbarui laporan
     */
    public function update(Request $request, string $id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $laporan->update($request->only([
            'judul',
            'kategori',
            'tingkat',
            'alamat',
            'deskripsi',
            'status',
            'latitude',
            'longitude',
            'foto'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data' => $laporan
        ], 200);
    }

    /**
     * Menghapus laporan
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ], 200);
    }
}