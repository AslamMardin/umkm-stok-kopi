<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * SupplierSeeder
 * Data dummy pemasok bahan baku kopi UMKM.
 */
class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name'    => 'PT. Kopi Polewali',
                'phone'   => '08123456789',
                'address' => 'Jl. Raya',
                'email'   => 'kopi.nusantara@email.com',
            ],
            [
                'name'    => 'PT. Kopi Mandar',
                'phone'   => '08234567890',
                'address' => 'Jl. Perkebunan',
                'email'   => 'agro.kopi@email.com',
            ],
            [
                'name'    => 'PT. Kopi Campalagian',
                'phone'   => '08345678901',
                'address' => 'Jl. merpati',
                'email'   => 'sejahtera.gula@email.com',
            ],
           
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }

        $this->command->info('✅ SupplierSeeder: ' . count($suppliers) . ' supplier berhasil dibuat.');
    }
}
