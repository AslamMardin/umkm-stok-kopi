<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualans';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'jumlah',       // pcs
        'harga_satuan', // Harga per pcs saat transaksi
        'diskon_item',  // Diskon per item
        'subtotal',
    ];

    protected $casts = [
        'jumlah'       => 'integer',
        'harga_satuan' => 'decimal:2',
        'diskon_item'  => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function produkKopi()
    {
        return $this->belongsTo(ProdukKopi::class, 'produk_id');
    }
}
