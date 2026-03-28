<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'no_penjualan',
        'pelanggan_id',
        'tanggal_jual',
        'total_harga',
        'diskon',
        'total_bayar',
        'metode_bayar',  // tunai, transfer, cod
        'status',        // pending, lunas, dibatalkan
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_jual' => 'date',
        'total_harga'  => 'decimal:2',
        'diskon'       => 'decimal:2',
        'total_bayar'  => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    /**
     * Konfirmasi penjualan: kurangi stok produk secara otomatis
     */
    public function konfirmasiPenjualan(): void
    {
        if ($this->status === 'lunas') {
            throw new \Exception("Penjualan ini sudah dikonfirmasi.");
        }

        \DB::transaction(function () {
            foreach ($this->detailPenjualans as $detail) {
                $detail->produkKopi->kurangiStok($detail->jumlah);
            }
            $this->status = 'lunas';
            $this->save();
        });
    }

    /**
     * Batalkan penjualan: kembalikan stok produk
     */
    public function batalkanPenjualan(): void
    {
        if ($this->status !== 'lunas') {
            throw new \Exception("Hanya penjualan berstatus 'lunas' yang bisa dibatalkan.");
        }

        \DB::transaction(function () {
            foreach ($this->detailPenjualans as $detail) {
                $detail->produkKopi->tambahStok($detail->jumlah);
            }
            $this->status = 'dibatalkan';
            $this->save();
        });
    }
}
