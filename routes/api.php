<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LaporanController;

Route::get('/home', [HomeController::class, 'index']);

// AUTH
Route::post('/daftar', [AuthController::class, 'register']);
Route::post('/masuk', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/keluar', [AuthController::class, 'logout']);

    // LAPORAN
    Route::get('/laporan', [LaporanController::class, 'index']);
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::get('/laporan/{id}', [LaporanController::class, 'show']);
    Route::put('/laporan/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);

    // Sampah
    Route::get('/laporan-sampah', [LaporanController::class, 'trash']);
    Route::put('/laporan/{id}/restore', [LaporanController::class, 'restore']);
    Route::delete('/laporan/{id}/force-delete', [LaporanController::class, 'forceDelete']);
});