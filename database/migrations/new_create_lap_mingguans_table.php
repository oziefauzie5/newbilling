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
        Schema::create('lap_mingguans', function (Blueprint $table) {
            $table->id();
            $table->integer('lm_id');
            $table->integer('lm_admin')->nullable();
            $table->integer('lm_debet')->nullable();
            $table->integer('lm_kredit')->nullable();
            $table->integer('lm_adm')->nullable();
            $table->integer('lm_akun')->nullable();
            $table->text('lm_keterangan')->nullable();
            $table->text('lm_periode')->nullable();
            $table->string('lm_status')->nullable();
            $table->string('lm_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lap_mingguans');
    }
};
