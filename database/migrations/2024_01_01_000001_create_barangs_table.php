<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: barangs
 * Menyimpan semua barang: bahan mentah & produk jadi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();

            // Nama unik barang
            $table->string('name')->unique();

            // Tipe barang: bahan_mentah atau produk_jadi
            $table->enum('type', ['bahan_mentah', 'produk_jadi']);

            // Stok saat ini (tidak boleh negatif)
            $table->unsignedInteger('stock')->default(0);

            // Satuan pengukuran (kg, gram, pcs, liter, dll.)
            $table->string('satuan', 50)->default('pcs');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
