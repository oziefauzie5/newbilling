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
        Schema::create('kode_promos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('promo_id');
            $table->string('promo_nama')->nullable();
             $table->unsignedBigInteger('promo_paket_id');
            $table->foreign('promo_paket_id')->references('paket_id')->on('pakets')->onDelete('restrict')->onUpdate('cascade');
            $table->integer('promo_harga')->nullable();
            $table->string('promo_expired')->nullable();
            $table->enum('promo_status',['Enable','Disable'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_promos');
    }
};
