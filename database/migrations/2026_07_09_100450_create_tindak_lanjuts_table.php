<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laporan_id');
            $table->string('judul');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('laporan_id')->references('id')->on('laporans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};
