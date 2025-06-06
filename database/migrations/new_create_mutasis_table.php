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
        Schema::create('mutasis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('mt_mts_id');
            $table->foreign('mt_mts_id')->references('mts_user_id')->on('mitra_settings')->onDelete('restrict');
            $table->string('mt_admin')->nullable();
            $table->string('mt_kategori')->nullable();
            $table->text('mt_deskripsi')->nullable();
            $table->string('mt_cabar')->nullable();
            $table->integer('mt_kredit')->nullable();
            $table->integer('mt_debet')->nullable();
            $table->integer('mt_saldo')->nullable();
            $table->integer('mt_biaya_adm')->nullable();
            $table->string('mt_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasis');
    }
};
