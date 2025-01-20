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
        Schema::create('data__olts', function (Blueprint $table) {
            $table->id();
            $table->integer('olt_id')->nullable();
            $table->string('olt_kode')->nullable();
            $table->integer('olt_id_pop')->nullable();
            $table->string('olt_nama')->nullable();
            $table->string('olt_merek')->nullable();
            $table->string('olt_mac')->nullable();
            $table->string('olt_sn')->nullable();
            $table->string('olt_pon')->nullable();
            $table->string('olt_ip')->nullable();
            $table->string('olt_username')->nullable();
            $table->string('olt_password')->nullable();
            $table->string('olt_ip_default')->nullable();
            $table->string('olt_username_default')->nullable();
            $table->string('olt_password_default')->nullable();
            $table->string('olt_topologi_img')->nullable();
            $table->string('olt_keterangan')->nullable();
            $table->string('olt_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__olts');
    }
};
