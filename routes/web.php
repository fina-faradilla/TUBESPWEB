<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman landing/welcome sebagai halaman utama.
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Semua route laporan warga wajib login dulu
Route::middleware('auth')->group(function () {
    // Resource route otomatis meng-cover: index, create, store, show, edit, update, destroy
    // (mis. GET /laporan, GET /laporan/create, POST /laporan, GET /laporan/{id}, dst.)
    Route::resource('laporan', LaporanController::class);

    // Halaman "Sampah" (soft delete) laporan warga.
    Route::get('laporan-sampah', [LaporanController::class, 'trashed'])->name('laporan.trashed');
    Route::patch('laporan/{id}/restore', [LaporanController::class, 'restore'])->name('laporan.restore');
    Route::delete('laporan/{id}/force-delete', [LaporanController::class, 'forceDelete'])->name('laporan.forceDelete');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';