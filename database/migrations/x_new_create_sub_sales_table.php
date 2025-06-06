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
        Schema::create('sub_sales', function (Blueprint $table) {
            $table->id();
             $table->integer('corp_app_id');
             $table->integer('subsales_id');
             $table->unsignedBigInteger('subsales_idpel');
           $table->foreign('subsales_idpel')->references('inv_idpel')->on('invoices')->onDelete('cascade');
            //  $table->integer('subsales_idpel');
             $table->integer('subsales_fee');
             $table->integer('akun_status');
            $table->string('subsales_nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sales');
    }
};
