<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            // Nama pelapor manual — hanya dipakai kalau laporan dibuat admin lewat
            // "Tambah Manual" (tidak ada akun warga / user_id). Kalau laporan berasal
            // dari akun warga, nama pelapor selalu diambil dari relasi user().
            $table->string('pelapor')->nullable();
            $table->string('judul');
            $table->string('kategori');
            $table->string('tingkat'); // Ringan / Sedang / Berat
            $table->string('alamat');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
