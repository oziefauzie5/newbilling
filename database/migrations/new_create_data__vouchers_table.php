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
        Schema::create('data__vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('vhc_id')->nullable();
            $table->string('vhc_pesananid')->nullable();
            $table->string('vhc_username')->nullable();
            $table->string('vhc_password')->nullable();
            $table->string('vhc_paket')->nullable();
            $table->string('vhc_site')->nullable();
            $table->string('vhc_router')->nullable();
            $table->string('vhc_mitra')->nullable();
            $table->string('vhc_outlet')->nullable();
            $table->string('vhc_hpp')->nullable();
            $table->string('vhc_komisi')->nullable();
            $table->string('vhc_hjk')->nullable();
            $table->string('vhc_tgl_cetak')->nullable();
            $table->string('vhc_tgl_jual')->nullable();
            $table->string('vhc_tgl_hapus')->nullable();
            $table->string('vhc_exp')->nullable();
            $table->string('vhc_durasi_pakai')->nullable();
            $table->string('vhc_kuota')->nullable();
            $table->string('vhc_admin')->nullable();
            $table->string('vhc_status')->nullable();
            $table->string('vhc_status_pakai')->nullable();
            $table->string('vhc_mac')->nullable();
            $table->string('vhc_script')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__vouchers');
    }
};
