<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->timestamps();
        });

        // Kategori bawaan (default).
        $default = ['Berlubang', 'Retak', 'Jembatan', 'Ambles'];
        foreach ($default as $nama) {
            DB::table('kategoris')->insert([
                'nama'       => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Kalau ada laporan lama dengan kategori custom (di luar daftar bawaan
        // di atas), daftarkan juga supaya tidak "hilang" dari pilihan.
        if (Schema::hasTable('laporans')) {
            $existing = DB::table('laporans')
                ->whereNotNull('kategori')
                ->where('kategori', '!=', '')
                ->where('kategori', '!=', 'Lainnya')
                ->distinct()
                ->pluck('kategori');

            foreach ($existing as $nama) {
                if (!in_array($nama, $default, true)) {
                    DB::table('kategoris')->insertOrIgnore([
                        'nama'       => $nama,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
