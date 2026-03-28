<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'nama_supplier' => 'Petani Kopi Pak Ahmad',
                'alamat'        => 'Desa Campalagian, Polewali Mandar',
                'no_telepon'    => '081234567890',
                'email'         => 'pak.ahmad@gmail.com',
                'keterangan'    => 'Supplier tetap Green Bean Arabika Toraja',
            ],
            [
                'nama_supplier' => 'Koperasi Tani Maju',
                'alamat'        => 'Desa Balanipa, Polewali Mandar',
                'no_telepon'    => '082345678901',
                'email'         => 'koptani.maju@gmail.com',
                'keterangan'    => 'Supplier Green Bean Robusta',
            ],
            [
                'nama_supplier' => 'Petani Kopi Bu Sari',
                'alamat'        => 'Desa Tinambung, Polewali Mandar',
                'no_telepon'    => '083456789012',
                'email'         => null,
                'keterangan'    => 'Supplier musiman Green Bean campuran',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
