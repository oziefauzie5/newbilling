<?php

use App\Models\Aplikasi\Corporate;
use App\Models\PSB\FtthInstalasi;
use App\Models\Teknisi\Data_Odp;
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
        Schema::create('ftth_instalasis', function (Blueprint $table) {
            $table->id();
            $table->foreign('id')->references('reg_idpel')->on('registrasis')->onDelete('cascade');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Odp::class)->constrained()->on('data__odps')->onDelete('restrict');
             $table->unsignedBigInteger('reg_noc');
             $table->unsignedBigInteger('reg_router');
            $table->foreign('reg_noc')->references('id')->on('users')->onDelete('restrict');
            $table->string('reg_out_odp')->nullable();
            $table->string('reg_in_ont')->nullable();
            $table->integer('reg_slot_odp')->nullable(); #ganti slot onu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ftth_instalasis');
    }
};
