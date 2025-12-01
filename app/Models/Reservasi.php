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
        'tanggal_kembali',
        'metode_pengantaran',
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

    public function infoPembayaran()
    {
         return $this->hasOne(Pembayaran::class, 'reservasi_id');
    }
}
