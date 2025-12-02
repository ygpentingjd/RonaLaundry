<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->text('catatan_lokasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap',
                'nomor_telepon',
                'alamat_lengkap',
                'catatan_lokasi',
            ]);
        });
    }
};