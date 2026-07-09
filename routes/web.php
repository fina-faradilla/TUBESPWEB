<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Portal Warga: klik "Buat Laporan" / "Riwayat Laporan Saya" saat belum login
    // otomatis diarahkan ke halaman login (lalu kembali ke halaman ini setelah berhasil masuk).
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

