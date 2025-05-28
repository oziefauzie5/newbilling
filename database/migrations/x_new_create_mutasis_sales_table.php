<?php

use App\Models\Aplikasi\Corporate;
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
        Schema::create('mutasi_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
             $table->unsignedBigInteger('mts_mitra_id');
            $table->foreign('mts_mitra_id')->references('id')->on('users')->onDelete('restrict');
             $table->unsignedBigInteger('mts_mitra_idpel');
            $table->foreign('mts_mitra_idpel')->references('reg_idpel')->on('registrasis')->onDelete('restrict');
            $table->string('mts_admin')->nullable();
            $table->enum('mts_type', ['debet', 'credit']);
            $table->integer('mts_jumlah')->nullable();
            $table->string('mts_deskripsi')->nullable();
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
