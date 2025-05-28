<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Teknisi\Data_Olt;
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
        Schema::create('data__vlans', function (Blueprint $table) {
            $table->id();
             $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
           $table->foreignIdFor(Data_Olt::class)->constrained()->on('data__olts')->onDelete('restrict');
            $table->integer('vlan_id')->nullable();
            $table->integer('vlan_pon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__vlans');
    }
};
