<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayananController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
});

// ================= LAYANAN (PUBLIK) =================
Route::get('/layanan', [LayananController::class, 'index']);
Route::get('/layanan/{slug}', [LayananController::class, 'show']);

// ================= LAYANAN (ADMIN) =================
Route::middleware(['auth:sanctum', 'is.admin'])->prefix('admin')->group(function () {
    Route::post('/layanan', [LayananController::class, 'store']);
    Route::put('/layanan/{layanan}', [LayananController::class, 'update']);
    Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy']);
});