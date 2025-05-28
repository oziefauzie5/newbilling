<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Teknisi\Data_Olt;
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
        Schema::create('data__odcs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Olt::class)->constrained()->on('data__olts')->onDelete('restrict');
            $table->string('odc_id')->nullable();
            $table->integer('odc_pon_olt')->nullable();
            $table->integer('odc_core')->nullable();
            $table->string('odc_nama')->nullable();
            $table->integer('odc_jumlah_port')->nullable();
            $table->string('odc_file_topologi')->nullable();
            $table->string('odc_lokasi_img')->nullable();
            $table->string('odc_koordinat')->nullable();
            $table->text('odc_keterangan')->nullable();
            $table->string('odc_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__odcs');
    }
};
