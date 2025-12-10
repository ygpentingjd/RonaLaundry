<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Reservasi;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Total Transaksi
        $totalTransaksi = Reservasi::where('user_id', $user->id)->count();

        // 2. Status Pelanggan
        $statusPelanggan = 'Baru';
        if ($totalTransaksi > 10) {
            $statusPelanggan = 'Langganan';
        } elseif ($totalTransaksi > 5) {
            $statusPelanggan = 'Tetap';
        }

        // 3. Layanan Favorit (Most Frequent)
        $layananFavorit = Reservasi::where('user_id', $user->id)
            ->select('layanan', \DB::raw('count(*) as total'))
            ->groupBy('layanan')
            ->orderByDesc('total')
            ->value('layanan') ?? '-';

        // 4. Riwayat Terakhir
        $lastOrder = Reservasi::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $lastOrderData = null;
        if ($lastOrder) {
            $lastOrderData = [
                'layanan' => $lastOrder->layanan,
                'berat' => $lastOrder->berat,
                'time_ago' => $lastOrder->created_at->diffForHumans(),
            ];
        }

        return Inertia::render('Profile', [
            'user' => $user,
            'stats' => [
                'total_transaksi' => $totalTransaksi,
                'status_pelanggan' => $statusPelanggan,
                'layanan_favorit' => $layananFavorit,
            ],
            'lastOrder' => $lastOrderData,
        ]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:255',
            'catatan_lokasi' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
