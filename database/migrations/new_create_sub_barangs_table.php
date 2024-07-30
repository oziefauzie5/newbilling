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
        Schema::create('sub_barangs', function (Blueprint $table) {
            $table->id('id_subbarang');
            $table->integer('subbarang_idbarang')->nullable();
            $table->string('subbarang_nama')->nullable();
            $table->string('subbarang_ktg')->nullable();
            $table->string('subbarang_qty')->nullable();
            $table->string('subbarang_keluar')->nullable();
            $table->string('subbarang_stok')->nullable();
            $table->string('subbarang_harga')->nullable();
            $table->string('subbarang_keterangan')->nullable();
            $table->string('subbarang_sn')->nullable();
            $table->string('subbarang_mac')->nullable();
            $table->string('subbarang_status')->nullable();
            $table->string('subbarang_tgl_masuk')->nullable();
            $table->string('subbarang_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_barangs');
    }
};
