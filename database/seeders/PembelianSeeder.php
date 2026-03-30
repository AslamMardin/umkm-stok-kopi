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
        $supplierNusantara = Supplier::where('name', 'UD. Kopi Nusantara')->first();
        $supplierAgro      = Supplier::where('name', 'PT. Agro Kopi Mandiri')->first();
        $supplierGula      = Supplier::where('name', 'CV. Sejahtera Gula')->first();
        $supplierBahan     = Supplier::where('name', 'Toko Bahan Kue Berkah')->first();
        $supplierSusu      = Supplier::where('name', 'Distributor Susu Segar Jaya')->first();

        $arabika   = Barang::where('name', 'Biji Kopi Arabika')->first();
        $robusta   = Barang::where('name', 'Biji Kopi Robusta')->first();
        $gula      = Barang::where('name', 'Gula Pasir')->first();
        $susu      = Barang::where('name', 'Susu Full Cream')->first();
        $coklat    = Barang::where('name', 'Coklat Bubuk')->first();
        $kemPouch  = Barang::where('name', 'Kemasan Pouch 250gr')->first();
        $kemBotol  = Barang::where('name', 'Kemasan Botol 250ml')->first();

        $data = [
            // ── Bulan lalu ────────────────────────────────────
            [
                'supplier_id'  => $supplierNusantara->id,
                'barang_id'    => $arabika->id,
                'tanggal'      => now()->subDays(60)->toDateString(),
                'qty'          => 100,
                'harga_satuan' => 95000,
                'keterangan'   => 'Pembelian biji kopi arabika grade A',
            ],
            [
                'supplier_id'  => $supplierAgro->id,
                'barang_id'    => $robusta->id,
                'tanggal'      => now()->subDays(55)->toDateString(),
                'qty'          => 80,
                'harga_satuan' => 65000,
                'keterangan'   => 'Pembelian biji kopi robusta Aceh',
            ],
            [
                'supplier_id'  => $supplierGula->id,
                'barang_id'    => $gula->id,
                'tanggal'      => now()->subDays(50)->toDateString(),
                'qty'          => 50,
                'harga_satuan' => 14000,
                'keterangan'   => null,
            ],
            [
                'supplier_id'  => $supplierSusu->id,
                'barang_id'    => $susu->id,
                'tanggal'      => now()->subDays(45)->toDateString(),
                'qty'          => 40,
                'harga_satuan' => 22000,
                'keterangan'   => 'Susu full cream untuk produk kopi susu',
            ],
            // ── Bulan ini ─────────────────────────────────────
            [
                'supplier_id'  => $supplierNusantara->id,
                'barang_id'    => $arabika->id,
                'tanggal'      => now()->subDays(20)->toDateString(),
                'qty'          => 100,
                'harga_satuan' => 97000,
                'keterangan'   => 'Restok arabika bulan ini',
            ],
            [
                'supplier_id'  => $supplierAgro->id,
                'barang_id'    => $robusta->id,
                'tanggal'      => now()->subDays(15)->toDateString(),
                'qty'          => 70,
                'harga_satuan' => 67000,
                'keterangan'   => null,
            ],
            [
                'supplier_id'  => $supplierBahan->id,
                'barang_id'    => $coklat->id,
                'tanggal'      => now()->subDays(10)->toDateString(),
                'qty'          => 30,
                'harga_satuan' => 55000,
                'keterangan'   => 'Coklat bubuk untuk cappuccino',
            ],
            [
                'supplier_id'  => $supplierBahan->id,
                'barang_id'    => $kemPouch->id,
                'tanggal'      => now()->subDays(8)->toDateString(),
                'qty'          => 500,
                'harga_satuan' => 800,
                'keterangan'   => 'Kemasan pouch stand-up',
            ],
            [
                'supplier_id'  => $supplierBahan->id,
                'barang_id'    => $kemBotol->id,
                'tanggal'      => now()->subDays(5)->toDateString(),
                'qty'          => 300,
                'harga_satuan' => 1500,
                'keterangan'   => 'Botol kaca 250ml untuk sirop',
            ],
            [
                'supplier_id'  => $supplierGula->id,
                'barang_id'    => $gula->id,
                'tanggal'      => now()->subDays(3)->toDateString(),
                'qty'          => 50,
                'harga_satuan' => 14500,
                'keterangan'   => 'Restok gula',
            ],
        ];

        foreach ($data as $item) {
            Pembelian::create($item);
        }

        $this->command->info('✅ PembelianSeeder: ' . count($data) . ' transaksi pembelian berhasil dibuat.');
    }
}
