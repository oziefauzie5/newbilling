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
        Schema::create('sub_tikets', function (Blueprint $table) {
            $table->id();
            $table->string('subtiket_id')->nullable();
            $table->string('subtiket_admin')->nullable();
            $table->string('subtiket_status')->nullable();
            $table->string('subtiket_teknisi_team')->nullable();
            $table->text('subtiket_deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tikets');
    }
};
