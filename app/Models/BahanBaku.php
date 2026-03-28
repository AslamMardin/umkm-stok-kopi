<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_bakus';

    protected $fillable = [
        'nama_bahan',   // Contoh: Green Bean Arabika, Green Bean Robusta
        'satuan',       // kg
        'stok',         // Stok saat ini (kg)
        'stok_minimum', // Batas minimum stok
        'harga_beli',   // Harga per kg
        'keterangan',
    ];

    protected $casts = [
        'stok'         => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'harga_beli'   => 'decimal:2',
    ];

    /**
     * Relasi ke detail pembelian bahan baku
     */
    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelianBahanBaku::class, 'bahan_baku_id');
    }

    /**
     * Relasi ke detail produksi (bahan yang digunakan)
     */
    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class, 'bahan_baku_id');
    }

    /**
     * Cek apakah stok di bawah minimum
     */
    public function isStokKritis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    /**
     * Tambah stok bahan baku
     */
    public function tambahStok(float $jumlah): void
    {
        $this->stok += $jumlah;
        $this->save();
    }

    /**
     * Kurangi stok bahan baku
     */
    public function kurangiStok(float $jumlah): void
    {
        if ($this->stok < $jumlah) {
            throw new \Exception("Stok bahan baku {$this->nama_bahan} tidak mencukupi. Stok tersedia: {$this->stok} {$this->satuan}");
        }
        $this->stok -= $jumlah;
        $this->save();
    }
}
