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
     * ============================================================
     * REGISTER
     * ============================================================
     *
     * Registrasi publik hanya boleh membuat akun dengan role "user".
     *
     * Role:
     * - admin
     * - helpdesk
     * - pegawai
     *
     * tidak boleh dipilih dari halaman register publik.
     */
    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI DATA
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

            'kategori_pengguna' => [
                'required',
                'string',
                'max:100',
            ],

            'captcha' => [
                'required',
                'string',
                'max:20',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI CAPTCHA
        |--------------------------------------------------------------------------
        |
        | CaptchaController::verify() akan:
        |
        | 1. mengambil captcha dari session
        | 2. mengecek masa berlaku
        | 3. membandingkan dengan input user
        | 4. menghapus captcha setelah digunakan
        |
        */

        if (
            ! CaptchaController::verify(
                $request,
                $validated['captcha']
            )
        ) {

            return response()->json([
                'message' => 'Captcha tidak sesuai atau sudah kedaluwarsa.',

                'errors' => [
                    'captcha' => [
                        'Captcha tidak sesuai atau sudah kedaluwarsa.',
                    ],
                ],
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT USER
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Role SELALU "user".
        |
        | User tidak boleh mengirim:
        |
        | role=admin
        | role=helpdesk
        | role=pegawai
        |
        | dari frontend.
        |
        */

        $user = User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => $validated['password'],

            'kategori_pengguna' =>
                $validated['kategori_pengguna'],

            'role' => 'user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        */

        event(new Registered($user));


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' =>
                'Registrasi berhasil. Silakan cek email untuk verifikasi akun.',
        ], 201);
    }


    /**
     * ============================================================
     * LOGIN
     * ============================================================
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],

            'captcha' => [
                'required',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CAPTCHA LOGIN
        |--------------------------------------------------------------------------
        */

        if (
            ! CaptchaController::verify(
                $request,
                $credentials['captcha']
            )
        ) {

            return response()->json([
                'message' =>
                    'Captcha tidak sesuai atau sudah kedaluwarsa.',

                'errors' => [
                    'captcha' => [
                        'Captcha tidak sesuai atau sudah kedaluwarsa.',
                    ],
                ],
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATION
        |--------------------------------------------------------------------------
        */

        if (
            ! Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ])
        ) {

            return response()->json([
                'message' =>
                    'Email atau password salah.',
            ], 422);
        }


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        */

        if (! $user->hasVerifiedEmail()) {

            Auth::logout();

            return response()->json([
                'message' =>
                    'Email belum diverifikasi. Silakan cek email Anda.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'message' =>
                'Login berhasil.',

            'user' => $user,
        ]);
    }


    /**
     * ============================================================
     * LOGOUT
     * ============================================================
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' =>
                'Logout berhasil.',
        ]);
    }


    /**
     * ============================================================
     * CURRENT USER
     * ============================================================
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }


    /**
     * ============================================================
     * RESEND EMAIL VERIFICATION
     * ============================================================
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {

            return response()->json([
                'message' =>
                    'Email sudah diverifikasi.',
            ]);
        }


        $request
            ->user()
            ->sendEmailVerificationNotification();


        return response()->json([
            'message' =>
                'Email verifikasi sudah dikirim ulang.',
        ]);
    }
}