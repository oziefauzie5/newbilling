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
        Schema::create('mutasi_saless', function (Blueprint $table) {
            $table->id();
            $table->string('smt_user_id')->nullable();
            $table->string('smt_admin')->nullable();
            $table->string('smt_kategori')->nullable();
            $table->text('smt_deskripsi')->nullable();
            $table->string('smt_cabar')->nullable();
            $table->integer('smt_kredit')->nullable();
            $table->integer('smt_debet')->nullable();
            $table->integer('smt_saldo')->nullable();
            $table->integer('smt_biaya_adm')->nullable();
            $table->string('smt_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_saless');
    }
};
