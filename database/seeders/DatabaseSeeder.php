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
            SupplierSeeder::class,  // 1. Supplier dulu (diperlukan oleh UserSeeder)
            UserSeeder::class,      // 2. Users
            BarangSeeder::class,    // 3. Barang (dibutuhkan oleh Pembelian, Produksi, Penjualan)
        ]);

        // Buat dummy Pembelian untuk Supplier (Petani)
        $supplier = \App\Models\Supplier::first();
        $arabika = \App\Models\Barang::where('name', 'Biji Kopi Arabika')->first();
        $robusta = \App\Models\Barang::where('name', 'Biji Kopi Robusta')->first();

        if ($supplier && $arabika && $robusta) {
            \App\Models\Pembelian::create([
                'supplier_id' => $supplier->id,
                'barang_id' => $arabika->id,
                'qty' => 100,
                'harga_satuan' => 95000,
                'tanggal' => now()->subDays(10)->toDateString(),
                'keterangan' => 'Pengiriman Biji Kopi Arabika Mentah',
            ]);

            \App\Models\Pembelian::create([
                'supplier_id' => $supplier->id,
                'barang_id' => $robusta->id,
                'qty' => 150,
                'harga_satuan' => 65000,
                'tanggal' => now()->subDays(5)->toDateString(),
                'keterangan' => 'Pengiriman Biji Kopi Robusta Mentah',
            ]);
        }

        // Buat dummy Penjualan untuk UMKM (Toko Ritel Berkah)
        $produkJadi = \App\Models\Barang::where('name', 'Kopi Bubuk')->first();
        if ($produkJadi) {
            // Set stok produk jadi awal agar bisa dibeli
            $produkJadi->update(['stock' => 50]);

            \App\Models\Penjualan::create([
                'barang_id' => $produkJadi->id,
                'qty' => 10,
                'harga_satuan' => 45000,
                'tanggal' => now()->subDays(3)->toDateString(),
                'pembeli' => 'Toko Ritel Berkah',
                'keterangan' => 'Pembelian Kopi Bubuk untuk Restok Toko',
            ]);
        }
    }
}
