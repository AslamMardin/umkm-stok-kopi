<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

/**
 * PembelianSeeder
 * Data dummy transaksi pembelian bahan mentah.
 * Stok barang sudah di-seed langsung di BarangSeeder,
 * sehingga seeder ini hanya mencatat histori transaksinya
 * tanpa double-increment stok.
 */
class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID supplier dan barang yang sudah di-seed
        $supplierNusantara = Supplier::first(); // PT. Kopi Polewali
        $supplierAgro      = Supplier::skip(1)->first() ?? $supplierNusantara; // PT. Kopi Mandar

        $arabika   = Barang::where('name', 'Biji Kopi Arabika')->first();
        $robusta   = Barang::where('name', 'Biji Kopi Robusta')->first();

        $data = [
            [
                'supplier_id'  => $supplierNusantara->id,
                'barang_id'    => $arabika->id,
                'tanggal'      => now()->subDays(30)->toDateString(),
                'qty'          => 100,
                'harga_satuan' => 95000,
                'keterangan'   => 'Pembelian biji kopi arabika grade A',
            ],
            [
                'supplier_id'  => $supplierAgro->id,
                'barang_id'    => $robusta->id,
                'tanggal'      => now()->subDays(25)->toDateString(),
                'qty'          => 80,
                'harga_satuan' => 65000,
                'keterangan'   => 'Pembelian biji kopi robusta Mandar',
            ],
            [
                'supplier_id'  => $supplierNusantara->id,
                'barang_id'    => $arabika->id,
                'tanggal'      => now()->subDays(10)->toDateString(),
                'qty'          => 50,
                'harga_satuan' => 97000,
                'keterangan'   => 'Restok arabika',
            ],
            [
                'supplier_id'  => $supplierAgro->id,
                'barang_id'    => $robusta->id,
                'tanggal'      => now()->subDays(5)->toDateString(),
                'qty'          => 60,
                'harga_satuan' => 67000,
                'keterangan'   => 'Restok robusta',
            ],
        ];

        foreach ($data as $item) {
            Pembelian::create($item);
        }

        $this->command->info('✅ PembelianSeeder: ' . count($data) . ' transaksi pembelian berhasil dibuat.');
    }
}
