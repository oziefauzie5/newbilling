<?php

use App\Models\Aplikasi\Corporate;
use App\Models\PSB\FtthFee;
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
        Schema::create('ftth_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('fee_idpel');
            $table->foreign('fee_idpel')->references('reg_idpel')->on('registrasis')->onDelete('cascade');
            $table->integer('reg_mitra')->nullable();
            $table->integer('reg_fee')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ftth_fees');
    }
};
