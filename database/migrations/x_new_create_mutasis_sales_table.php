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
             $table->unsignedBigInteger('mutasi_sales_mitra_id');
            $table->foreign('mutasi_sales_mitra_id')->references('id')->on('users')->onDelete('restrict');
             $table->unsignedBigInteger('mutasi_sales_idpel');
            $table->foreign('mutasi_sales_idpel')->references('reg_idpel')->on('registrasis')->onDelete('restrict');
            $table->string('mutasi_sales_admin')->nullable();
            $table->enum('mutasi_sales_type', ['debet', 'credit']);
            $table->integer('mutasi_sales_jumlah')->nullable();
            $table->string('mutasi_sales_deskripsi')->nullable();
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
