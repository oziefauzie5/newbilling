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
        Schema::create('data_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->integer('data_lap_id')->nullable();
            $table->string('data_lap_tgl')->nullable();
            $table->integer('data_lap_pendapatan')->nullable();
            $table->integer('data_lap_admin')->nullable();
            $table->integer('data_lap_trx')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_laporans');
    }
};
