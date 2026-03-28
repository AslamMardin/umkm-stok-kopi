<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin UMKM Kopi',
            'email'    => 'admin@kopi.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name'     => 'Staf Gudang',
            'email'    => 'gudang@kopi.com',
            'password' => Hash::make('password123'),
        ]);
    }
}