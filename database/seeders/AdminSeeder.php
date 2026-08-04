<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@diskominfo.bogorkota.go.id'],
            [
                'name' => 'Admin Diskominfo',
                'password' => 'password', // otomatis di-hash sama cast di model
                'role' => 'admin',
                'kategori_pengguna' => 'Instansi',
                'email_verified_at' => now(), // admin nggak perlu verifikasi email lagi
            ]
        );
    }
}