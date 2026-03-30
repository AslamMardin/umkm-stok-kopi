<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Produksi;
use Illuminate\Database\Seeder;

/**
 * ProduksiSeeder
 * Data dummy batch produksi.
 * Karena stok sudah di-set di BarangSeeder, seeder ini hanya
 * mencatat histori produksi dan melakukan penyesuaian stok
 * secara manual (decrement bahan, increment produk jadi).
 */
class ProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $arabika  = Barang::where('name', 'Biji Kopi Arabika')->first();
        $robusta  = Barang::where('name', 'Biji Kopi Robusta')->first();
        $gula     = Barang::where('name', 'Gula Pasir')->first();
        $susu     = Barang::where('name', 'Susu Full Cream')->first();
        $coklat   = Barang::where('name', 'Coklat Bubuk')->first();
        $kemPouch = Barang::where('name', 'Kemasan Pouch 250gr')->first();
        $kemBotol = Barang::where('name', 'Kemasan Botol 250ml')->first();

        $arabikaBubuk    = Barang::where('name', 'Kopi Arabika Bubuk 250gr')->first();
        $robustaBubuk    = Barang::where('name', 'Kopi Robusta Bubuk 250gr')->first();
        $siropVanilla    = Barang::where('name', 'Sirop Kopi Vanilla 250ml')->first();
        $kopiSusuSachet  = Barang::where('name', 'Kopi Susu Sachet 20gr')->first();
        $cappucinoSachet = Barang::where('name', 'Cappuccino Sachet 20gr')->first();

        /**
         * Format: [bahan_mentah, produk_jadi, qty_bahan, qty_produk, tanggal, keterangan]
         * Setiap record akan:
         *   1. Membuat record Produksi
         *   2. Decrement stok bahan mentah
         *   3. Increment stok produk jadi
         */
        $batches = [
            // ── Batch produksi 40 hari lalu ────────────────
            [
                'bahan'     => $arabika,
                'produk'    => $arabikaBubuk,
                'qty_bahan' => 50,    // 50 kg arabika
                'qty_produk'=> 180,   // menghasilkan 180 pouch @250gr
                'tanggal'   => now()->subDays(40)->toDateString(),
                'keterangan'=> 'Batch produksi arabika pertama',
            ],
            [
                'bahan'     => $robusta,
                'produk'    => $robustaBubuk,
                'qty_bahan' => 40,
                'qty_produk'=> 145,
                'tanggal'   => now()->subDays(38)->toDateString(),
                'keterangan'=> 'Batch produksi robusta',
            ],
            // ── Batch produksi bulan ini ────────────────────
            [
                'bahan'     => $arabika,
                'produk'    => $arabikaBubuk,
                'qty_bahan' => 30,
                'qty_produk'=> 108,
                'tanggal'   => now()->subDays(12)->toDateString(),
                'keterangan'=> 'Batch arabika batch-2',
            ],
            [
                'bahan'     => $gula,
                'produk'    => $kopiSusuSachet,
                'qty_bahan' => 20,
                'qty_produk'=> 250,
                'tanggal'   => now()->subDays(10)->toDateString(),
                'keterangan'=> 'Produksi kopi susu sachet',
            ],
            [
                'bahan'     => $coklat,
                'produk'    => $cappucinoSachet,
                'qty_bahan' => 10,
                'qty_produk'=> 120,
                'tanggal'   => now()->subDays(7)->toDateString(),
                'keterangan'=> 'Produksi cappuccino sachetan',
            ],
            [
                'bahan'     => $arabika,
                'produk'    => $siropVanilla,
                'qty_bahan' => 15,
                'qty_produk'=> 55,
                'tanggal'   => now()->subDays(4)->toDateString(),
                'keterangan'=> 'Produksi sirop kopi vanilla',
            ],
        ];

        foreach ($batches as $batch) {
            // Validasi stok cukup sebelum decrement
            if ($batch['bahan']->stock >= $batch['qty_bahan']) {
                Produksi::create([
                    'barang_bahan_mentah_id' => $batch['bahan']->id,
                    'barang_produk_jadi_id'  => $batch['produk']->id,
                    'tanggal'                => $batch['tanggal'],
                    'qty_bahan_mentah'       => $batch['qty_bahan'],
                    'qty_produk_jadi'        => $batch['qty_produk'],
                    'keterangan'             => $batch['keterangan'],
                ]);

                // Adjust stok
                $batch['bahan']->decrement('stock',  $batch['qty_bahan']);
                $batch['produk']->increment('stock', $batch['qty_produk']);
            } else {
                $this->command->warn("⚠️  Stok {$batch['bahan']->name} tidak cukup, batch dilewati.");
            }
        }

        $this->command->info('✅ ProduksiSeeder: ' . count($batches) . ' batch produksi berhasil dibuat.');
    }
}
