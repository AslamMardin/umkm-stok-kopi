<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Barang
 *
 * Merepresentasikan semua barang dalam sistem SCM.
 * Dibedakan menjadi dua tipe:
 *   - bahan_mentah : biji kopi, gula, susu, dll.
 *   - produk_jadi  : kopi bubuk kemasan, sirop kopi, dll.
 *
 * @property int    $id
 * @property string $name
 * @property string $type   bahan_mentah | produk_jadi
 * @property int    $stock
 * @property string $satuan kg, gram, liter, pcs, dll.
 */
class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'stock',
        'satuan',
    ];

    protected $casts = [
        'stock' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Scope Queries
    // ──────────────────────────────────────────

    /** Filter hanya bahan mentah */
    public function scopeBahanMentah($query)
    {
        return $query->where('type', 'bahan_mentah');
    }

    /** Filter hanya produk jadi */
    public function scopeProdukJadi($query)
    {
        return $query->where('type', 'produk_jadi');
    }

    /** Filter barang dengan stok rendah */
    public function scopeStokRendah($query, int $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold);
    }

    // ──────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────

    /** Label tipe yang ramah tampilan */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'bahan_mentah' => 'Bahan Mentah',
            'produk_jadi'  => 'Produk Jadi',
            default        => ucfirst($this->type),
        };
    }

    /** Badge warna tipe untuk Blade */
    public function getTypeBadgeAttribute(): string
    {
        return match ($this->type) {
            'bahan_mentah' => 'warning',
            'produk_jadi'  => 'success',
            default        => 'secondary',
        };
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    /** Pembelian yang pernah dilakukan untuk barang ini */
    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }

    /** Penjualan yang pernah dilakukan untuk barang ini */
    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }

    /** Proses produksi di mana barang ini digunakan sebagai bahan mentah */
    public function produksiSebagaiBahanMentah()
    {
        return $this->hasMany(Produksi::class, 'barang_bahan_mentah_id');
    }

    /** Proses produksi di mana barang ini dihasilkan sebagai produk jadi */
    public function produksiSebagaiProdukJadi()
    {
        return $this->hasMany(Produksi::class, 'barang_produk_jadi_id');
    }
}
