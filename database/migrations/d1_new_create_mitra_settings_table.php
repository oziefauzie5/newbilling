<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
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
        Schema::create('mitra_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mts_user_id');
            $table->foreign('mts_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
             $table->foreignIdFor(Data_Site::class)->constrained()->on('data__sites')->onDelete('restrict');
            $table->integer('mts_limit_minus')->nullable();
            $table->integer('mts_komisi')->nullable();
            $table->integer('mts_fee')->nullable();
            $table->string('mts_wilayah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_settings');
    }
};
