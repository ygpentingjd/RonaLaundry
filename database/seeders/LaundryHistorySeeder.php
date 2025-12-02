<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaundryHistorySeeder extends Seeder
{
    public function run()
    {
        DB::table('reservasi')->insert([
            [
                'user_id' => 1,
                'nama' => 'Siti Aisyah',
                'whatsapp' => '081234567890',
                'layanan' => 'Cuci Kiloan Reguler',
                'barang' => json_encode(['notes' => 'Sudah disetrika dan dikemas rapi.']),
                'alamat' => 'Jl. Merpati No. 21, Bandung',
                'pembayaran' => 'Tunai',
                'tanggal' => '2025-12-19 11:00:00',
                'berat' => 5,
                'harga_per_kg' => 7000,
                'total' => 35000,
                'status' => 'Selesai Diproses',
                'payment_status' => 'lunas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 1,
                'nama' => 'Rizky Putra',
                'whatsapp' => '082345678901',
                'layanan' => 'Cuci Express',
                'barang' => json_encode(['notes' => 'Dijemput oleh kurir pada pukul 10:00 WIB.']),
                'alamat' => 'Jl. Cendrawasih No. 10, Cimahi',
                'pembayaran' => 'Transfer',
                'tanggal' => '2025-12-21 10:30:00',
                'berat' => 3,
                'harga_per_kg' => 9000,
                'total' => 27000,
                'status' => 'Siap Diambil',
                'payment_status' => 'lunas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}