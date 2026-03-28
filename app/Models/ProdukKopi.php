<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukKopi extends Model
{
    use HasFactory;

    protected $table = 'produk_kopis';

    protected $fillable = [
        'kode_produk',
        'nama_produk',    // Contoh: Arabika Medium Roast 200g
        'jenis_roast',    // light, medium, dark
        'kemasan',        // 200g, 500g, 1kg
        'stok',           // Stok produk jadi (pcs)
        'stok_minimum',
        'harga_jual',     // Harga jual per pcs
        'keterangan',
    ];

    protected $casts = [
        'stok'          => 'integer',
        'stok_minimum'  => 'integer',
        'harga_jual'    => 'decimal:2',
    ];

    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class, 'produk_id');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }

    public function isStokKritis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    /**
     * Tambah stok produk jadi
     */
    public function tambahStok(int $jumlah): void
    {
        $this->stok += $jumlah;
        $this->save();
    }

    /**
     * Kurangi stok produk jadi saat penjualan
     */
    public function kurangiStok(int $jumlah): void
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok produk {$this->nama_produk} tidak mencukupi. Stok tersedia: {$this->stok} pcs");
        }
        $this->stok -= $jumlah;
        $this->save();
    }
}
