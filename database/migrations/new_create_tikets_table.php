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
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->string('tiket_id')->nullable();
            $table->string('tiket_status')->nullable();
            $table->string('tiket_departemen')->nullable();
            $table->string('tiket_nolayanan')->nullable();
            $table->string('tiket_idpel')->nullable();
            $table->string('tiket_pelanggan')->nullable();
            $table->string('tiket_admin')->nullable();
            $table->string('tiket_whatsapp')->nullable();
            $table->string('tiket_judul')->nullable();
            $table->string('tiket_prioritas')->nullable();
            $table->string('tiket_deskripsi')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};
