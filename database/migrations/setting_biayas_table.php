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
        Schema::create('setting_biayas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('biaya_pasang')->nullable();
            $table->string('biaya_ppn')->nullable();
            $table->string('biaya_psb')->nullable();
            $table->string('biaya_sales')->nullable();
            $table->string('biaya_bph_uso')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_biayas');
    }
};
