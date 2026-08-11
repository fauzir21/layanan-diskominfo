<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Persyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    /**
     * List publik — bisa difilter kategori & dicari.
     * Cuma nampilin yang statusnya aktif.
     */
    public function index(Request $request)
    {
        $query = Layanan::query()->where('status', 'aktif');

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }

        return response()->json([
            'data' => $query->latest()->get(),
        ]);
    }

    /**
     * Detail publik, termasuk daftar persyaratan.
     */
    public function show($slug)
    {
        $layanan = Layanan::where('slug', $slug)
            ->where('status', 'aktif')
            ->with('persyaratans')
            ->firstOrFail();

        return response()->json(['data' => $layanan]);
    }

    /**
     * Tambah layanan baru (admin only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'in:eksternal,internal'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
            'persyaratans' => ['array'],
            'persyaratans.*' => ['string', 'max:255'],
        ]);

        $layanan = Layanan::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']).'-'.Str::random(5),
            'deskripsi' => $validated['deskripsi'],
            'kategori' => $validated['kategori'],
            'status' => $validated['status'] ?? 'aktif',
        ]);

        $this->syncPersyaratans($layanan, $validated['persyaratans'] ?? []);

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan.',
            'data' => $layanan->load('persyaratans'),
        ], 201);
    }

    /**
     * Update layanan (admin only).
     */
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kategori' => ['required', 'in:eksternal,internal'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
            'persyaratans' => ['array'],
            'persyaratans.*' => ['string', 'max:255'],
        ]);

        $layanan->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'kategori' => $validated['kategori'],
            'status' => $validated['status'] ?? $layanan->status,
        ]);

        $this->syncPersyaratans($layanan, $validated['persyaratans'] ?? []);

        return response()->json([
            'message' => 'Layanan berhasil diperbarui.',
            'data' => $layanan->load('persyaratans'),
        ]);
    }

    /**
     * Hapus layanan (admin only).
     * Persyaratan-nya sendiri TIDAK ikut kehapus (masih bisa dipakai layanan lain),
     * cuma "kaitan"-nya (baris di tabel pivot) yang otomatis hilang.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return response()->json([
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }

    /**
     * Sinkronin daftar persyaratan (berupa nama) ke sebuah layanan.
     * Kalau nama persyaratan udah pernah ada (dipakai layanan lain), dipakai ulang.
     * Kalau belum ada, dibikin baru otomatis (default wajib=true, tipe=file).
     */
    private function syncPersyaratans(Layanan $layanan, array $namaPersyaratans): void
    {
        $syncData = [];

        foreach ($namaPersyaratans as $index => $nama) {
            $persyaratan = Persyaratan::firstOrCreate(
                ['nama_syarat' => $nama],
                ['tipe' => 'file', 'wajib' => true]
            );

            $syncData[$persyaratan->id] = ['urutan' => $index];
        }

        $layanan->persyaratans()->sync($syncData);
    }
}