<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Inertia\Inertia;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Reservasi::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'orderId' => 'ORD' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                    'userName' => $order->nama,
                    'method' => $order->pembayaran, // Assuming 'pembayaran' stores method like 'Transfer', 'COD'
                    'total' => $order->total ?? 0,
                    'status' => $order->status_pembayaran ?? 'Pending',
                    'date' => $order->created_at->format('Y-m-d'),
                    'proof' => $order->bukti_pembayaran ? asset('storage/' . $order->bukti_pembayaran) : '/images/sample-proof.jpg', // Adjust path as needed
                ];
            });

        return Inertia::render('admin/Payments', [
            'payments' => $payments,
        ]);
    }

    public function verify($id)
    {
        $order = Reservasi::findOrFail($id);
        $order->status_pembayaran = 'Lunas'; // Or 'Verified' depending on your enum/string
        $order->save();

        return back()->with('success', 'Payment verified successfully!');
    }

    public function reject($id)
    {
        $order = Reservasi::findOrFail($id);
        $order->status_pembayaran = 'Ditolak'; // Or 'Rejected'
        $order->save();

        return back()->with('success', 'Payment rejected.');
    }
}
