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
    Schema::create('pembayaran', function (Blueprint $table) {
        $table->id('id_pembayaran');
        
        $table->unsignedBigInteger('reservasi_id');
        $table->unsignedBigInteger('user_id');

        $table->string('payment_method', 100);
        $table->integer('total_amount');
        $table->enum('status', ['Pending', 'Verified', 'Rejected'])->default('Pending');

        $table->timestamps();

        // Foreign keys
        $table->foreign('reservasi_id')
              ->references('id')
              ->on('reservasis')   // ← diperbaiki (pakai 'reservasis')
              ->onDelete('cascade');

        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}


public function down(): void
{
    Schema::dropIfExists('pembayaran');
}

};
