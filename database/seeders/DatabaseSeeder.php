<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 * Orkestrasi urutan pemanggilan semua seeder.
 * Urutan penting karena ada foreign key dependencies.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // 1. Users dulu (tidak ada dependency)
            BarangSeeder::class,    // 2. Barang (dibutuhkan oleh Pembelian, Produksi, Penjualan)
            SupplierSeeder::class,  // 3. Supplier (dibutuhkan oleh Pembelian)
            // PembelianSeeder::class, // 4. Pembelian (butuh Supplier & Barang)
            // ProduksiSeeder::class,  // 5. Produksi (butuh Barang bahan mentah & produk jadi)
            // PenjualanSeeder::class, // 6. Penjualan (butuh Barang produk jadi)
        ]);
    }
}
