<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PaymentAdminController extends Controller
{
    public function index()
    {
        return inertia('admin/Payments');
    }

    public function data()
    {
        $payments = Pembayaran::with('user')->get();

        return $payments->map(function ($p) {
            return [
                'id'        => $p->id_pembayaran,                                 // Vue pakai payment.id
                'orderId'   => $p->reservasi_id,                                  // Vue pakai payment.orderId
                'userName'  => $p->user->nama_lengkap ?? 'Unknown',               // Vue pakai payment.userName
                'method'    => $p->payment_method,                                // Vue pakai payment.method
                'total'     => intval($p->total_amount),                          // Vue pakai payment.total
                'status'    => ucfirst($p->status),                               // Harus: Pending / Verified / Rejected
                'proof'     => $p->bukti_pembayaran
                                 ? asset('storage/' . $p->bukti_pembayaran)
                                 : null,                                         // Vue pakai payment.proof
                'date'      => $p->created_at
                                 ? $p->created_at->format('d M Y H:i')           // Vue pakai payment.date
                                 : null,
            ];
        });
    }

    public function verify($id)
    {
        $p = Pembayaran::findOrFail($id);
        $p->status = 'Verified';          // Vue filter pakai 'Verified'
        $p->save();

        return response()->json(['message' => 'Payment verified']);
    }

    public function reject($id)
    {
        $p = Pembayaran::findOrFail($id);
        $p->status = 'Rejected';          // Vue filter pakai 'Rejected'
        $p->save();

        return response()->json(['message' => 'Payment rejected']);
    }
}
