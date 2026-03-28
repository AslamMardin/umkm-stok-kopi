<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $bahanBakus = [
            [
                'nama_bahan'    => 'Green Bean Arabika',
                'satuan'        => 'kg',
                'stok'          => 150.00,
                'stok_minimum'  => 20.00,
                'harga_beli'    => 55000,
                'keterangan'    => 'Biji kopi hijau jenis Arabika dari Polewali Mandar',
            ],
            [
                'nama_bahan'    => 'Green Bean Robusta',
                'satuan'        => 'kg',
                'stok'          => 200.00,
                'stok_minimum'  => 30.00,
                'harga_beli'    => 35000,
                'keterangan'    => 'Biji kopi hijau jenis Robusta lokal',
            ],
            [
                'nama_bahan'    => 'Kemasan Aluminium Foil 200g',
                'satuan'        => 'pcs',
                'stok'          => 500.00,
                'stok_minimum'  => 100.00,
                'harga_beli'    => 2500,
                'keterangan'    => 'Kemasan pouch aluminium foil ukuran 200 gram',
            ],
            [
                'nama_bahan'    => 'Kemasan Aluminium Foil 500g',
                'satuan'        => 'pcs',
                'stok'          => 300.00,
                'stok_minimum'  => 50.00,
                'harga_beli'    => 4000,
                'keterangan'    => 'Kemasan pouch aluminium foil ukuran 500 gram',
            ],
        ];

        foreach ($bahanBakus as $bahan) {
            BahanBaku::create($bahan);
        }
    }
}
