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
        Schema::create('data_laporans', function (Blueprint $table) {
            $table->id();
            $table->integer('data_lap_id')->nullable();
            $table->string('data_lap_tgl')->nullable();
            $table->integer('data_lap_pendapatan')->nullable();
            $table->integer('data_lap_tunai')->nullable();
            $table->integer('data_lap_adm')->nullable();
            $table->integer('data_lap_admin')->nullable();
            $table->integer('data_lap_refund')->nullable();
            $table->string('data_lap_trx')->nullable();
            $table->string('data_lap_keterangan')->nullable();
            $table->string('data_lap_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_laporans');
    }
};
