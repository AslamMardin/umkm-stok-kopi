<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Penjualan
 * Mencatat setiap transaksi penjualan produk jadi ke konsumen.
 */
class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'tanggal',
        'qty',
        'harga_satuan',
        'pembeli',
        'keterangan',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'qty'          => 'integer',
        'harga_satuan' => 'decimal:2',
    ];

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    public function getTotalHargaAttribute(): float
    {
        return $this->qty * $this->harga_satuan;
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
