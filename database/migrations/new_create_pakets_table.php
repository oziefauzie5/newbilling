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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id('paket_id');
            $table->string('paket_nama')->nullable();
            $table->string('paket_limitasi')->nullable();
            $table->string('paket_shared')->nullable();
            $table->string('paket_masa_aktif')->nullable();
            $table->integer('paket_komisi');
            $table->integer('paket_harga');
            $table->string('paket_lokal')->nullable();
            $table->string('paket_remote_address')->nullable();
            $table->string('paket_qris')->nullable();
            $table->string('paket_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
