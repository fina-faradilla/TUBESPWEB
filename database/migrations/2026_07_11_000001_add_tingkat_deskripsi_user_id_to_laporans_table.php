<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (!Schema::hasColumn('laporans', 'tingkat')) {
                $table->string('tingkat')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('laporans', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('laporans', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')
                      ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (Schema::hasColumn('laporans', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('laporans', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('laporans', 'tingkat')) {
                $table->dropColumn('tingkat');
            }
        });
    }
};
