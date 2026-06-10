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
        $arabikaBubuk    = Barang::where('name', 'Kopi Arabika Bubuk 250gr')->first() ?? Barang::create(['name' => 'Kopi Arabika Bubuk 250gr', 'type' => 'produk_jadi', 'stock' => 100, 'satuan' => 'pcs']);
        $robustaBubuk    = Barang::where('name', 'Kopi Robusta Bubuk 250gr')->first() ?? Barang::create(['name' => 'Kopi Robusta Bubuk 250gr', 'type' => 'produk_jadi', 'stock' => 100, 'satuan' => 'pcs']);
        $kopiSusuSachet  = Barang::where('name', 'Kopi Susu Sachet 20gr')->first() ?? Barang::create(['name' => 'Kopi Susu Sachet 20gr', 'type' => 'produk_jadi', 'stock' => 100, 'satuan' => 'pcs']);
        $cappucinoSachet = Barang::where('name', 'Cappuccino Sachet 20gr')->first() ?? Barang::create(['name' => 'Cappuccino Sachet 20gr', 'type' => 'produk_jadi', 'stock' => 100, 'satuan' => 'pcs']);
        $siropVanilla    = Barang::where('name', 'Sirop Kopi Vanilla 250ml')->first() ?? Barang::create(['name' => 'Sirop Kopi Vanilla 250ml', 'type' => 'produk_jadi', 'stock' => 100, 'satuan' => 'pcs']);


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

        // Tambah 20 data penjualan dummy untuk keperluan test pagination
        for ($i = 1; $i <= 20; $i++) {
            $barangTerpilih = ($i % 2 === 0) ? $arabikaBubuk : $robustaBubuk;
            if ($barangTerpilih) {
                $data[] = [
                    'barang_id'    => $barangTerpilih->id,
                    'tanggal'      => now()->subDays($i + 1)->toDateString(),
                    'qty'          => rand(1, 5),
                    'harga_satuan' => $barangTerpilih->name === 'Kopi Arabika Bubuk 250gr' ? 55000 : 45000,
                    'pembeli'      => 'Pelanggan Dummy #' . $i,
                    'keterangan'   => 'Seeding otomatis untuk tes halaman #' . $i,
                ];
            }
        }

        foreach ($data as $item) {
            $barang = Barang::find($item['barang_id']);

            if ($barang) {
                // Pastikan stok cukup agar seeding berhasil
                if ($barang->stock < $item['qty']) {
                    $barang->increment('stock', $item['qty'] * 2);
                }

                Penjualan::create($item);
                $barang->decrement('stock', $item['qty']);
            }
        }

        $this->command->info('✅ PenjualanSeeder: ' . count($data) . ' transaksi penjualan berhasil dibuat.');
    }
}
