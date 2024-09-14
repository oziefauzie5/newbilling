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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->integer('jurnal_id')->nullable();
            $table->string('jurnal_tgl')->nullable();
            $table->string('jurnal_uraian')->nullable();
            $table->string('jurnal_kategori')->nullable();
            $table->string('jurnal_keterangan')->nullable();
            $table->string('jurnal_admin')->nullable();
            $table->string('jurnal_penerima')->nullable();
            $table->string('jurnal_idpel')->nullable();
            $table->string('jurnal_metode_bayar')->nullable();
            $table->string('jurnal_debet')->nullable();
            $table->string('jurnal_kredit')->nullable();
            $table->integer('jurnal_saldo')->nullable();
            $table->string('jurnal_img')->nullable();
            $table->string('jurnal_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
