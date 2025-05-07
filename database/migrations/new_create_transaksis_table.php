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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('trx_kategori')->nullable();
            $table->string('trx_jenis')->nullable();
            $table->string('trx_admin')->nullable();
            $table->string('trx_deskripsi')->nullable();
            $table->integer('trx_qty')->nullable();
            $table->integer('trx_kredit')->nullable();
            $table->integer('trx_debet')->nullable();
            $table->integer('trx_saldo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
