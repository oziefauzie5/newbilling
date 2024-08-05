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
        Schema::create('titikvhcs', function (Blueprint $table) {
            $table->id();
            $table->string('titik_id')->nullable();
            $table->string('titik_nama')->nullable();
            $table->string('titik_nama_titik')->nullable();
            $table->string('titik_alamat')->nullable();
            $table->string('titik_pen_jawab_id')->nullable();
            $table->string('titik_pen_jawab_hp')->nullable();
            $table->string('titik_maps')->nullable();
            $table->string('titik_ip')->nullable();
            $table->string('titik_penangguang_jawab')->nullable();
            $table->string('titik_username')->nullable();
            $table->string('titik_password')->nullable();
            $table->string('titik_wilayah')->nullable();
            $table->string('titik_mac')->nullable();
            $table->string('titik_sn')->nullable();
            $table->string('titik_mrek')->nullable();
            $table->string('titik_tgl_pasang')->nullable();
            $table->string('titik_teknisi_team')->nullable();
            $table->string('titik_kode_pactcore')->nullable();
            $table->string('titik_kode_adaptor')->nullable();
            $table->string('titik_kode_dropcore')->nullable();
            $table->string('titik_kode_ont')->nullable();
            $table->string('titik_fat')->nullable();
            $table->string('titik_fat_opm')->nullable();
            $table->string('titik_home_opm')->nullable();
            $table->string('titik_los_opm')->nullable();
            $table->string('titik_router')->nullable();
            $table->string('titik_before')->nullable();
            $table->string('titik_after')->nullable();
            $table->string('titik_penggunaan_dropcore')->nullable();
            $table->string('titik_catatan')->nullable();
            $table->string('titik_progres')->nullable();
            $table->string('titik_stt_perangkat')->nullable();
            $table->string('titik_slotonu')->nullable();
            $table->string('titik_img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titikvhcs');
    }
};
