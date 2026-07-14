<?php

namespace Database\Seeders;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama, atau buat user demo kalau belum ada sama sekali
        $user = User::first() ?? User::factory()->create([
            'name' => 'Warga Demo',
            'email' => 'warga@demo.test',
        ]);

        $rows = [
            [
                'judul' => 'Jalan Berlubang Besar', 'pelapor' => 'Desti R.', 'kategori' => 'Berlubang',
                'tingkat' => 'Berat', 'alamat' => 'Jl. Merdeka No. 12, Bandung',
                'deskripsi' => 'Lubang cukup dalam dan membahayakan pengendara motor pada malam hari.',
                'status' => 'Menunggu Verifikasi',
            ],
            [
                'judul' => 'Aspal Retak Parah', 'pelapor' => 'Fina F.', 'kategori' => 'Retak',
                'tingkat' => 'Sedang', 'alamat' => 'Jl. Asia Afrika, Bandung',
                'deskripsi' => 'Retakan memanjang sekitar 5 meter di sisi kiri jalan.',
                'status' => 'Diproses',
            ],
            [
                'judul' => 'Jembatan Rusak', 'pelapor' => 'Gita R.', 'kategori' => 'Jembatan',
                'tingkat' => 'Berat', 'alamat' => 'Jembatan Cikapundung, Bandung',
                'deskripsi' => 'Sebagian pagar pembatas jembatan roboh.',
                'status' => 'Diproses',
            ],
            [
                'judul' => 'Jalan Ambles Sebagian', 'pelapor' => 'Aisyiyah Z.', 'kategori' => 'Ambles',
                'tingkat' => 'Ringan', 'alamat' => 'Jl. Dago, Bandung',
                'deskripsi' => 'Penurunan permukaan jalan sekitar 5 cm, sudah ditangani.',
                'status' => 'Selesai',
            ],
        ];

        foreach ($rows as $row) {
            $row['user_id'] = $user->id; // <-- tambahan penting

            $laporan = Laporan::updateOrCreate(
                ['judul' => $row['judul'], 'pelapor' => $row['pelapor']],
                $row
            );

            if ($laporan->tindakLanjuts()->count() === 0) {
                $laporan->tindakLanjuts()->create([
                    'judul'      => 'Laporan diterima',
                    'keterangan' => 'Masuk ke sistem dan menunggu verifikasi admin.',
                ]);

                if (in_array($row['status'], ['Diproses', 'Selesai'])) {
                    $laporan->tindakLanjuts()->create([
                        'judul'      => 'Diverifikasi oleh Admin',
                        'keterangan' => 'Laporan valid, diteruskan ke Dinas PUPR.',
                    ]);
                }

                if ($row['status'] === 'Selesai') {
                    $laporan->tindakLanjuts()->create([
                        'judul'      => 'Perbaikan selesai',
                        'keterangan' => 'Laporan telah ditindaklanjuti dan ditutup.',
                    ]);
                }
            }
        }
    }
}