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
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->integer('pesan_id_site')->nullable();
            $table->string('layanan')->nullable();
            $table->string('target')->nullable();
            $table->string('nama')->nullable();
            $table->string('schedule')->nullable();
            $table->string('delay')->nullable();
            $table->text('pesan')->nullable();
            $table->string('status')->nullable();
            $table->string('ket')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
