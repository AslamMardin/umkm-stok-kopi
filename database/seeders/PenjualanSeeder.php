<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Database\Seeder;

/**
 * PenjualanSeeder
 * Data dummy transaksi penjualan produk jadi.
 */
class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $arabikaBubuk    = Barang::where('name', 'Kopi Arabika Bubuk 250gr')->first();
        $robustaBubuk    = Barang::where('name', 'Kopi Robusta Bubuk 250gr')->first();
        $kopiSusuSachet  = Barang::where('name', 'Kopi Susu Sachet 20gr')->first();
        $cappucinoSachet = Barang::where('name', 'Cappuccino Sachet 20gr')->first();
        $siropVanilla    = Barang::where('name', 'Sirop Kopi Vanilla 250ml')->first();

        $data = [
            // ── Penjualan lama ─────────────────────────────
            [
                'barang_id'    => $arabikaBubuk->id,
                'tanggal'      => now()->subDays(30)->toDateString(),
                'qty'          => 20,
                'harga_satuan' => 55000,
                'pembeli'      => 'Toko Ritel Berkah',
                'keterangan'   => 'Pesanan rutin bulanan',
            ],
            [
                'barang_id'    => $robustaBubuk->id,
                'tanggal'      => now()->subDays(28)->toDateString(),
                'qty'          => 15,
                'harga_satuan' => 45000,
                'pembeli'      => 'Warung Kopi Pak Budi',
                'keterangan'   => null,
            ],
            [
                'barang_id'    => $kopiSusuSachet->id,
                'tanggal'      => now()->subDays(20)->toDateString(),
                'qty'          => 50,
                'harga_satuan' => 4000,
                'pembeli'      => 'Minimarket Sinar',
                'keterangan'   => 'Titip jual di minimarket',
            ],
            // ── Penjualan minggu ini ───────────────────────
            [
                'barang_id'    => $arabikaBubuk->id,
                'tanggal'      => now()->subDays(7)->toDateString(),
                'qty'          => 30,
                'harga_satuan' => 55000,
                'pembeli'      => 'Café Kopi Nusantara',
                'keterangan'   => 'Order batch café',
            ],
            [
                'barang_id'    => $cappucinoSachet->id,
                'tanggal'      => now()->subDays(5)->toDateString(),
                'qty'          => 40,
                'harga_satuan' => 4500,
                'pembeli'      => null,
                'keterangan'   => 'Penjualan langsung',
            ],
            [
                'barang_id'    => $siropVanilla->id,
                'tanggal'      => now()->subDays(3)->toDateString(),
                'qty'          => 10,
                'harga_satuan' => 35000,
                'pembeli'      => 'Kedai Kopi Oma',
                'keterangan'   => null,
            ],
            [
                'barang_id'    => $robustaBubuk->id,
                'tanggal'      => now()->subDays(1)->toDateString(),
                'qty'          => 20,
                'harga_satuan' => 46000,
                'pembeli'      => 'Toko Ritel Berkah',
                'keterangan'   => 'Reorder',
            ],
        ];

        foreach ($data as $item) {
            $barang = Barang::find($item['barang_id']);

            if ($barang && $barang->stock >= $item['qty']) {
                Penjualan::create($item);
                $barang->decrement('stock', $item['qty']);
            } else {
                $this->command->warn("⚠️  Stok {$barang?->name} tidak cukup untuk penjualan, dilewati.");
            }
        }

        $this->command->info('✅ PenjualanSeeder: ' . count($data) . ' transaksi penjualan berhasil dibuat.');
    }
}
