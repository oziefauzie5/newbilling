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
            $table->id('lap_id');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('lap_tgl')->nullable();
            $table->integer('lap_inv')->nullable();
            $table->enum('lap_jenis_inv', ['Debit', 'Credit']);
            $table->integer('lap_fee_mitra')->nullable();
            $table->integer('lap_ppn')->nullable();
            $table->integer('lap_bph_uso')->nullable();
            $table->string('lap_pokok')->nullable();
            $table->string('lap_jumlah')->nullable();
            $table->string('lap_admin')->nullable();
            $table->string('lap_akun')->nullable();
            $table->string('lap_keterangan')->nullable();
            $table->string('lap_status')->nullable();
            $table->string('lap_img')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
