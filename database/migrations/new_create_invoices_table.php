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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('inv_id');
            $table->string('inv_status')->nullable();
            $table->string('inv_idpel')->nullable();
            $table->string('inv_nolayanan')->nullable();
            $table->string('inv_nama')->nullable();
            $table->string('inv_jenis_tagihan')->nullable();
            $table->string('inv_profile')->nullable();
            $table->string('inv_mitra')->nullable();
            $table->string('inv_kategori')->nullable();
            $table->string('inv_tgl_tagih')->nullable();
            $table->string('inv_tgl_jatuh_tempo')->nullable();
            $table->string('inv_tgl_isolir')->nullable();
            $table->string('inv_tgl_bayar')->nullable();
            $table->string('inv_periode')->nullable();
            $table->string('inv_diskon')->nullable();
            $table->string('inv_total')->nullable();
            $table->string('inv_reference')->nullable();
            $table->string('inv_payment_method')->nullable();
            $table->string('inv_payment_method_code')->nullable();
            $table->string('inv_total_amount')->nullable();
            $table->string('inv_fee_merchant')->nullable();
            $table->string('inv_fee_customer')->nullable();
            $table->string('inv_total_fee')->nullable();
            $table->string('inv_amount_received')->nullable();
            $table->string('inv_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
