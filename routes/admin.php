<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Portal Admin / Dinas
|--------------------------------------------------------------------------
| File ini SENGAJA dipisah dari routes/web.php supaya gampang digabungkan
| tanpa bentrok dengan route punya owner/anggota tim lain.
|
| Cara pakai — tinggal tambahkan baris ini di routes/web.php:
|
|     require __DIR__.'/admin.php';
|
| Nanti kalau owner sudah selesai bikin fitur login, tinggal bungkus
| grup ini dengan middleware auth, misalnya:
|
|     Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
|         ...isi grup di bawah ini...
|     });
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/manage-report', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/manage-report', [LaporanController::class, 'store'])->name('laporan.store');
    Route::put('/manage-report/{laporan}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/manage-report/{laporan}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    Route::patch('/manage-report/{laporan}/verifikasi', [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');
    Route::get('/manage-report/{laporan}', [LaporanController::class, 'show'])->name('laporan.show');
});
