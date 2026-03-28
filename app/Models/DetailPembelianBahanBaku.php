<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian_bahan_bakus';

    protected $fillable = [
        'pembelian_id',
        'bahan_baku_id',
        'jumlah',       // Jumlah dalam kg
        'harga_satuan', // Harga per kg saat pembelian
        'subtotal',
    ];

    protected $casts = [
        'jumlah'       => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'pembelian_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
