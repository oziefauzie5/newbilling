<?php

use App\Models\Aplikasi\Corporate;
use App\Models\User;
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
        Schema::create('teknisis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->on('users')->onDelete('restrict');

            $table->unsignedBigInteger('teknisi_idpel');
            $table->foreign('teknisi_idpel')->references('reg_idpel')->on('registrasis')->onDelete('cascade');
            $table->string('teknisi_team')->nullable();
            $table->string('teknisi_ket')->nullable();
            $table->string('teknisi_job')->nullable();
            $table->string('teknisi_psb')->nullable();
            $table->string('teknisi_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teknisis');
    }
};
