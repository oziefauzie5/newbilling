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
        Schema::create('setting_tripays', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->string('tripay_merchant')->nullable();
            $table->string('tripay_url_callback')->nullable();
            $table->string('tripay_kode_merchant')->nullable();
            $table->string('tripay_apikey')->nullable();
            $table->string('tripay_privatekey')->nullable();
            $table->string('tripay_admin_topup')->nullable();
            $table->string('tripay_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_tripays');
    }
};
