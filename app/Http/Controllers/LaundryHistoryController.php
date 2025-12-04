<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LaundryHistoryController extends Controller
{
    public function index()
    {
        $orders = Reservasi::where('user_id', Auth::id())
            ->whereIn('status', ['Selesai Diproses', 'Siap Diambil', 'Siap Diantar', 'Selesai', 'Batal'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->nama,
                    'service' => $order->layanan,
                    'address' => $order->alamat,
                    'pickupDate' => $order->tanggal_kembali ? date('l, d F Y', strtotime($order->tanggal_kembali)) : '-',
                    'pricePerKg' => $order->harga_per_kg ?? 0,
                    'weight' => $order->berat ?? 0,
                    'total' => $order->total ?? 0,
                    'items' => $order->order_details, 
                    'paymentStatus' => $order->payment_status,
                    'status' => $order->status,
                    'notes' => $order->pesan,
                    'date' => $order->created_at->format('d F Y | H:i') . ' WIB',
                ];
            });

        return Inertia::render('LaundryHistory', [
            'historyOrders' => $orders
        ]);
    }
}
