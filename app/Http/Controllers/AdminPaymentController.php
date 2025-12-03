<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Inertia\Inertia;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Reservasi::with(['user', 'infoPembayaran'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'orderId' => 'ORD' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                    'userName' => $order->nama,
                    'method' => $order->pembayaran, // Assuming 'pembayaran' stores method like 'Transfer', 'COD'
                    'total' => $order->total ?? 0,
                    'status' => $order->payment_status ?? 'Pending',
                    'date' => $order->created_at->format('Y-m-d'),
                    'proof' => $order->infoPembayaran && $order->infoPembayaran->bukti_pembayaran 
                        ? asset('storage/' . $order->infoPembayaran->bukti_pembayaran) 
                        : '/images/sample-proof.jpg',
                ];
            });

        return Inertia::render('admin/Payments', [
            'payments' => $payments,
        ]);
    }

    public function verify($id)
    {
        $order = Reservasi::findOrFail($id);
        $order->payment_status = 'Lunas'; 
        $order->status_pembayaran = 'Lunas'; // Sync duplicate column
        $order->save();

        // Also update Pembayaran record if exists
        if ($order->infoPembayaran) {
            $order->infoPembayaran->update(['status' => 'Lunas']);
        }

        return back()->with('success', 'Payment verified successfully!');
    }

    public function reject($id)
    {
        $order = Reservasi::findOrFail($id);
        $order->payment_status = 'Rejected';
        $order->status_pembayaran = 'Rejected'; // Sync duplicate column
        $order->save();

        // Also update Pembayaran record if exists
        if ($order->infoPembayaran) {
            $order->infoPembayaran->update(['status' => 'Rejected']);
        }

        return back()->with('success', 'Payment rejected.');
    }
}
