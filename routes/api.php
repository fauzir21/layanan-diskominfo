<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


// ================= USER =================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ================= AUTH =================

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/captcha', [CaptchaController::class, 'generate']);


// ================= AUTHENTICATED =================

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::post(
        '/email/resend',
        [AuthController::class, 'resendVerification']
    );


    // Dashboard
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );


    // ================= PERMOHONAN =================

    // Daftar permohonan
    Route::get(
        '/permohonan',
        [PengajuanController::class, 'index']
    );


    // Detail permohonan
    Route::get(
        '/permohonan/{pengajuan}',
        [PengajuanController::class, 'show']
    );


    // Disposisi permohonan
    Route::post(
        '/permohonan/{pengajuan}/disposisi',
        [PengajuanController::class, 'disposisi']
    );


    // ================= TIM KERJA =================

    Route::get(
        '/tim-kerja',
        [PengajuanController::class, 'timKerja']
    );


    // ================= PENGAJUAN PEMOHON =================

    // Membuat pengajuan baru
    Route::post(
        '/pengajuan',
        [PengajuanController::class, 'store']
    );


    // Detail pengajuan berdasarkan ID
    Route::get(
        '/pengajuan/{id}',
        [PengajuanController::class, 'show']
    )->where('id', '[0-9]+');

});


// ================= LAYANAN PUBLIK =================

Route::get(
    '/layanan',
    [LayananController::class, 'index']
);

Route::get(
    '/layanan/{slug}',
    [LayananController::class, 'show']
);


// ================= LAYANAN ADMIN =================

Route::middleware([
    'auth:sanctum',
    'role:admin'
])
    ->prefix('admin')
    ->group(function () {

        Route::post(
            '/layanan',
            [LayananController::class, 'store']
        );

        Route::put(
            '/layanan/{layanan}',
            [LayananController::class, 'update']
        );

        Route::delete(
            '/layanan/{layanan}',
            [LayananController::class, 'destroy']
        );
    });


// ================= LACAK PERMOHONAN =================

// Endpoint ini publik karena nomor tiket
// memang digunakan untuk tracking dari halaman publik.

Route::get(
    '/pengajuan/lacak/{nomorTiket}',
    [PengajuanController::class, 'lacak']
);