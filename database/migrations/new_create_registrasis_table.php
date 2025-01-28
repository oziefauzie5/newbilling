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
            $table->integer('reg_harga')->nullable();
            $table->integer('reg_kode_unik')->nullable();
            $table->integer('reg_deposit')->nullable();
            $table->integer('reg_ppn')->nullable();
            $table->integer('reg_dana_kas')->nullable();
            $table->integer('reg_dana_kerjasama')->nullable();
            $table->integer('reg_fee')->nullable();
            $table->string('reg_username')->nullable();
            $table->string('reg_password')->nullable();
            $table->string('reg_tgl_pasang')->nullable();
            $table->string('reg_tgl_tagih')->nullable();
            $table->string('reg_tgl_jatuh_tempo')->nullable();
            $table->string('reg_tgl_deaktivasi')->nullable();
            $table->string('reg_out_odp')->nullable();
            $table->string('reg_in_ont')->nullable();
            $table->string('reg_los_opm')->nullable();
            $table->string('reg_nama_barang')->nullable(); #baru
            $table->integer('reg_site')->nullable(); #ganti wilayah jadi site
            $table->integer('reg_pop')->nullable(); #baru
            $table->integer('reg_router')->nullable();
            $table->integer('reg_olt')->nullable(); #baru
            $table->integer('reg_odc')->nullable(); #baru
            $table->integer('reg_odp')->nullable(); #baru
            $table->integer('reg_slot_odp')->nullable(); #ganti slot onu
            $table->string('reg_mac_olt')->nullable(); #baru
            $table->string('reg_onuid')->nullable(); #baru
            $table->string('reg_mrek')->nullable();
            $table->string('reg_mac')->nullable();
            $table->string('reg_sn')->nullable();
            $table->string('reg_skb')->nullable();
            $table->string('reg_ip_address')->nullable();
            $table->string('reg_catatan')->nullable();
            $table->string('reg_status')->nullable();
            $table->string('reg_progres')->nullable();
            $table->string('reg_teknisi_team')->nullable();
            $table->integer('reg_inv_control')->nullable();
            $table->string('reg_img')->nullable();
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
