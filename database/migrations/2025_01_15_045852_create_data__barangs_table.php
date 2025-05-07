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
        Schema::create('data__barangs', function (Blueprint $table) {
            $table->id();
            $table->string('barang_id')->nullable();
            $table->string('barang_id_group')->nullable();
            $table->string('barang_lokasi')->nullable();
            $table->string('barang_kategori')->nullable();
            $table->string('barang_jenis')->nullable();
            $table->string('barang_jenis_jurnal')->nullable();
            $table->string('barang_nama')->nullable();
            $table->string('barang_merek')->nullable();
            $table->integer('barang_qty')->nullable();
            $table->integer('barang_digunakan')->nullable();
            $table->integer('barang_dijual')->nullable();
            $table->integer('barang_rusak')->nullable();
            $table->integer('barang_hilang')->nullable();
            $table->integer('barang_pengembalian')->nullable();
            $table->string('barang_satuan')->nullable();
            $table->string('barang_sn')->nullable();
            $table->string('barang_mac')->nullable();
            $table->string('barang_mac_olt')->nullable();
            $table->string('barang_tglmasuk')->nullable();
            $table->integer('barang_harga')->nullable();
            $table->string('barang_status')->nullable();
            $table->string('barang_img')->nullable();
            $table->string('barang_ket')->nullable();
            $table->string('barang_nama_pengguna')->nullable();
            $table->string('barang_admin_update')->nullable();
            $table->string('barang_penerima')->nullable();
            $table->string('barang_pengecek')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__barangs');
    }
};
