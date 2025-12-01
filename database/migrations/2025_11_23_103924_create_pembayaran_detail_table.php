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
    Schema::create('pembayaran_detail', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('pembayaran_id');

        $table->string('item_name', 255);
        $table->float('quantity'); // berdasarkan screenshot kamu
        $table->string('unit', 50);
        $table->integer('price');
        $table->integer('subtotal');

        // Tambahkan foreign key
        $table->foreign('pembayaran_id')->references('id_pembayaran')->on('pembayaran')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('pembayaran_detail');
}

};
