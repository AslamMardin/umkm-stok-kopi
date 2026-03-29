<?php

namespace Database\Seeders;

use App\Models\ProdukKopi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukKopiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $produkList = [
            [
                'kode_produk'   => 'KPI-ARA-LGT-200',
                'nama_produk'   => 'Arabika Light Roast 200g',
                'jenis_roast'   => 'light',
                'kemasan'       => '200g',
                'stok'          => 50,
                'stok_minimum'  => 10,
                'harga_jual'    => 65000,
                'keterangan'    => 'Kopi Arabika sangrai ringan, cocok untuk pour-over',
            ],
            [
                'kode_produk'   => 'KPI-ARA-MED-200',
                'nama_produk'   => 'Arabika Medium Roast 200g',
                'jenis_roast'   => 'medium',
                'kemasan'       => '200g',
                'stok'          => 80,
                'stok_minimum'  => 10,
                'harga_jual'    => 62000,
                'keterangan'    => 'Kopi Arabika sangrai sedang, balanced flavor',
            ],
            // [
            //     'kode_produk'   => 'KPI-ROB-DRK-200',
            //     'nama_produk'   => 'Robusta Dark Roast 200g',
            //     'jenis_roast'   => 'dark',
            //     'kemasan'       => '200g',
            //     'stok'          => 100,
            //     'stok_minimum'  => 15,
            //     'harga_jual'    => 45000,
            //     'keterangan'    => 'Kopi Robusta sangrai gelap, cocok untuk espresso',
            // ],
            // [
            //     'kode_produk'   => 'KPI-ARA-MED-500',
            //     'nama_produk'   => 'Arabika Medium Roast 500g',
            //     'jenis_roast'   => 'medium',
            //     'kemasan'       => '500g',
            //     'stok'          => 30,
            //     'stok_minimum'  => 5,
            //     'harga_jual'    => 145000,
            //     'keterangan'    => 'Kopi Arabika sangrai sedang ukuran 500 gram',
            // ],
        ];

        foreach ($produkList as $produk) {
            ProdukKopi::create($produk);
        }
    }
}
