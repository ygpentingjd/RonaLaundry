<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Ubah tipe kolom status dan payment_status menjadi enum
            $table->enum('status', [
                'Menunggu Penjemputan',
                'Sedang Diproses',
                'Selesai Diproses',
                'Batal'
            ])->default('Menunggu Penjemputan')->change();

            $table->enum('payment_status', [
                'Pending',
                'Verified',
                'Rejected'
            ])->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Balik ke string biasa kalau rollback
            $table->string('status')->default('Menunggu Penjemputan')->change();
            $table->string('payment_status')->default('Pending')->change();
        });
    }
};