<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id_product' => 1,
                'nama_barang' => 'Pakaian',
                'satuan' => 'kg',
                'estimasi_waktu' => '2',
                'harga_reguler' => 4000,
                'harga_kilat' => 6000,
                'deskripsi' => 'Sudah di setrika',
                'image' => 'products/fLITgPIflaDjBzlpksbgWDRkgUoFr4J3FD8msKCN.png',
                'created_at' => '2025-12-03 01:22:30',
                'updated_at' => '2025-12-07 02:08:49',
            ],
            [
                'id_product' => 2,
                'nama_barang' => 'Boneka',
                'satuan' => 'pcs',
                'estimasi_waktu' => '2',
                'harga_reguler' => 3000,
                'harga_kilat' => 5000,
                'deskripsi' => 'Boneka kembali bersih seperti baru',
                'image' => 'products/UN0NLdmtEQgVjf1eDJkSnetNvzcx0Hy3aHtWWcOE.png',
                'created_at' => '2025-12-03 01:41:05',
                'updated_at' => '2025-12-03 01:41:05',
            ],
            [
                'id_product' => 3,
                'nama_barang' => 'Selimut',
                'satuan' => 'meter',
                'estimasi_waktu' => '2',
                'harga_reguler' => 3000,
                'harga_kilat' => 5000,
                'deskripsi' => 'Selimut kembali bersih dan harum wangi',
                'image' => 'products/BZO1FM8YC8lqYJt09JStMbCtwZSq93pDyQg28umt.png',
                'created_at' => '2025-12-03 01:42:03',
                'updated_at' => '2025-12-03 01:42:03',
            ],
            [
                'id_product' => 4,
                'nama_barang' => 'Handuk',
                'satuan' => 'pcs',
                'estimasi_waktu' => '2',
                'harga_reguler' => 3000,
                'harga_kilat' => 5000,
                'deskripsi' => 'Handuk wangi',
                'image' => 'products/pzkBniuvvFSF6cbhrLPPttOgZ3bNEYJGe2bth1Yk.png',
                'created_at' => '2025-12-03 01:42:44',
                'updated_at' => '2025-12-03 01:42:44',
            ],
            [
                'id_product' => 5,
                'nama_barang' => 'Alat Ibadah',
                'satuan' => 'pcs',
                'estimasi_waktu' => '2',
                'harga_reguler' => 3000,
                'harga_kilat' => 5000,
                'deskripsi' => 'Mukena & Sajadah bersih, wangi dan nyaman dipakai kembali',
                'image' => 'products/Hx9LRxUoYDdghMuAucUKhMLFmgMjBxqCj0UvfuCl.png',
                'created_at' => '2025-12-03 01:44:20',
                'updated_at' => '2025-12-03 01:44:20',
            ],
            [
                'id_product' => 6,
                'nama_barang' => 'Sepatu',
                'satuan' => 'pcs',
                'estimasi_waktu' => '3',
                'harga_reguler' => 10000,
                'harga_kilat' => 20000,
                'deskripsi' => 'Sepatu kembali bersih, harum, dan seperti baru',
                'image' => 'products/BjsiKASgOqpJLfzrGkFelRJsZYKhZz3p7kfLODLA.png',
                'created_at' => '2025-12-03 01:45:18',
                'updated_at' => '2025-12-03 01:45:18',
            ],
            [
                'id_product' => 7,
                'nama_barang' => 'Sprei',
                'satuan' => 'meter',
                'estimasi_waktu' => '3',
                'harga_reguler' => 3000,
                'harga_kilat' => 5000,
                'deskripsi' => 'Sprei menjadi harum, lembut, dan siap dipakai kembali',
                'image' => 'products/COiES321IJH2V8k8ehbVPVd3oneDZ5BMMfCCPfYE.png',
                'created_at' => '2025-12-03 01:46:13',
                'updated_at' => '2025-12-03 01:46:13',
            ],
        ];

        foreach ($products as $product) {
            DB::table('products')->updateOrInsert(
                ['id_product' => $product['id_product']],
                $product
            );
        }
    }
}
