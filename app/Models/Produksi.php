<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksis';

    protected $fillable = [
        'no_produksi',
        'tanggal_produksi',
        'jenis_proses',     // roasting, packing, roasting_packing
        'status',           // proses, selesai, dibatalkan
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class, 'produksi_id');
    }

    /**
     * Selesaikan produksi:
     * 1. Kurangi stok bahan baku sesuai pemakaian
     * 2. Tambah stok produk jadi sesuai hasil
     */
    public function selesaikanProduksi(): void
    {
        if ($this->status === 'selesai') {
            throw new \Exception("Produksi ini sudah diselesaikan.");
        }

        \DB::transaction(function () {
            foreach ($this->detailProduksis as $detail) {
                // Kurangi bahan baku yang digunakan
                if ($detail->bahan_baku_id && $detail->jumlah_bahan_digunakan > 0) {
                    $detail->bahanBaku->kurangiStok($detail->jumlah_bahan_digunakan);
                }
                // Tambah produk jadi yang dihasilkan
                if ($detail->produk_id && $detail->jumlah_produk_dihasilkan > 0) {
                    $detail->produkKopi->tambahStok($detail->jumlah_produk_dihasilkan);
                }
            }
            $this->status = 'selesai';
            $this->save();
        });
    }
}
