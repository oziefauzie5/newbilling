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
        Schema::create('data_pops', function (Blueprint $table) {
            $table->id('pop_id');
            $table->string('pop_kode')->nullable();
            $table->integer('pop_id_site')->nullable();
            $table->string('pop_nama')->nullable();
            $table->string('pop_alamat')->nullable();
            $table->string('pop_koordinat')->nullable();
            $table->string('pop_ip1')->nullable();
            $table->string('pop_ip2')->nullable();
            $table->string('pop_topologi_img')->nullable();
            $table->string('pop_keterangan')->nullable();
            $table->string('pop_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pops');
    }
};
