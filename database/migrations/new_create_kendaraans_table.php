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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('trans_user_id')->nullable();
            $table->string('trans_divisi_id')->nullable();
            $table->string('trans_plat_nomor')->nullable();
            $table->string('trans_jenis_motor')->nullable();
            $table->string('trans_bensin')->nullable();
            $table->string('trans_service')->nullable();
            $table->string('trans_sewa')->nullable();
            $table->string('trans_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
