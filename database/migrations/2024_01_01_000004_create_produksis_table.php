<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: produksis
 * Mencatat setiap batch konversi bahan mentah → produk jadi.
 *
 * Logika stok:
 *   - barangs.stock WHERE id = barang_bahan_mentah_id   -= qty_bahan_mentah
 *   - barangs.stock WHERE id = barang_produk_jadi_id    += qty_produk_jadi
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();

            // Bahan mentah yang digunakan
            $table->foreignId('barang_bahan_mentah_id')
                  ->constrained('barangs')
                  ->cascadeOnDelete();

            // Produk jadi yang dihasilkan
            $table->foreignId('barang_produk_jadi_id')
                  ->constrained('barangs')
                  ->cascadeOnDelete();

            $table->date('tanggal');
            $table->unsignedInteger('qty_bahan_mentah'); // berapa bahan yang dipakai
            $table->unsignedInteger('qty_produk_jadi');  // berapa produk yang dihasilkan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
