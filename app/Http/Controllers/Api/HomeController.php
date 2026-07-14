<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data Home RoadFix',

            'data' => [
                'app_name' => 'RoadFix',
                'title' => 'LIHAT JALAN RUSAK? LAPORKAN LEWAT SINI.',
                'description' => 'RoadFix menjembatani laporan warga ke dinas terkait.',

                'statistik' => [
                    'total_laporan' => Laporan::count(),
                    'baru' => Laporan::where('status', 'BARU')->count(),
                    'diverifikasi' => Laporan::where('status', 'DIVERIFIKASI')->count(),
                    'diproses' => Laporan::where('status', 'DIPROSES')->count(),
                    'selesai' => Laporan::where('status', 'SELESAI')->count(),
                ]
            ]
        ]);
    }
}
