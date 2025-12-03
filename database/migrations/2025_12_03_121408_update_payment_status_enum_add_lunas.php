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
        // Update reservasis table
        Schema::table('reservasis', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN payment_status ENUM('Pending', 'Verified', 'Rejected', 'Lunas') DEFAULT 'Pending'");
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN status_pembayaran ENUM('Pending', 'Verified', 'Rejected', 'Lunas') DEFAULT 'Pending'");
        });

        // Update pembayaran table
        Schema::table('pembayaran', function (Blueprint $table) {
            DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('Pending', 'Verified', 'Rejected', 'Lunas') DEFAULT 'Pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN payment_status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending'");
            DB::statement("ALTER TABLE reservasis MODIFY COLUMN status_pembayaran ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending'");
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending'");
        });
    }
};
