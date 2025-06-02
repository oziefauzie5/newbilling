<?php

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
        Schema::create('router_subs', function (Blueprint $table) {
            $table->id('router_sub_id');
            $table->unsignedBigInteger('router_id');
            $table->foreign('router_id')->references('id')->on('routers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_subs');
    }
};
