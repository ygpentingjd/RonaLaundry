<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'whatsapp',
        'layanan',
        'barang',
        'alamat',
        'pembayaran',
        'tanggal',
        'berat',
        'harga_per_kg',
        'total',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'barang' => 'array',
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}