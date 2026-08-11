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