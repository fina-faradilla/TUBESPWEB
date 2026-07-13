<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('laporans', function (Blueprint $table) {
        $table->renameColumn('kode_laporan', 'kode');
        $table->date('tanggal')->nullable()->after('kode');
    });
}

public function down(): void
{
    Schema::table('laporans', function (Blueprint $table) {
        $table->renameColumn('kode', 'kode_laporan');
        $table->dropColumn('tanggal');
    });
}
};
