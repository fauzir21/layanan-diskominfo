<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TimKerja;
use App\Models\RiwayatDisposisi;

class PengajuanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST PERMOHONAN
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        /*
         * Admin dan Helpdesk:
         * dapat melihat seluruh permohonan.
         */

        if (in_array($user->role, ['admin', 'helpdesk'])) {
            $query = Pengajuan::query()
                ->with([
                    'layanan:id,nama',
                    'user:id,name,email',
                ]);
        }

        /*
         * Pegawai:
         * hanya melihat permohonan yang pernah didisposisikan
         * kepada Tim Kerja miliknya.
         */

        elseif ($user->role === 'pegawai') {
            $timKerjaIds = $user
                ->timKerjas()
                ->pluck('tim_kerjas.id');

            $query = Pengajuan::query()
                ->whereHas('riwayatDisposisis', function ($q) use ($timKerjaIds) {
                    $q->whereIn('tim_kerja_id', $timKerjaIds);
                })
                ->with([
                    'layanan:id,nama',
                    'user:id,name,email',
                    'riwayatDisposisis.timKerja:id,nama_tim',
                    'riwayatDisposisis.handledBy:id,name',
                ]);
        }

        /*
         * User/pemohon:
         * hanya melihat permohonan miliknya sendiri.
         */

        elseif ($user->role === 'user') {
            $query = Pengajuan::query()
                ->where('user_id', $user->id)
                ->with([
                    'layanan:id,nama',
                    'user:id,name,email',
                ]);
        }

        else {
            return response()->json([
                'message' => 'Role tidak memiliki akses.'
            ], 403);
        }


        /*
         * Filter status
         */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }


        /*
         * Search
         */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'nomor_tiket',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('layanan', function ($layananQuery) use ($search) {
                    $layananQuery
                        ->where('nama', 'like', "%{$search}%");
                });
            });
        }


        $pengajuans = $query
            ->latest('tanggal_pengajuan')
            ->latest('id')
            ->paginate(10);


        return response()->json($pengajuans);
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL PERMOHONAN
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        Pengajuan $pengajuan
    ): JsonResponse {
        $user = $request->user();


        /*
         * User hanya boleh melihat miliknya sendiri.
         */

        if (
            $user->role === 'user' &&
            $pengajuan->user_id !== $user->id
        ) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke permohonan ini.'
            ], 403);
        }


        /*
         * Pegawai hanya boleh melihat permohonan
         * yang masuk ke Tim Kerjanya.
         */

        if ($user->role === 'pegawai') {
            $timKerjaIds = $user
                ->timKerjas()
                ->pluck('tim_kerjas.id');

            $hasAccess = $pengajuan
                ->riwayatDisposisis()
                ->whereIn(
                    'tim_kerja_id',
                    $timKerjaIds
                )
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke permohonan ini.'
                ], 403);
            }
        }


        $pengajuan->load([
            'layanan.persyaratans',
            'user:id,name,email',
            'dokumens.persyaratan',
            'riwayatDisposisis.timKerja',
            'riwayatDisposisis.handledBy:id,name',
        ]);


        return response()->json([
            'data' => $pengajuan,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE PENGAJUAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();


        $validated = $request->validate([
            'layanan_id' => [
                'required',
                'exists:layanans,id',
            ],
        ]);


        $pengajuan = DB::transaction(function () use (
            $validated,
            $user
        ) {
            return Pengajuan::create([
                'layanan_id' => $validated['layanan_id'],
                'user_id' => $user->id,
                'nomor_tiket' => $this->generateTicketNumber(),
                'status' => 'menunggu_diproses',
                'tanggal_pengajuan' => now()->toDateString(),
            ]);
        });


        $pengajuan->load([
            'layanan',
            'user',
        ]);


        return response()->json([
            'message' => 'Pengajuan berhasil dibuat.',
            'data' => $pengajuan,
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | TRACKING
    |--------------------------------------------------------------------------
    */

    public function lacak(
        string $nomorTiket
    ): JsonResponse {
        $pengajuan = Pengajuan::query()
            ->where('nomor_tiket', $nomorTiket)
            ->with([
                'layanan',
                'riwayatDisposisis.timKerja',
                'riwayatDisposisis.handledBy:id,name',
            ])
            ->first();


        if (!$pengajuan) {
            return response()->json([
                'message' => 'Nomor tiket tidak ditemukan.'
            ], 404);
        }


        return response()->json([
            'data' => $pengajuan,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NOMOR TIKET
    |--------------------------------------------------------------------------
    */

    private function generateTicketNumber(): string
    {
        do {
            $ticket = 'TKT-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );
        } while (
            Pengajuan::where(
                'nomor_tiket',
                $ticket
            )->exists()
        );


        return $ticket;
    }

    public function timKerja(Request $request): JsonResponse
    {
        if (!in_array($request->user()->role, ['admin', 'helpdesk'])) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        $timKerjas = TimKerja::query()
            ->withCount('users')
            ->orderBy('nama_tim')
            ->get();

        return response()->json([
            'data' => $timKerjas,
        ]);
    }

    public function disposisi(
        Request $request,
        Pengajuan $pengajuan
    ): JsonResponse {
        if (!in_array($request->user()->role, ['admin', 'helpdesk'])) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        $validated = $request->validate([
            'tim_kerja_id' => [
                'required',
                'exists:tim_kerjas,id',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $timKerja = TimKerja::findOrFail(
            $validated['tim_kerja_id']
        );

        DB::transaction(function () use (
            $request,
            $pengajuan,
            $validated,
            $timKerja
        ) {
            RiwayatDisposisi::create([
                'pengajuan_id' => $pengajuan->id,
                'tim_kerja_id' => $timKerja->id,
                'handled_by' => $request->user()->id,
                'status' => 'diproses',
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_disposisi' => now(),
            ]);

            $pengajuan->update([
                'status' => 'diproses',
            ]);
        });

        $pengajuan->load([
            'layanan',
            'user:id,name,email',
            'riwayatDisposisis.timKerja',
            'riwayatDisposisis.handledBy:id,name',
        ]);

        return response()->json([
            'message' => 'Permohonan berhasil didisposisikan.',
            'data' => $pengajuan,
        ]);
    }
}