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
        Schema::create('setting_whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('wa_nama')->nullable();
            $table->string('wa_site')->nullable();
            $table->string('wa_key')->nullable();
            $table->string('wa_url')->nullable();
            $table->string('wa_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_whatsapps');
    }
};
