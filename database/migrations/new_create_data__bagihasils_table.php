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
        Schema::create('data__bagihasils', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('bh_id')->nullable();
            $table->string('bh_mitraid')->nullable();
            $table->string('bh_keterangan')->nullable();
            $table->string('bh_saldo')->nullable();
            $table->string('bh_persen')->nullable();
            $table->string('bh_total_persentase')->nullable();
            $table->string('bh_status')->nullable();
            $table->string('bh_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__bagihasils');
    }
};
