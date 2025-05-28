<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Router\Router;
use App\Models\Teknisi\Data_pop;
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
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Router::class)->constrained()->on('routers')->onDelete('restrict');
            $table->integer('olt_pon')->nullable();
            $table->string('olt_nama')->nullable();
            $table->string('olt_file_topologi')->nullable();
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
