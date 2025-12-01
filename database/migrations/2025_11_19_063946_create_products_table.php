<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id_product'); // primary key

            $table->string('nama_barang', 255);
            $table->enum('satuan', ['kg', 'pcs', 'meter']);
            $table->string('estimasi_waktu', 50);
            $table->integer('harga_reguler');
            $table->integer('harga_kilat');

            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};