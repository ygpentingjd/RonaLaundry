<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Reservasi;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Stats
        $totalProducts = Product::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalOrders = Reservasi::count();
        $pendingVerification = Reservasi::where('status_pembayaran', 'Pending')->count();

        $stats = [
            ['title' => 'Total Products', 'value' => $totalProducts, 'icon' => 'shopping_bag'],
            ['title' => 'Total Users', 'value' => $totalUsers, 'icon' => 'group'],
            ['title' => 'Total Orders', 'value' => $totalOrders, 'icon' => 'assignment'],
            ['title' => 'Pending Verification', 'value' => $pendingVerification, 'icon' => 'hourglass_empty'],
        ];

        // 2. Chart Data (Monthly Income for current year)
        $monthlyIncome = Reservasi::select(
            DB::raw('SUM(total) as total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->whereYear('created_at', date('Y'))
        ->where(function ($q) {
            $q->where('payment_status', 'Lunas')
              ->orWhere('status_pembayaran', 'Lunas');
        })
        ->whereIn('status', ['Selesai', 'Siap Diantar'])
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyIncome[$i] ?? 0;
        }

        $chartSeries = [
            ['name' => 'Pemasukan (Rp)', 'data' => $chartData]
        ];

        // 3. Recent Orders
        $recentOrders = Reservasi::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'name' => $order->nama,
                    'service' => $order->layanan,
                    'status' => $order->status,
                    'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
                ];
            });

        // 4. Recent Users
        $recentUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->nama_lengkap ?? $user->username,
                    'email' => $user->email,
                ];
            });

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
            'chartSeries' => $chartSeries,
            'recentOrders' => $recentOrders,
            'recentUsers' => $recentUsers,
        ]);
    }
}
