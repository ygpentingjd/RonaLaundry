<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE reservasis MODIFY COLUMN status ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Batal', 'Selesai') DEFAULT 'Menunggu Penjemputan'");
        DB::statement("ALTER TABLE reservasis MODIFY COLUMN status_pesanan ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Batal', 'Selesai') DEFAULT 'Menunggu Penjemputan'");
    }

    public function down()
    {
        // Reverting is tricky with enums, usually we don't remove values unless necessary.
        // But for completeness:
        DB::statement("ALTER TABLE reservasis MODIFY COLUMN status ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Batal') DEFAULT 'Menunggu Penjemputan'");
        DB::statement("ALTER TABLE reservasis MODIFY COLUMN status_pesanan ENUM('Menunggu Penjemputan', 'Sedang Diproses', 'Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Batal') DEFAULT 'Menunggu Penjemputan'");
    }
};
