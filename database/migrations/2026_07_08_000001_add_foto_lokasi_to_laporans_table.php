<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('tanggal');      // path file di storage
            $table->decimal('latitude', 10, 7)->nullable()->after('foto');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('alamat')->nullable()->after('longitude');  // alamat/lokasi dalam teks
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['foto', 'latitude', 'longitude', 'alamat']);
        });
    }
};
