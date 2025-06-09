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
        Schema::create('data__tikets', function (Blueprint $table) {
            $table->id('tiket_id');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Data_Site::class)->constrained()->on('data__sites')->onDelete('restrict');
            $table->unsignedBigInteger('tiket_idpel');
            $table->foreign('tiket_idpel')->references('reg_idpel')->on('registrasis')->onDelete('cascade');
            $table->string('tiket_idbarang_keluar')->nullable();
            $table->string('tiket_pending')->nullable();
            $table->string('tiket_pembuat')->nullable();
            $table->string('tiket_type')->nullable();
            $table->string('tiket_jenis')->nullable();
            $table->string('tiket_nama')->nullable();
            $table->string('tiket_jadwal_kunjungan')->nullable();
            $table->string('tiket_waktu_mulai')->nullable();
            $table->string('tiket_waktu_selesai')->nullable();
            $table->string('tiket_foto')->nullable();
            $table->text('tiket_deskripsi')->nullable();
            $table->text('tiket_kendala')->nullable();
            $table->text('tiket_tindakan')->nullable();
            $table->string('tiket_keterangan')->nullable();
            $table->string('tiket_teknisi1')->nullable();
            $table->string('tiket_teknisi2')->nullable();
            $table->string('tiket_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data__tikets');
    }
};
