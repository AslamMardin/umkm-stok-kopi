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
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_produksi')->unique();
            $table->date('tanggal_produksi');
            $table->enum('jenis_proses', ['roasting', 'packing', 'roasting_packing']);
            $table->enum('status', ['proses', 'selesai', 'dibatalkan'])->default('proses');
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
