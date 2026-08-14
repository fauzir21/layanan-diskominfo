<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Daftar akun baru. Belum bisa login sebelum verifikasi email.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'kategori_pengguna' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'kategori_pengguna' => $validated['kategori_pengguna'],
            'role' => 'user',
        ]);

        // Kirim email verifikasi (pakai notifikasi bawaan Laravel)
        event(new Registered($user));

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan cek email untuk verifikasi akun.',
        ], 201);
    }

    /**
     * Login. Ditolak kalau captcha salah, email/password salah,
     * atau email belum diverifikasi.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'string'],
        ]);

        // Captcha dicek & langsung "dipakai habis" di dalam verify(),
        // jadi tiap percobaan login butuh captcha baru.
        if (! CaptchaController::verify($request, $credentials['captcha'])) {
            return response()->json([
                'message' => 'Captcha tidak sesuai atau sudah kedaluwarsa.',
                'errors' => ['captcha' => ['Captcha tidak sesuai atau sudah kedaluwarsa.']],
            ], 422);
        }

        if (! Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ])) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 422);
        }

        $user = Auth::user();

        if (! $user->hasVerifiedEmail()) {
            Auth::logout();

            return response()->json([
                'message' => 'Email belum diverifikasi. Silakan cek email Anda.',
            ], 403);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
        ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * User yang lagi login sekarang (dipanggil Vue pas app pertama kali load,
     * buat ngecek status login).
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Kirim ulang email verifikasi.
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email sudah diverifikasi.',
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Email verifikasi sudah dikirim ulang.',
        ]);
    }
}