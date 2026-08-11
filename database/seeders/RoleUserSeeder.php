<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@testing.local',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Helpdesk',
                'email' => 'helpdesk@testing.local',
                'password' => Hash::make('password123'),
                'role' => 'helpdesk',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pegawai',
                'email' => 'pegawai@testing.local',
                'password' => Hash::make('password123'),
                'role' => 'pegawai',
                'kategori_pengguna' => 'internal',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pemohon',
                'email' => 'user@testing.local',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'kategori_pengguna' => 'eksternal',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('Akun testing berhasil dibuat.');
    }
}