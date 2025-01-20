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
        Schema::create('data__sub_tikets', function (Blueprint $table) {
            $table->id();
            $table->integer('subtiket_id')->nullable();
            $table->integer('subtiket_kode_barang')->nullable();
            $table->string('subtiket_jenis_barang')->nullable();
            $table->string('subtiket_jumlah_barang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__sub_tikets');
    }
};
