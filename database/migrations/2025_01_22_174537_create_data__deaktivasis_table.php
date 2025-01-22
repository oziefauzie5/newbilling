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
        Schema::create('data__deaktivasis', function (Blueprint $table) {
            $table->id();
            $table->string('deaktivasi_idpel')->nullable();
            $table->string('deaktivasi_mac')->nullable();
            $table->string('deaktivasi_sn')->nullable();
            $table->string('deaktivasi_kelengkapan_perangkat')->nullable();
            $table->string('deaktivasi_tanggal_pengambilan')->nullable();
            $table->string('deaktivasi_pengambil_perangkat')->nullable();
            $table->string('deaktivasi_admin')->nullable();
            $table->string('deaktivasi_alasan_deaktivasi')->nullable();
            $table->string('deaktivasi_pernyataan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__deaktivasis');
    }
};
