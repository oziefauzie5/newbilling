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
        Schema::create('data__outlets', function (Blueprint $table) {
            $table->id();
            $table->string('outlet_nama')->nullable();
            $table->string('outlet_pemilik')->nullable();
            $table->string('outlet_hp')->nullable();
            $table->string('outlet_mitra')->nullable();
            $table->string('outlet_alamat')->nullable();
            $table->string('outlet_tgl_gabung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__outlets');
    }
};
