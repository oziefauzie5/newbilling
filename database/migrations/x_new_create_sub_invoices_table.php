<?php

use App\Models\Aplikasi\Corporate;
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
        Schema::create('sub_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subinvoice_id');
           $table->foreign('subinvoice_id')->references('inv_id')->on('invoices')->onDelete('cascade');
            $table->foreignIdFor(Corporate::class)->constrained()->cascadeOnDelete();
            // $table->string('subinvoice_id')->nullable();
            $table->string('subinvoice_deskripsi')->nullable();
            $table->integer('subinvoice_qty')->nullable();
            $table->string('subinvoice_harga')->nullable();
            $table->string('subinvoice_ppn')->nullable();
            $table->string('subinvoice_bph_uso')->nullable();
            $table->string('subinvoice_total')->nullable();
            $table->string('subinvoice_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_invoices');
    }
};
