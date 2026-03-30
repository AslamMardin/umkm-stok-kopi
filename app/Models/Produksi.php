<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Produksi
 * Mencatat konversi bahan mentah menjadi produk jadi.
 *
 * Relasi kunci:
 *   - barang_bahan_mentah_id → Barang (type: bahan_mentah) yang dikonsumsi
 *   - barang_produk_jadi_id  → Barang (type: produk_jadi) yang dihasilkan
 */
class Produksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_bahan_mentah_id',
        'barang_produk_jadi_id',
        'tanggal',
        'qty_bahan_mentah',
        'qty_produk_jadi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal'          => 'date',
        'qty_bahan_mentah' => 'integer',
        'qty_produk_jadi'  => 'integer',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    /** Bahan mentah yang dikonsumsi dalam proses ini */
    public function bahanMentah()
    {
        return $this->belongsTo(Barang::class, 'barang_bahan_mentah_id');
    }

    /** Produk jadi yang dihasilkan dari proses ini */
    public function produkJadi()
    {
        return $this->belongsTo(Barang::class, 'barang_produk_jadi_id');
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /** Rasio konversi: berapa unit produk jadi per 1 unit bahan mentah */
    public function getRasioKonversiAttribute(): float
    {
        if ($this->qty_bahan_mentah === 0) return 0;
        return round($this->qty_produk_jadi / $this->qty_bahan_mentah, 2);
    }
}
