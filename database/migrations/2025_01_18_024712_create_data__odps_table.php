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
        Schema::create('data__odps', function (Blueprint $table) {
            $table->id('odp_id');
            $table->string('odp_kode')->nullable();
            $table->integer('odc_nama')->nullable();
            $table->integer('odp_id_odc')->nullable();
            $table->integer('odp_port_odc')->nullable();
            $table->string('odp_opm_out_odc')->nullable();
            $table->string('odp_opm_in')->nullable();
            $table->string('odp_opm_out')->nullable();
            $table->string('odp_core')->nullable();
            $table->string('odp_nama')->nullable();
            $table->string('odp_jumlah_port')->nullable();
            $table->string('odp_lokasi_img')->nullable();
            $table->string('odp_topologi_img')->nullable();
            $table->string('odp_koordinat')->nullable();
            $table->string('odp_keterangan')->nullable();
            $table->string('odp_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__odps');
    }
};
