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
        Schema::create('data__barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->integer('bk_id')->nullable();
            $table->string('bk_jenis_laporan')->nullable();
            $table->string('bk_id_tiket')->nullable();
            $table->integer('bk_id_barang')->nullable();
            $table->string('bk_kategori')->nullable();
            $table->string('bk_satuan')->nullable();
            $table->string('bk_harga')->nullable();
            $table->string('bk_nama_barang')->nullable();
            $table->string('bk_model')->nullable();
            $table->string('bk_mac')->nullable();
            $table->string('bk_sn')->nullable();
            $table->string('bk_jumlah')->nullable();
            $table->string('bk_keperluan')->nullable();
            $table->string('bk_foto_awal')->nullable();
            $table->string('bk_foto_akhir')->nullable();
            $table->string('bk_nama_penggunan')->nullable();
            $table->string('bk_waktu_keluar')->nullable();
            $table->string('bk_admin_input')->nullable();
            $table->string('bk_penerima')->nullable();
            $table->string('bk_status')->nullable();
            $table->string('bk_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__barang_keluars');
    }
};
