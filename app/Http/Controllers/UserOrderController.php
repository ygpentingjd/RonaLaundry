<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = Reservasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tanggal' => $order->created_at->format('Y-m-d'),
                    'barang' => is_array($order->barang) ? implode(', ', $order->barang) : $order->barang,
                    'jumlah' => $order->order_details 
                        ? array_reduce($order->order_details, fn($carry, $item) => $carry + ($item['qty'] ?? 0), 0) 
                        : ($order->berat ?? 0),
                    'status' => $order->status,
                    'harga' => 'Rp ' . number_format($order->total ?? 0, 0, ',', '.'),
                ];
            });

        return Inertia::render('Order', [
            'orders' => $orders
        ]);
    }
}
