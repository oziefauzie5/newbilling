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
        Schema::create('mitra_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mts_id')->nullable();
            $table->string('mts_user_id')->nullable();
            $table->integer('mts_limit_minus')->nullable();
            $table->integer('mts_kode_unik')->nullable();
            $table->integer('mts_komisi')->nullable();
            $table->integer('mts_komisi_sales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_settings');
    }
};
