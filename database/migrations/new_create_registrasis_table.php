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
        Schema::create('registrasis', function (Blueprint $table) {
            $table->id();
            $table->string('reg_idpel')->nullable();
            $table->string('reg_nolayanan')->nullable();
            $table->string('reg_layanan')->nullable();
            $table->string('reg_profile')->nullable();
            $table->string('reg_jenis_tagihan')->nullable();
            $table->string('reg_harga')->nullable();
            $table->string('reg_kode_unik')->nullable();
            $table->string('reg_deposit')->nullable();
            $table->string('reg_ppn')->nullable();
            $table->string('reg_dana_kas')->nullable();
            $table->string('reg_dana_kerjasama')->nullable();
            $table->string('reg_username')->nullable();
            $table->string('reg_password')->nullable();
            $table->string('reg_tgl_pasang')->nullable();
            $table->string('reg_tgl_tagih')->nullable();
            $table->string('reg_tgl_jatuh_tempo')->nullable();
            $table->string('reg_tgl_isolir')->nullable();
            $table->string('reg_wilayah')->nullable();
            $table->string('reg_fat')->nullable();
            $table->string('reg_fat_opm')->nullable();
            $table->string('reg_home_opm')->nullable();
            $table->string('reg_los_opm')->nullable();
            $table->string('reg_router')->nullable();
            $table->string('reg_mrek')->nullable();
            $table->string('reg_mac')->nullable();
            $table->string('reg_sn')->nullable();
            $table->string('reg_kode_pactcore')->nullable();
            $table->string('reg_kode_ont')->nullable();
            $table->string('reg_kode_adaptor')->nullable();
            $table->string('reg_kode_dropcore')->nullable();
            $table->string('reg_before')->nullable();
            $table->string('reg_after')->nullable();
            $table->string('reg_penggunaan_dropcore')->nullable();
            $table->string('reg_ip_address')->nullable();
            $table->string('reg_catatan')->nullable();
            $table->string('reg_status')->nullable();
            $table->string('reg_progres')->nullable();
            $table->string('reg_stt_perangkat')->nullable();
            $table->string('reg_slotonu')->nullable();
            $table->string('reg_teknisi_team')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasis');
    }
};
