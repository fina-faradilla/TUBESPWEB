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
            $table->string('kode', 20)->unique();       // JK-0143
            $table->string('judul');
            $table->string('pelapor');
            $table->string('kategori');                  // Berlubang, Retak, Jembatan, Ambles, Lainnya
            $table->string('status')->default('BARU');   // BARU, DIVERIFIKASI, DIPROSES, SELESAI
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
