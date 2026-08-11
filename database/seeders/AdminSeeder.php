<?php

namespace Database\Seeders;

use App\Models\TimKerja;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tim kerja contoh
        $timKerja = TimKerja::firstOrCreate(['nama_tim' => 'Tim Layanan Kependudukan']);

        // Admin — kelola master data
        User::updateOrCreate(
            ['email' => 'admin@diskominfo.bogorkota.go.id'],
            [
                'name' => 'Admin Diskominfo',
                'password' => 'password',
                'role' => 'admin',
                'kategori_pengguna' => 'Instansi',
                'email_verified_at' => now(),
            ]
        );

        // Helpdesk — verifikasi awal & disposisi pengajuan
        User::updateOrCreate(
            ['email' => 'helpdesk@diskominfo.bogorkota.go.id'],
            [
                'name' => 'Petugas Helpdesk',
                'password' => 'password',
                'role' => 'helpdesk',
                'kategori_pengguna' => 'Instansi',
                'email_verified_at' => now(),
            ]
        );

        // Pegawai — proses pengajuan sesuai tim kerja
        $pegawai = User::updateOrCreate(
            ['email' => 'pegawai@diskominfo.bogorkota.go.id'],
            [
                'name' => 'Pegawai Tim Kependudukan',
                'password' => 'password',
                'role' => 'pegawai',
                'kategori_pengguna' => 'Instansi',
                'email_verified_at' => now(),
            ]
        );

        $pegawai->timKerjas()->syncWithoutDetaching([$timKerja->id]);
    }
}