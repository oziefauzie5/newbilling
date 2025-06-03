<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Teknisi\Data_Odc;
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
        Schema::create('data__odps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Odc::class)->constrained()->on('data__odcs')->onDelete('restrict');
            $table->string('odp_id')->nullable();
            $table->string('odp_core')->nullable();
            $table->string('odp_nama')->nullable();
            $table->string('odp_jumlah_slot')->nullable();
            $table->string('odp_lokasi_img')->nullable();
            $table->string('odp_file_topologi')->nullable();
            $table->string('odp_koordinat')->nullable();
            $table->string('odp_keterangan')->nullable();
            $table->string('odp_status')->nullable();
            $table->string('odp_slot_odc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__odps');
    }
};
