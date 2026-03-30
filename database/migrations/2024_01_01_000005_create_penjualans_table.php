<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: penjualans
 * Mencatat setiap transaksi penjualan produk jadi ke konsumen.
 * Setiap record mengurangi stok produk jadi terkait.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();

            // Produk yang dijual (hanya produk_jadi)
            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->cascadeOnDelete();

            $table->date('tanggal');
            $table->unsignedInteger('qty');
            $table->decimal('harga_satuan', 12, 2);
            $table->string('pembeli')->nullable(); // nama pembeli/pelanggan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
