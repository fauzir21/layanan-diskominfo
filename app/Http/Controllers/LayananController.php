<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Persyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    /**
     * List publik.
     */
    public function index(Request $request)
    {
        $query = Layanan::query()
            ->where('status', 'aktif')
            ->with('persyaratans');

        if (
            $request->filled('kategori') &&
            $request->kategori !== 'semua'
        ) {
            $query->where(
                'kategori',
                $request->kategori
            );
        }

        if ($request->filled('search')) {
            $query->where(
                'nama',
                'like',
                '%' . $request->search . '%'
            );
        }

        return response()->json([
            'data' => $query->latest()->get(),
        ]);
    }

    /**
     * Detail publik.
     */
    public function show($slug)
    {
        $layanan = Layanan::where('slug', $slug)
            ->where('status', 'aktif')
            ->with('persyaratans')
            ->firstOrFail();

        return response()->json([
            'data' => $layanan
        ]);
    }

    /**
     * Tambah layanan baru.
     * Admin only.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:255'
            ],

            'deskripsi' => [
                'required',
                'string'
            ],

            'kategori' => [
                'required',
                'in:eksternal,internal'
            ],

            'status' => [
                'nullable',
                'in:aktif,nonaktif'
            ],

            'persyaratans' => [
                'nullable',
                'array'
            ],

            'persyaratans.*.nama_syarat' => [
                'required',
                'string',
                'max:255'
            ],

            'persyaratans.*.tipe' => [
                'required',
                'in:file,text'
            ],

            'persyaratans.*.wajib' => [
                'required',
                'boolean'
            ],

        ]);

        $layanan = Layanan::create([

            'nama' => $validated['nama'],

            'slug' =>
                Str::slug($validated['nama'])
                . '-'
                . Str::random(5),

            'deskripsi' =>
                $validated['deskripsi'],

            'kategori' =>
                $validated['kategori'],

            'status' =>
                $validated['status'] ?? 'aktif',

        ]);

        $this->syncPersyaratans(
            $layanan,
            $validated['persyaratans'] ?? []
        );

        return response()->json([

            'message' =>
                'Layanan berhasil ditambahkan.',

            'data' =>
                $layanan->load('persyaratans'),

        ], 201);
    }

    /**
     * Update layanan.
     * Admin only.
     */
    public function update(
        Request $request,
        Layanan $layanan
    ) {
        $validated = $request->validate([

            'nama' => [
                'required',
                'string',
                'max:255'
            ],

            'deskripsi' => [
                'required',
                'string'
            ],

            'kategori' => [
                'required',
                'in:eksternal,internal'
            ],

            'status' => [
                'nullable',
                'in:aktif,nonaktif'
            ],

            'persyaratans' => [
                'nullable',
                'array'
            ],

            'persyaratans.*.nama_syarat' => [
                'required',
                'string',
                'max:255'
            ],

            'persyaratans.*.tipe' => [
                'required',
                'in:file,text'
            ],

            'persyaratans.*.wajib' => [
                'required',
                'boolean'
            ],

        ]);

        $layanan->update([

            'nama' =>
                $validated['nama'],

            'deskripsi' =>
                $validated['deskripsi'],

            'kategori' =>
                $validated['kategori'],

            'status' =>
                $validated['status']
                ?? $layanan->status,

        ]);

        $this->syncPersyaratans(
            $layanan,
            $validated['persyaratans'] ?? []
        );

        return response()->json([

            'message' =>
                'Layanan berhasil diperbarui.',

            'data' =>
                $layanan->load('persyaratans'),

        ]);
    }

    /**
     * Hapus layanan.
     */
    public function destroy(
        Layanan $layanan
    ) {
        $layanan->delete();

        return response()->json([

            'message' =>
                'Layanan berhasil dihapus.',

        ]);
    }

    /**
     * Sinkronisasi persyaratan.
     */
    private function syncPersyaratans(
        Layanan $layanan,
        array $persyaratans
    ): void {

        $syncData = [];

        foreach (
            $persyaratans as $index => $data
        ) {

            $nama = trim(
                $data['nama_syarat']
            );

            $tipe =
                $data['tipe'] ?? 'file';

            $wajib =
                (bool) (
                    $data['wajib'] ?? true
                );

            /*
             * Kalau persyaratan dengan nama yang sama
             * sudah ada, gunakan yang lama.
             */
            $persyaratan =
                Persyaratan::firstOrCreate(
                    [
                        'nama_syarat' => $nama
                    ],
                    [
                        'tipe' => $tipe,
                        'wajib' => $wajib
                    ]
                );

            /*
             * Pastikan tipe dan status wajib
             * mengikuti pengaturan admin.
             */
            $persyaratan->update([

                'tipe' => $tipe,

                'wajib' => $wajib,

            ]);

            $syncData[
                $persyaratan->id
            ] = [

                'urutan' => $index

            ];
        }

        /*
         * Sync pivot layanan_persyaratan.
         */
        $layanan
            ->persyaratans()
            ->sync($syncData);
    }
}