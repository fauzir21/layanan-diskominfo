<?php

namespace Database\Seeders;

use App\Models\TimKerja;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@testing.local',
                'password' => 'password123',
                'role' => 'admin',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Helpdesk',
                'email' => 'helpdesk@testing.local',
                'password' => 'password123',
                'role' => 'helpdesk',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pegawai',
                'email' => 'pegawai@testing.local',
                'password' => 'password123',
                'role' => 'pegawai',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pemohon',
                'email' => 'user@testing.local',
                'password' => 'password123',
                'role' => 'user',
                'kategori_pengguna' => 'eksternal',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Pegawai testing dimasukin ke 1 tim kerja contoh, biar dashboard-nya
        // ada isinya pas dites (tanpa ini, pegawai nggak "kebagian" pengajuan apapun,
        // soalnya sistem nyaring pengajuan berdasarkan tim kerja dia)
        $timKerja = TimKerja::firstOrCreate(['nama_tim' => 'Tim Layanan Kependudukan']);

        $pegawai = User::where('email', 'pegawai@testing.local')->first();
        $pegawai->timKerjas()->syncWithoutDetaching([$timKerja->id]);

        $this->command->info('Akun testing berhasil dibuat.');
    }
}