<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'nama_pelanggan'  => 'Kafe Mandar Coffee',
                'alamat'          => 'Jl. Jenderal Sudirman No. 12, Polewali',
                'no_telepon'      => '085678901234',
                'email'           => 'mandar.coffee@gmail.com',
                'jenis_pelanggan' => 'cafe',
            ],
            [
                'nama_pelanggan'  => 'Toko Bu Rahma',
                'alamat'          => 'Pasar Sentral Polewali',
                'no_telepon'      => '086789012345',
                'email'           => null,
                'jenis_pelanggan' => 'retail',
            ],
            [
                'nama_pelanggan'  => 'CV Kopi Nusantara',
                'alamat'          => 'Jl. Gatot Subroto No. 8, Makassar',
                'no_telepon'      => '087890123456',
                'email'           => 'cv.kopinusantara@gmail.com',
                'jenis_pelanggan' => 'reseller',
            ],
        ];

        foreach ($pelanggans as $pelanggan) {
            Pelanggan::create($pelanggan);
        }
    }
}
