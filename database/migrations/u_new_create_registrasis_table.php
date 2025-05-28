<?php

use App\Models\Aplikasi\Corporate;
use App\Models\Aplikasi\Data_Site;
use App\Models\Gudang\Data_BarangKeluar;
use App\Models\PSB\FtthFee;
use App\Models\PSB\FtthInstalasi;
use App\Models\Router\Paket;
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
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            
            // $table->unsignedBigInteger('reg_skb');
            // $table->foreign('reg_skb')->references('bk_id')->on('data__barang_keluars')->onDelete('restrict');
            $table->unsignedBigInteger('reg_idpel');
            $table->foreign('reg_idpel')->references('id')->on('input_data')->onDelete('restrict');
            $table->unsignedBigInteger('reg_profile');
            $table->foreign('reg_profile')->references('paket_id')->on('pakets')->onDelete('restrict');
            $table->string('reg_nolayanan')->nullable();
            $table->string('reg_layanan')->nullable();
            $table->string('reg_jenis_tagihan')->nullable();
            $table->integer('reg_harga')->nullable();
            $table->integer('reg_ppn')->nullable();
            $table->integer('reg_bph_uso')->nullable();
            $table->integer('reg_kode_unik')->nullable();
            $table->integer('reg_inv_control')->nullable();
            $table->string('reg_tgl_pasang')->nullable();
            $table->string('reg_tgl_tagih')->nullable();
            $table->string('reg_tgl_jatuh_tempo')->nullable();
            $table->string('reg_tgl_deaktivasi')->nullable();
            $table->string('reg_username')->nullable();
            $table->string('reg_password')->nullable();
            
            $table->string('reg_img')->nullable();
            $table->string('reg_catatan')->nullable();
            $table->string('reg_status')->nullable();
            $table->string('reg_progres')->nullable();
            
            
            
            ##--Buat Table baru, Table Insatalasi pelanggan  --ftth_fee
            // $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(FtthFee::class)->constrained()->on('registrsais')->onDelete('restrict');
            // $table->integer('reg_mitra')->nullable();
            // $table->integer('reg_fee')->nullable();
            
            // $table->string('reg_profile')->nullable();
            // $table->string('reg_idpel')->nullable();
            
            // $table->integer('reg_dana_kas')->nullable();
            // $table->integer('reg_dana_kerjasama')->nullable();

            // $table->integer('reg_deposit')->nullable();
            


            
            // $table->string('reg_skb')->nullable();
            
            
            // $table->string('reg_pop')->nullable(); #baru
            // $table->string('reg_router')->nullable();
            // $table->string('reg_olt')->nullable(); #baru
            // $table->string('reg_odc')->nullable(); #baru
            // $table->string('reg_odp')->nullable(); #baru
            // $table->string('reg_onuid')->nullable(); #baru
            // $table->string('reg_ip_address')->nullable();
            // $table->string('reg_foto_odp')->nullable();
            // $table->string('reg_koordinat_odp')->nullable();
            // $table->string('reg_teknisi_team')->nullable();
            // $table->integer('reg_site')->nullable(); 
            
            
            ##--Buat Table baru, Table Insatalasi pelanggan  --ftth_instalasi
            // $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            // $table->foreignIdFor(FtthInstalasi::class)->constrained()->on('registrsais')->onDelete('restrict');
            // $table->unsignedBigInteger('reg_odp');
            // $table->foreign('reg_odp')->references('id')->on('data__odps')->onDelete('restrict');
            // $table->unsignedBigInteger('reg_teknisi');
            // $table->foreign('reg_teknisi')->references('teknisi_id')->on('teknisis')->onDelete('restrict');
            // $table->string('reg_out_odp')->nullable();
            // $table->string('reg_in_ont')->nullable();
            // $table->string('reg_los_opm')->nullable();
            // $table->integer('reg_slot_odp')->nullable(); #ganti slot onu



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
