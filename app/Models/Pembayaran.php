<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; // pakai nama table kamu

    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'reservasi_id',
        'user_id',
        'payment_method',
        'total_amount',
        'status',
        'bukti_pembayaran',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function details()
{
    return $this->hasMany(PembayaranDetail::class, 'pembayaran_id', 'id_pembayaran');
}

}
