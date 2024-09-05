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
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();
            $table->integer('kasbon_user_id')->nullable();
            $table->string('kasbon_jenis')->nullable();
            $table->integer('kasbon_tempo')->nullable();
            $table->string('kasbon_uraian')->nullable();
            $table->string('kasbon_kredit')->nullable();
            $table->string('kasbon_debet')->nullable();
            $table->string('kasbon_file')->nullable();
            $table->string('kasbon_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbons');
    }
};
