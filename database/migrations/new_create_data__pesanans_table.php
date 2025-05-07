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
        Schema::create('data__pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('pesanan_id')->nullable();
            $table->string('pesanan_siteid')->nullable();
            $table->string('pesanan_paketid')->nullable();
            $table->string('pesanan_mitraid')->nullable();
            $table->string('pesanan_outletid')->nullable();
            $table->string('pesanan_routerid')->nullable();
            $table->string('pesanan_jumlah')->nullable();
            $table->string('pesanan_hpp')->nullable();
            $table->string('pesanan_komisi')->nullable();
            $table->string('pesanan_total_hpp')->nullable();
            $table->string('pesanan_total_komisi')->nullable();
            $table->string('pesanan_admin')->nullable();
            $table->string('pesanan_tanggal')->nullable();
            $table->string('pesanan_tgl_proses')->nullable();
            $table->string('pesanan_tgl_bayar')->nullable();
            $table->string('pesanan_status')->nullable();
            $table->string('pesanan_status_generate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__pesanans');
    }
};
