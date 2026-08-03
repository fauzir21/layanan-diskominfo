<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Link verifikasi email — harus di atas catch-all
Route::get('/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Link verifikasi tidak valid.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect('/?verified=1');
})->middleware(['signed'])->name('verification.verify');

// Catch-all buat SPA Vue — HARUS PALING BAWAH
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');