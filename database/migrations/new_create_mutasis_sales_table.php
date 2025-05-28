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
             $table->unsignedBigInteger('mitra_id');
            $table->foreign('mitra_id')->references('id')->on('users')->onDelete('restrict');
             $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('reg_idpel')->on('registrasi')->onDelete('restrict');
            $table->string('merchant')->nullable();
            $table->string('type')->nullable();
            $table->enum('type', ['debet', 'credit']);
            $table->decimal('amount', 15, 1);
            $table->string('description')->nullable();
            $table->string('fee_merchant')->nullable();
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
