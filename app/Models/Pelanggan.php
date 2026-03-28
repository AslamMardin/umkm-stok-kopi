<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';

    protected $fillable = [
        'nama_pelanggan',
        'alamat',
        'no_telepon',
        'email',
        'jenis_pelanggan', // retail, reseller, cafe
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }
}
