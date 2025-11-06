<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('whatsapp');
            $table->string('layanan');
            $table->json('barang');
            $table->text('alamat');
            $table->string('pembayaran');
            $table->date('tanggal');
            $table->float('berat')->nullable();
            $table->integer('harga_per_kg')->nullable();
            $table->integer('total')->nullable();
            $table->string('status')->default('Menunggu Penjemputan');
            $table->string('payment_status')->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};