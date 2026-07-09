<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['kode' => 'JK-0143', 'judul' => 'Jalan Berlubang Besar',  'pelapor' => 'Desti R.',    'kategori' => 'Berlubang', 'status' => 'BARU',         'tanggal' => '2026-07-03'],
            ['kode' => 'JK-0142', 'judul' => 'Aspal Retak Parah',      'pelapor' => 'Fina F.',     'kategori' => 'Retak',     'status' => 'DIPROSES',     'tanggal' => '2026-06-29'],
            ['kode' => 'JK-0141', 'judul' => 'Jembatan Rusak',         'pelapor' => 'Gita R.',     'kategori' => 'Jembatan',  'status' => 'DIVERIFIKASI', 'tanggal' => '2026-06-25'],
            ['kode' => 'JK-0140', 'judul' => 'Jalan Ambles Sebagian',  'pelapor' => 'Aisyiyah Z.', 'kategori' => 'Ambles',    'status' => 'SELESAI',      'tanggal' => '2026-06-18'],
        ];

        foreach ($rows as $row) {
            Laporan::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
