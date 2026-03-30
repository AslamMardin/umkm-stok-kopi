<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 * Membuat akun admin/owner untuk login awal.
 * Tidak ada registrasi publik — semua user dibuat dari sini.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Administrator',
                'email'             => 'admin@kopi.com',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Pemilik Toko',
                'email'             => 'owner@kopi.com',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('✅ UserSeeder: 2 akun berhasil dibuat.');
        $this->command->line('   📧 admin@kopi.com  | 🔑 password123');
        $this->command->line('   📧 owner@kopi.com  | 🔑 password123');
    }
}
