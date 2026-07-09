<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
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
}
