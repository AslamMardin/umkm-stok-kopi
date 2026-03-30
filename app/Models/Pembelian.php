<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pembelian
 * Mencatat setiap transaksi pembelian bahan mentah dari supplier.
 */
class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'barang_id',
        'tanggal',
        'qty',
        'harga_satuan',
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

    /** Total harga = qty × harga_satuan */
    public function getTotalHargaAttribute(): float
    {
        return $this->qty * $this->harga_satuan;
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
