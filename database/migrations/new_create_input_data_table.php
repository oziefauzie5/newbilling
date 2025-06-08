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
        Schema::create('input_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Site::class)->constrained()->on('data__sites')->onDelete('restrict');
            $table->string('input_tgl')->nullable();
            $table->string('input_nama')->nullable();
            $table->string('input_ktp')->nullable();
            $table->string('input_hp')->nullable();
            $table->string('input_hp_2')->nullable();
            $table->string('input_email')->nullable();
            $table->string('input_alamat_ktp')->nullable();
            $table->string('input_alamat_pasang')->nullable();
            $table->string('input_sales')->nullable();
            $table->string('input_subseles')->nullable();
            $table->string('input_promo')->nullable();
            $table->string('password')->nullable();
            $table->string('input_maps')->nullable();
            $table->string('input_koordinat')->nullable();
            $table->string('input_status')->nullable();
            $table->string('input_keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_data');
    }
};
