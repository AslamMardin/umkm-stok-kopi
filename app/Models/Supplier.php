<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telepon',
        'email',
        'keterangan',
    ];

    /**
     * Relasi ke pembelian bahan baku
     */
    public function pembelianBahanBakus()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'supplier_id');
    }
}
