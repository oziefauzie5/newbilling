<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Teknisi\Data_pop;
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
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Data_pop::class)->constrained()->on('data_pops')->onDelete('restrict');
            $table->string('router_nama')->nullable();
            $table->string('router_ip')->nullable();
            $table->string('router_dns')->nullable();
            $table->string('router_port_api')->nullable();
            $table->string('router_port_remote')->nullable();
            $table->string('router_username')->nullable();
            $table->string('router_password')->nullable();
            $table->string('router_status')->nullable();
            $table->timestamps();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routers');
    }
};
