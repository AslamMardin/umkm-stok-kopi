<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel: pembelians
 * Log setiap pembelian bahan mentah dari supplier.
 * Setiap record menambah stok barang terkait.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();

            // Relasi ke supplier
            $table->foreignId('supplier_id')
                  ->constrained('suppliers')
                  ->cascadeOnDelete(); // Supplier tidak bisa dihapus jika masih ada pembelian

            // Relasi ke barang yang dibeli
            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->cascadeOnDelete();

            $table->date('tanggal');
            $table->unsignedInteger('qty');
            $table->decimal('harga_satuan', 12, 2);

            // Total = qty × harga_satuan (bisa dihitung, tidak disimpan double)
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
