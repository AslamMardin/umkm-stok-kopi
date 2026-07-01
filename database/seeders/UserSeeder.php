<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
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
        // Ambil supplier pertama untuk dihubungkan ke user supplier
        $supplier = Supplier::first();
        $supplierId = $supplier ? $supplier->id : null;

        $users = [
            [
                'name'              => 'Administrator',
                'email'             => 'admin@kopi.com',
                'password'          => Hash::make('password123'),
                'role'              => 'admin_gudang',
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Pemilik Toko',
                'email'             => 'owner@kopi.com',
                'password'          => Hash::make('password123'),
                'role'              => 'admin_gudang',
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Petani Kopi Polewali',
                'email'             => 'supplier@kopi.com',
                'password'          => Hash::make('password123'),
                'role'              => 'supplier',
                'supplier_id'       => $supplierId,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Toko Ritel Berkah',
                'email'             => 'umkm@kopi.com',
                'password'          => Hash::make('password123'),
                'role'              => 'umkm',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('✅ UserSeeder: Akun multi-role berhasil dibuat.');
        $this->command->line('   📧 admin@kopi.com    (Admin Gudang)  | 🔑 password123');
        $this->command->line('   📧 owner@kopi.com    (Admin Gudang)  | 🔑 password123');
        $this->command->line('   📧 supplier@kopi.com (Supplier)      | 🔑 password123');
        $this->command->line('   📧 umkm@kopi.com     (UMKM)          | 🔑 password123');
    }
}
