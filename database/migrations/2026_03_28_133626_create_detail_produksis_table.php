<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksis')->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')->nullable()->constrained('bahan_bakus')->nullOnDelete();
            $table->decimal('jumlah_bahan_digunakan', 10, 2)->nullable();  // kg
            $table->foreignId('produk_id')->nullable()->constrained('produk_kopis')->nullOnDelete();
            $table->integer('jumlah_produk_dihasilkan')->nullable();       // pcs
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_produksis');
    }
};
