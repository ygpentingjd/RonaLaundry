<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            // Using raw statement to ensure ENUM is updated correctly
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN status ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Batal') DEFAULT 'Menunggu Penjemputan'");
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN status ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Batal') DEFAULT 'Menunggu Penjemputan'");
        });
    }
};
