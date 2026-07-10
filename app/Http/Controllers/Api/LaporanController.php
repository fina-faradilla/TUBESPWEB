<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;

class LaporanController extends Controller
{
    public function show($id)
    {
        $laporan = Laporan::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $laporan,
        ]);
    }
}
