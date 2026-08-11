<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Layanan;
use App\Models\User;
use App\Models\TimKerja;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard($user),
            'helpdesk' => $this->helpdeskDashboard($user),
            'pegawai' => $this->pegawaiDashboard($user),
            'user' => $this->userDashboard($user),

            default => response()->json([
                'message' => 'Role tidak dikenali.'
            ], 403),
        };
    }

    /**
     * Dashboard Admin
     */
    private function adminDashboard($user)
    {
        $pengajuan = Pengajuan::query();

        return response()->json([
            'user' => $this->userData($user),

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

            'pengajuan_terbaru' => $this->latestPengajuan($pengajuan),
        ]);
    }

    /**
     * Dashboard Helpdesk
     */
    private function helpdeskDashboard($user)
    {
        $pengajuan = Pengajuan::query();

        return response()->json([
            'user' => $this->userData($user),

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

            'pengajuan_terbaru' => $this->latestPengajuan($pengajuan),
        ]);
    }

    /**
     * Dashboard Pegawai
     */
    private function pegawaiDashboard($user)
    {
        $timIds = $user->timKerjas()->pluck('tim_kerja.id');

        $pengajuan = Pengajuan::whereHas('riwayatDisposisis', function ($query) use ($timIds) {
            $query->whereIn('tim_kerja_id', $timIds);
        });

        return response()->json([
            'user' => $this->userData($user),

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

            'pengajuan_terbaru' => $this->latestPengajuan($pengajuan),
        ]);
    }

    /**
     * Dashboard User / Pemohon
     */
    private function userDashboard($user)
    {
        $pengajuan = Pengajuan::where('user_id', $user->id);

        return response()->json([
            'user' => $this->userData($user),

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

            'pengajuan_terbaru' => $this->latestPengajuan($pengajuan),
        ]);
    }

    /**
     * Pengajuan terbaru
     */
    private function latestPengajuan($query)
    {
        return $query
            ->with(['layanan', 'user'])
            ->latest('tanggal_pengajuan')
            ->take(5)
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
            });
    }

    /**
     * Data user
     */
    private function userData($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'kategori_pengguna' => $user->kategori_pengguna,
        ];
    }
}