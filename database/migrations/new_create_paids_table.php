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
        Schema::create('paids', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unpaid')->nullable();
            $table->integer('idpel_unpaid')->nullable();
            $table->string('reference')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_code')->nullable();
            $table->integer('total_amount')->nullable();
            $table->integer('fee_merchant')->nullable();
            $table->integer('fee_customer')->nullable();
            $table->integer('total_fee')->nullable();
            $table->integer('amount_received')->nullable();
            $table->integer('is_closed_payment')->nullable();
            $table->string('status')->nullable();
            $table->string('paid_at')->nullable();
            $table->string('admin')->nullable();
            $table->string('note')->nullable();
            $table->integer('akun')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paids');
    }
};
