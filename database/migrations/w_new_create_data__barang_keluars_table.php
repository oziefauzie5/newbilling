<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Gudang\Data_Barang;
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
            $table->id('bk_id');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('bk_id_barang');
            $table->foreign('bk_id_barang')->references('barang_id')->on('data__barangs')->onDelete('restrict');
            $table->string('bk_jenis_laporan')->nullable();
            $table->string('bk_kategori')->nullable();
            $table->string('bk_harga')->nullable();
            $table->string('bk_before')->nullable();
            $table->string('bk_after')->nullable();
            $table->string('bk_terpakai')->nullable();
            $table->string('bk_jumlah')->nullable();
            $table->string('bk_keperluan')->nullable();
            $table->string('bk_file_bukti')->nullable();
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
