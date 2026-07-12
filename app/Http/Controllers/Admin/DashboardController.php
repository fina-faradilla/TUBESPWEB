<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total = Laporan::count();
        $menungguVerifikasi = Laporan::where('status', 'Menunggu Verifikasi')->count();
        $sedangDiproses = Laporan::where('status', 'Diproses')->count();
        $selesai = Laporan::where('status', 'Selesai')->count();
        $persenSelesai = $total === 0 ? 0 : round(($selesai / $total) * 100);

        $terbaru = Laporan::with('user')->latest()->take(4)->get();

        // Tren 6 bulan terakhir berdasarkan jumlah laporan per bulan.
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trend[] = [
                'label' => $this->bulanPendek($bulan->month),
                'value' => Laporan::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count(),
            ];
        }
        $maxTrend = max(1, collect($trend)->max('value'));

        return view('admin.dashboard', compact(
            'total', 'menungguVerifikasi', 'sedangDiproses', 'selesai',
            'persenSelesai', 'terbaru', 'trend', 'maxTrend'
        ));
    }

    private function bulanPendek(int $bulan): string
    {
        $nama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return $nama[$bulan - 1];
    }
}
