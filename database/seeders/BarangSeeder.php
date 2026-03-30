<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

/**
 * BarangSeeder
 * Data dummy barang untuk UMKM kopi.
 *
 * Bahan Mentah  : biji kopi, gula pasir, susu full cream, coklat bubuk, dll.
 * Produk Jadi   : kopi kemasan, sirop kopi, kopi sachetan, dll.
 */
class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // ──── BAHAN MENTAH ────────────────────────────────────
        $bahanMentah = [
            ['name' => 'Biji Kopi Arabika',        'satuan' => 'kg',   'stock' => 200],
            ['name' => 'Biji Kopi Robusta',         'satuan' => 'kg',   'stock' => 150],
            
        ];

        foreach ($bahanMentah as $item) {
            Barang::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['type' => 'bahan_mentah'])
            );
        }

        // ──── PRODUK JADI ─────────────────────────────────────
        $produkJadi = [
            ['name' => 'Kopi Bubuk',  'satuan' => 'pcs',  'stock' => 0],
           
        ];

        foreach ($produkJadi as $item) {
            Barang::updateOrCreate(
                ['name' => $item['name']],
                array_merge($item, ['type' => 'produk_jadi'])
            );
        }

        $this->command->info('✅ BarangSeeder: ' . count($bahanMentah) . ' bahan mentah & ' . count($produkJadi) . ' produk jadi berhasil dibuat.');
    }
}
