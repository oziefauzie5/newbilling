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
        Schema::create('data__sites', function (Blueprint $table) {
            $table->id('site_id');
            $table->string('site_nama')->nullable();
            $table->string('site_prefix')->nullable();
            $table->string('site_brand')->nullable();
            $table->string('site_keterangan')->nullable();
            $table->string('site_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__sites');
    }
};
