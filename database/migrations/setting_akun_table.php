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
        Schema::create('setting_akuns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->enum('akun_type', ['TUNAI', 'TRANSFER','TRIPAY']);
            $table->string('akun_nama')->nullable();
            $table->string('akun_rekening')->nullable();
            $table->string('akun_pemilik')->nullable();
            $table->string('akun_status')->nullable();
            $table->string('akun_kategori')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_akuns');
    }
};
