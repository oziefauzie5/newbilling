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
        Schema::create('operasionals', function (Blueprint $table) {
            $table->id();
            $table->integer('op_id')->nullable();
            $table->string('op_tgl')->nullable();
            $table->string('op_uraian')->nullable();
            $table->string('op_kategori')->nullable();
            $table->string('op_admin')->nullable();
            $table->string('op_metode_bayar')->nullable();
            $table->string('op_debet')->nullable();
            $table->string('op_kredit')->nullable();
            $table->string('op_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operasionals');
    }
};
