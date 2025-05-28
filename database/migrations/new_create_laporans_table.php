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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->integer('lap_id')->nullable();
            $table->string('lap_tgl')->nullable();
            $table->string('lap_inv')->nullable();
            $table->string('lap_admin')->nullable();
            $table->string('lap_cabar')->nullable();
            $table->string('lap_kredit')->nullable();
            $table->string('lap_debet')->nullable();
            $table->integer('lap_fee_lingkungan')->nullable();
            $table->integer('lap_fee_kerja_sama')->nullable();
            $table->integer('lap_fee_marketing')->nullable();
            $table->integer('lap_ppn')->nullable();
            $table->string('lap_jumlah_bayar')->nullable();
            $table->string('lap_adm')->nullable();
            $table->string('lap_akun')->nullable();
            $table->string('lap_keterangan')->nullable();
            $table->string('lap_jenis_inv')->nullable();
            $table->string('lap_status')->nullable();
            $table->string('lap_img')->nullable();
            $table->string('lap_idpel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
