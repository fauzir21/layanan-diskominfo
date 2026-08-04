<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    /**
     * List publik — bisa difilter kategori & dicari.
     */
    public function index(Request $request)
    {
        $query = Layanan::query();

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
            ->with(['persyaratans' => fn ($q) => $q->orderBy('urutan')])
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
            'persyaratans' => ['array'],
            'persyaratans.*' => ['string', 'max:255'],
        ]);

        $layanan = Layanan::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']).'-'.Str::random(5),
            'deskripsi' => $validated['deskripsi'],
            'kategori' => $validated['kategori'],
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
            'persyaratans' => ['array'],
            'persyaratans.*' => ['string', 'max:255'],
        ]);

        $layanan->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'kategori' => $validated['kategori'],
        ]);

        $this->syncPersyaratans($layanan, $validated['persyaratans'] ?? []);

        return response()->json([
            'message' => 'Layanan berhasil diperbarui.',
            'data' => $layanan->load('persyaratans'),
        ]);
    }

    /**
     * Hapus layanan (admin only).
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return response()->json([
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }

    /**
     * Ganti seluruh daftar persyaratan punya sebuah layanan
     * (hapus yang lama, bikin ulang sesuai urutan yang dikirim).
     */
    private function syncPersyaratans(Layanan $layanan, array $persyaratans): void
    {
        $layanan->persyaratans()->delete();

        foreach ($persyaratans as $index => $nama) {
            $layanan->persyaratans()->create([
                'nama_syarat' => $nama,
                'urutan' => $index,
            ]);
        }
    }
}