<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Layanan;
use App\Models\Pengajuan;
use App\Models\RiwayatDisposisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengajuanController extends Controller
{
    /**
     * List pengajuan milik user yang lagi login (buat halaman "Permohonan Saya").
     */
    public function index(Request $request)
    {
        $pengajuans = Pengajuan::where('user_id', $request->user()->id)
            ->with('layanan:id,nama')
            ->latest('tanggal_pengajuan')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nomor_tiket' => $p->nomor_tiket,
                'layanan' => $p->layanan?->nama,
                'status' => $p->status,
                'tanggal_pengajuan' => $p->tanggal_pengajuan,
            ]);

        return response()->json(['data' => $pengajuans]);
    }

    /**
     * Detail 1 pengajuan + riwayat statusnya.
     * Yang boleh liat: pemilik pengajuan, atau staff (admin/helpdesk/pegawai).
     */
    public function show(Request $request, $id)
    {
        $pengajuan = Pengajuan::with(['layanan:id,nama', 'riwayatDisposisis'])->findOrFail($id);

        $user = $request->user();
        $isOwner = $pengajuan->user_id === $user->id;
        $isStaff = in_array($user->role, ['admin', 'helpdesk', 'pegawai']);

        if (! $isOwner && ! $isStaff) {
            return response()->json([
                'message' => 'Anda tidak punya akses ke pengajuan ini.',
            ], 403);
        }

        return response()->json([
            'data' => [
                'id' => $pengajuan->id,
                'nomor_tiket' => $pengajuan->nomor_tiket,
                'layanan' => $pengajuan->layanan?->nama,
                'status' => $pengajuan->status,
                'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
                'tanggal_selesai' => $pengajuan->tanggal_selesai,
                'riwayat' => $pengajuan->riwayatDisposisis->map(fn ($r) => [
                    'status' => $r->status,
                    'keterangan' => $r->keterangan,
                    'tanggal_disposisi' => $r->tanggal_disposisi,
                ]),
            ],
        ]);
    }

    /**
     * List pengajuan yang masih "menunggu_diproses" — buat helpdesk verifikasi/teruskan.
     */
    public function helpdeskIndex()
    {
        $pengajuans = Pengajuan::where('status', 'menunggu_diproses')
            ->with(['layanan:id,nama,tim_kerja_id', 'user:id,name'])
            ->latest('tanggal_pengajuan')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nomor_tiket' => $p->nomor_tiket,
                'layanan' => $p->layanan?->nama,
                'nama_pemohon' => $p->user?->name,
                'tanggal_pengajuan' => $p->tanggal_pengajuan,
            ]);

        return response()->json(['data' => $pengajuans]);
    }

    /**
     * Helpdesk teruskan (→ diproses, otomatis "kekirim" ke tim kerja layanan-nya)
     * atau tolak (→ ditolak) sebuah pengajuan.
     */
    public function helpdeskUpdateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diproses,ditolak'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $pengajuan = Pengajuan::with('layanan')->findOrFail($id);

        if ($pengajuan->status !== 'menunggu_diproses') {
            return response()->json([
                'message' => 'Pengajuan ini sudah diproses sebelumnya.',
            ], 422);
        }

        $pengajuan->update(['status' => $validated['status']]);

        RiwayatDisposisi::create([
            'pengajuan_id' => $pengajuan->id,
            'tim_kerja_id' => $validated['status'] === 'diproses' ? $pengajuan->layanan->tim_kerja_id : null,
            'handled_by' => $request->user()->id,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan']
                ?? ($validated['status'] === 'diproses'
                    ? 'Pengajuan diteruskan ke tim kerja terkait.'
                    : 'Pengajuan ditolak oleh helpdesk.'),
            'tanggal_disposisi' => now(),
        ]);

        return response()->json([
            'message' => 'Status pengajuan berhasil diperbarui.',
        ]);
    }

    /**
     * List pengajuan status "diproses" yang levansi ke tim kerja pegawai yang login.
     */
    public function pegawaiIndex(Request $request)
    {
        $timIds = $request->user()->timKerjas()->pluck('tim_kerjas.id');

        $pengajuans = Pengajuan::where('status', 'diproses')
            ->whereHas('layanan', fn ($q) => $q->whereIn('tim_kerja_id', $timIds))
            ->with(['layanan:id,nama', 'user:id,name'])
            ->latest('tanggal_pengajuan')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nomor_tiket' => $p->nomor_tiket,
                'layanan' => $p->layanan?->nama,
                'nama_pemohon' => $p->user?->name,
                'tanggal_pengajuan' => $p->tanggal_pengajuan,
            ]);

        return response()->json(['data' => $pengajuans]);
    }

    /**
     * Pegawai selesaikan (→ selesai) atau minta perbaikan (→ perbaikan) sebuah pengajuan
     * yang levansi ke tim kerja dia.
     */
    public function pegawaiUpdateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:selesai,perbaikan'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $pengajuan = Pengajuan::with('layanan')->findOrFail($id);

        $timIds = $request->user()->timKerjas()->pluck('tim_kerjas.id');

        if (! $timIds->contains($pengajuan->layanan->tim_kerja_id)) {
            return response()->json([
                'message' => 'Anda tidak punya akses ke pengajuan ini.',
            ], 403);
        }

        if ($pengajuan->status !== 'diproses') {
            return response()->json([
                'message' => 'Pengajuan ini bukan dalam status diproses.',
            ], 422);
        }

        $pengajuan->update([
            'status' => $validated['status'],
            'tanggal_selesai' => $validated['status'] === 'selesai' ? now()->toDateString() : $pengajuan->tanggal_selesai,
        ]);

        RiwayatDisposisi::create([
            'pengajuan_id' => $pengajuan->id,
            'tim_kerja_id' => $pengajuan->layanan->tim_kerja_id,
            'handled_by' => $request->user()->id,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan']
                ?? ($validated['status'] === 'selesai'
                    ? 'Permohonan telah selesai diproses.'
                    : 'Permohonan memerlukan perbaikan/kelengkapan tambahan.'),
            'tanggal_disposisi' => now(),
        ]);

        return response()->json([
            'message' => 'Status pengajuan berhasil diperbarui.',
        ]);
    }

    /**
     * User ngajuin permohonan baru buat suatu layanan.
     * Wajib login (auth:sanctum), email juga harus udah verified.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
        ]);

        $layanan = Layanan::where('id', $validated['layanan_id'])
            ->where('status', 'aktif')
            ->with('persyaratans')
            ->firstOrFail();

        // Validasi dinamis: tiap persyaratan yang wajib, harus ada jawabannya
        $errors = [];
        $jawabanPerPersyaratan = [];

        foreach ($layanan->persyaratans as $persyaratan) {
            $key = "jawaban.{$persyaratan->id}";

            if ($persyaratan->tipe === 'file') {
                $file = $request->file('jawaban')[$persyaratan->id] ?? null;

                if (! $file && $persyaratan->wajib) {
                    $errors[$key] = ["{$persyaratan->nama_syarat} wajib diunggah."];
                }

                $jawabanPerPersyaratan[$persyaratan->id] = ['file' => $file, 'text' => null];
            } else {
                $text = $request->input('jawaban')[$persyaratan->id] ?? null;

                if (! $text && $persyaratan->wajib) {
                    $errors[$key] = ["{$persyaratan->nama_syarat} wajib diisi."];
                }

                $jawabanPerPersyaratan[$persyaratan->id] = ['file' => null, 'text' => $text];
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        $pengajuan = DB::transaction(function () use ($request, $layanan, $jawabanPerPersyaratan) {
            $pengajuan = Pengajuan::create([
                'layanan_id' => $layanan->id,
                'user_id' => $request->user()->id,
                'nomor_tiket' => $this->generateNomorTiket(),
                'status' => 'menunggu_diproses',
                'tanggal_pengajuan' => now()->toDateString(),
            ]);

            foreach ($jawabanPerPersyaratan as $persyaratanId => $jawaban) {
                if (! $jawaban['file'] && ! $jawaban['text']) {
                    continue; // persyaratan opsional yang nggak diisi, skip
                }

                $filePath = $jawaban['file']
                    ? $jawaban['file']->store('dokumen-pengajuan', 'public')
                    : null;

                Dokumen::create([
                    'pengajuan_id' => $pengajuan->id,
                    'persyaratan_id' => $persyaratanId,
                    'file' => $filePath,
                    'text' => $jawaban['text'],
                ]);
            }

            RiwayatDisposisi::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => 'menunggu_diproses',
                'keterangan' => 'Pengajuan diterima, menunggu diproses oleh petugas.',
                'tanggal_disposisi' => now(),
            ]);

            return $pengajuan;
        });

        return response()->json([
            'message' => 'Pengajuan berhasil dikirim.',
            'data' => [
                'nomor_tiket' => $pengajuan->nomor_tiket,
            ],
        ], 201);
    }

    /**
     * Lacak status pengajuan pakai nomor tiket. Endpoint publik (nggak perlu login).
     */
    public function lacak($nomorTiket)
    {
        $pengajuan = Pengajuan::where('nomor_tiket', $nomorTiket)
            ->with(['layanan:id,nama', 'riwayatDisposisis'])
            ->first();

        if (! $pengajuan) {
            return response()->json([
                'message' => 'Nomor tiket tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'nomor_tiket' => $pengajuan->nomor_tiket,
                'layanan' => $pengajuan->layanan->nama,
                'status' => $pengajuan->status,
                'tanggal_pengajuan' => $pengajuan->tanggal_pengajuan,
                'tanggal_selesai' => $pengajuan->tanggal_selesai,
                'riwayat' => $pengajuan->riwayatDisposisis->map(fn ($r) => [
                    'status' => $r->status,
                    'keterangan' => $r->keterangan,
                    'tanggal_disposisi' => $r->tanggal_disposisi,
                ]),
            ],
        ]);
    }

    private function generateNomorTiket(): string
    {
        do {
            $nomor = 'TK-'.now()->format('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));
        } while (Pengajuan::where('nomor_tiket', $nomor)->exists());

        return $nomor;
    }
}