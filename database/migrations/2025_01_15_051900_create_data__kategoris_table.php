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
        Schema::create('data__kategoris', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori')->nullable();
            $table->string('jenis_jurnal_kategori')->nullable();
            $table->string('kategori_satuan')->nullable();
            $table->string('status_kategori')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__kategoris');
    }
};
