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
        Schema::create('data__odcs', function (Blueprint $table) {
            $table->id('odc_id');
            $table->string('odc_kode')->nullable();
            $table->integer('odc_id_olt')->nullable();
            $table->integer('odc_pon_olt')->nullable();
            $table->integer('odc_core')->nullable();
            $table->string('odc_nama')->nullable();
            $table->integer('odc_jumlah_port')->nullable();
            $table->string('odc_topologi_img')->nullable();
            $table->string('odc_koordinat')->nullable();
            $table->string('odc_lokasi_img')->nullable();
            $table->text('odc_keterangan')->nullable();
            $table->string('odc_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__odcs');
    }
};
