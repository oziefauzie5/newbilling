<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Router\Router;
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
        Schema::create('pakets', function (Blueprint $table) {
            $table->id('paket_id');
            // $table->foreignIdFor(Router::class)->constrained()->on('routers')->onDelete('restrict');
            $table->string('paket_nama')->nullable();
            $table->string('paket_limitasi')->nullable();
            $table->string('paket_shared')->nullable();
            $table->string('paket_masa_aktif')->nullable();
            $table->integer('paket_harga');
            $table->integer('paket_komisi');
            $table->string('paket_lokal')->nullable();
            $table->string('paket_remote_address')->nullable();
            $table->string('paket_layanan')->nullable();
            $table->string('paket_mode')->nullable();
            $table->string('paket_warna')->nullable();
            $table->string('paket_status')->nullable();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
