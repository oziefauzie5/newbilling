<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Kecamatan;
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
        Schema::create('data__kelurahans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
             $table->foreignIdFor(Data_Kecamatan::class)->constrained()->on('data__kecamatans')->onDelete('restrict');
            $table->string('kel_nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__kelurahans');
    }
};
