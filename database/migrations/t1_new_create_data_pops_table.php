<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
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
            $table->id();
            $table->foreignIdFor(Data_Site::class)->constrained()->on('data__sites')->onDelete('restrict');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('pop_nama')->nullable();
            $table->string('pop_alamat')->nullable();
            $table->string('pop_koordinat')->nullable();
            $table->string('pop_file_topologi')->nullable();
            $table->string('pop_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pops');
    }
};
