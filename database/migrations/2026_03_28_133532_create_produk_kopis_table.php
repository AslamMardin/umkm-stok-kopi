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
        Schema::create('produk_kopis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->enum('jenis_roast', ['light', 'medium', 'dark', 'extra_dark'])->default('medium');
            $table->string('kemasan');   // "200g", "500g", "1kg"
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_kopis');
    }
};
