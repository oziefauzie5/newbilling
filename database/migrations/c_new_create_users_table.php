<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Site::class)->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('ktp')->nullable();
            $table->string('hp')->nullable();
            $table->string('photo')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->string('status_user')->nullable();
            $table->string('tgl_gabung')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
