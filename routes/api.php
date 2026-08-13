<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ================= AUTH =================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/email/resend', [AuthController::class, 'resendVerification']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// ================= LAYANAN (PUBLIK) =================
Route::get('/layanan', [LayananController::class, 'index']);
Route::get('/layanan/{slug}', [LayananController::class, 'show']);

// ================= LAYANAN (ADMIN) =================
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::post('/layanan', [LayananController::class, 'store']);
    Route::put('/layanan/{layanan}', [LayananController::class, 'update']);
    Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy']);
});

// ================= PENGAJUAN =================
Route::get('/pengajuan', [PengajuanController::class, 'index'])->middleware('auth:sanctum');
Route::post('/pengajuan', [PengajuanController::class, 'store'])->middleware('auth:sanctum');
Route::get('/pengajuan/lacak/{nomorTiket}', [PengajuanController::class, 'lacak']);
Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->middleware('auth:sanctum')->where('id', '[0-9]+');

// ================= HELPDESK =================
Route::middleware(['auth:sanctum', 'role:helpdesk'])->prefix('helpdesk')->group(function () {
    Route::get('/pengajuan', [PengajuanController::class, 'helpdeskIndex']);
    Route::post('/pengajuan/{id}/proses', [PengajuanController::class, 'helpdeskUpdateStatus']);
});

// ================= PEGAWAI =================
Route::middleware(['auth:sanctum', 'role:pegawai'])->prefix('pegawai')->group(function () {
    Route::get('/pengajuan', [PengajuanController::class, 'pegawaiIndex']);
    Route::post('/pengajuan/{id}/proses', [PengajuanController::class, 'pegawaiUpdateStatus']);
});