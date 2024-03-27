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
        Schema::create('input_data', function (Blueprint $table) {
            $table->id();
            $table->string('input_tgl')->nullable();
            $table->string('input_nama')->nullable();
            $table->string('input_ktp')->nullable();
            $table->string('input_hp')->nullable();
            $table->string('input_email')->nullable();
            $table->string('input_alamat_ktp')->nullable();
            $table->string('input_alamat_pasang')->nullable();
            $table->string('input_sales')->nullable();
            $table->string('input_subseles')->nullable();
            $table->string('input_password')->nullable();
            $table->string('input_maps')->nullable();
            $table->string('input_kordinat')->nullable();
            $table->string('input_status')->nullable();
            $table->string('input_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_data');
    }
};
