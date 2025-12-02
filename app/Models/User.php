<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

     /**
     * Kolom yang bisa diisi mass-assignment
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_lengkap',
        'nomor_telepon',
        'alamat_lengkap',
        'catatan_lokasi',
        'role',
        'last_activity',
        'status',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut ke tipe data tertentu
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOnline()
    {
        return $this->last_activity &&
           $this->last_activity > now()->subMinutes(5);

    }

}