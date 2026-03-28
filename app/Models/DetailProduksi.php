<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Detail Produksi mencatat:
 * - Bahan baku yang digunakan (bahan_baku_id + jumlah_bahan_digunakan)
 * - Produk yang dihasilkan (produk_id + jumlah_produk_dihasilkan)
 * Satu record produksi bisa memiliki banyak detail (bahan masuk & produk keluar).
 */
class DetailProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_produksis';

    protected $fillable = [
        'produksi_id',
        'bahan_baku_id',              // nullable: bahan yang digunakan
        'jumlah_bahan_digunakan',     // dalam kg, nullable
        'produk_id',                  // nullable: produk yang dihasilkan
        'jumlah_produk_dihasilkan',   // dalam pcs, nullable
        'keterangan',
    ];

    protected $casts = [
        'jumlah_bahan_digunakan'   => 'decimal:2',
        'jumlah_produk_dihasilkan' => 'integer',
    ];

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    public function produkKopi()
    {
        return $this->belongsTo(ProdukKopi::class, 'produk_id');
    }
}
