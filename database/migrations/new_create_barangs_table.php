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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('id_trx')->nullable();
            $table->integer('id_supplier')->nullable();
            $table->string('barang_kategori')->nullable();
            $table->string('barang_nama')->nullable();
            $table->integer('barang_masuk')->nullable();
            $table->integer('barang_keluar')->nullable();
            $table->integer('barang_harga')->nullable();
            $table->integer('barang_total')->nullable();
            $table->string('barang_ket')->nullable();
            $table->string('barang_tgl_beli')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
