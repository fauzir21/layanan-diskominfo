<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\TimKerja;
use App\Models\User;
use App\Models\Layanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                return response()->json(
                    $this->adminDashboard($user)
                );

            case 'helpdesk':
                return response()->json(
                    $this->helpdeskDashboard($user)
                );

            case 'pegawai':
                return response()->json(
                    $this->pegawaiDashboard($user)
                );

            case 'user':
                return response()->json(
                    $this->pemohonDashboard($user)
                );

            default:
                return response()->json([
                    'message' => 'Role tidak dikenali.',
                ], 403);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    private function adminDashboard(User $user): array
    {
        $pengajuan = Pengajuan::query();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],

            'statistics' => [
                'total_pengajuan' => (clone $pengajuan)->count(),

                'menunggu_diproses' => (clone $pengajuan)
                    ->where('status', 'menunggu_diproses')
                    ->count(),

                'diproses' => (clone $pengajuan)
                    ->where('status', 'diproses')
                    ->count(),

                'perbaikan' => (clone $pengajuan)
                    ->where('status', 'perbaikan')
                    ->count(),

                'selesai' => (clone $pengajuan)
                    ->where('status', 'selesai')
                    ->count(),

                'ditolak' => (clone $pengajuan)
                    ->where('status', 'ditolak')
                    ->count(),
            ],

            'system' => [
                'total_user' => User::count(),
                'total_layanan' => Layanan::count(),
                'total_tim_kerja' => TimKerja::count(),
            ],

            'pengajuan_terbaru' => $this->latestPengajuan(
                $pengajuan
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HELPDESK
    |--------------------------------------------------------------------------
    */

    private function helpdeskDashboard(User $user): array
    {
        $pengajuan = Pengajuan::query();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],

            'statistics' => [
                'total_pengajuan' => (clone $pengajuan)->count(),

                'menunggu_diproses' => (clone $pengajuan)
                    ->where('status', 'menunggu_diproses')
                    ->count(),

                'diproses' => (clone $pengajuan)
                    ->where('status', 'diproses')
                    ->count(),

                'perbaikan' => (clone $pengajuan)
                    ->where('status', 'perbaikan')
                    ->count(),

                'selesai' => (clone $pengajuan)
                    ->where('status', 'selesai')
                    ->count(),

                'ditolak' => (clone $pengajuan)
                    ->where('status', 'ditolak')
                    ->count(),
            ],

            'pengajuan_terbaru' => $this->latestPengajuan(
                $pengajuan
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PEGAWAI
    |--------------------------------------------------------------------------
    */

    private function pegawaiDashboard(User $user): array
    {
        /*
         * Ambil ID Tim Kerja milik pegawai.
         *
         * Relasi User -> TimKerja menggunakan tabel:
         * tim_kerja_user
         *
         * Tabel model TimKerja adalah:
         * tim_kerjas
         */

        $timKerjaIds = $user
            ->timKerjas()
            ->pluck('tim_kerjas.id');


        /*
         * Jika pegawai belum memiliki Tim Kerja,
         * kembalikan dashboard kosong.
         */

        if ($timKerjaIds->isEmpty()) {
            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],

                'statistics' => [
                    'total_pengajuan' => 0,
                    'menunggu_diproses' => 0,
                    'diproses' => 0,
                    'perbaikan' => 0,
                    'selesai' => 0,
                    'ditolak' => 0,
                ],

                'pengajuan_terbaru' => [],

                'tim_kerja' => [],
            ];
        }


        /*
         * Ambil pengajuan yang memiliki riwayat disposisi
         * ke Tim Kerja pegawai tersebut.
         */

        $pengajuan = Pengajuan::query()
            ->whereHas('riwayatDisposisis', function ($query) use ($timKerjaIds) {
                $query->whereIn(
                    'tim_kerja_id',
                    $timKerjaIds
                );
            });


        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],

            'statistics' => [
                'total_pengajuan' => (clone $pengajuan)->count(),

                'menunggu_diproses' => (clone $pengajuan)
                    ->where('status', 'menunggu_diproses')
                    ->count(),

                'diproses' => (clone $pengajuan)
                    ->where('status', 'diproses')
                    ->count(),

                'perbaikan' => (clone $pengajuan)
                    ->where('status', 'perbaikan')
                    ->count(),

                'selesai' => (clone $pengajuan)
                    ->where('status', 'selesai')
                    ->count(),

                'ditolak' => (clone $pengajuan)
                    ->where('status', 'ditolak')
                    ->count(),
            ],

            'pengajuan_terbaru' => $this->latestPengajuan(
                $pengajuan
            ),

            'tim_kerja' => $timKerjaIds,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PEMOHON / USER
    |--------------------------------------------------------------------------
    */

    private function pemohonDashboard(User $user): array
    {
        $pengajuan = Pengajuan::query()
            ->where('user_id', $user->id);

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],

            'statistics' => [
                'total_pengajuan' => (clone $pengajuan)->count(),

                'menunggu_diproses' => (clone $pengajuan)
                    ->where('status', 'menunggu_diproses')
                    ->count(),

                'diproses' => (clone $pengajuan)
                    ->where('status', 'diproses')
                    ->count(),

                'perbaikan' => (clone $pengajuan)
                    ->where('status', 'perbaikan')
                    ->count(),

                'selesai' => (clone $pengajuan)
                    ->where('status', 'selesai')
                    ->count(),

                'ditolak' => (clone $pengajuan)
                    ->where('status', 'ditolak')
                    ->count(),
            ],

            'pengajuan_terbaru' => $this->latestPengajuan(
                $pengajuan
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PENGAJUAN TERBARU
    |--------------------------------------------------------------------------
    */

    private function latestPengajuan($query)
    {
        return $query
            ->with([
                'layanan:id,nama',
                'user:id,name',
            ])
            ->latest('tanggal_pengajuan')
            ->latest('id')
            ->limit(5)
            ->get()
            ->map(function ($pengajuan) {
                return [
                    'id' => $pengajuan->id,
                    'nomor_tiket' => $pengajuan->nomor_tiket,

                    'layanan' => $pengajuan->layanan?->nama,

                    'nama_pemohon' => $pengajuan->user?->name,

                    'status' => $pengajuan->status,

                    'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
                ];
            })
            ->values();
    }
}