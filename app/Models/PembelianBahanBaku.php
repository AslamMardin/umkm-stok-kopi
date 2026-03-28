<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan_bakus';

    protected $fillable = [
        'no_pembelian',  // Nomor transaksi unik
        'supplier_id',
        'tanggal_beli',
        'total_harga',
        'status',        // pending, diterima, dibatalkan
        'catatan',
        'user_id',       // User yang mencatat
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
        'total_harga'  => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function detailPembelians()
    {
        return $this->hasMany(DetailPembelianBahanBaku::class, 'pembelian_id');
    }

    /**
     * Hitung total harga dari detail dan update stok bahan baku
     * Dipanggil saat status menjadi "diterima"
     */
    public function terimaPembelian(): void
    {
        if ($this->status !== 'diterima') {
            foreach ($this->detailPembelians as $detail) {
                $detail->bahanBaku->tambahStok($detail->jumlah);
            }
            $this->status = 'diterima';
            $this->save();
        }
    }
}
