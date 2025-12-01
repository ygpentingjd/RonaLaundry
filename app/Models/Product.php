<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'estimasi_waktu',
        'harga_reguler',
        'harga_kilat',
        'deskripsi',
        'image',
    ];
}