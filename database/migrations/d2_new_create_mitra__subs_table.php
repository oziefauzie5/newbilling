<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
use App\Models\Mitra\MitraSetting;
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
        Schema::create('mitra__subs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mts_sub_user_id');
            $table->foreign('mts_sub_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->unsignedBigInteger('mts_sub_mitra_id');
            $table->foreign('mts_sub_mitra_id')->references('mts_user_id')->on('mitra_settings')->onDelete('restrict');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra__subs');
    }
};
