<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservasi::with(['user', 'infoPembayaran'])
            ->orderBy('created_at', 'desc');

        // Apply Filters
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // Calculate Totals
        $totalAllRevenue = Reservasi::where('payment_status', 'Lunas')->sum('total');
        
        // Calculate Filtered Revenue (based on current query filters + Lunas status)
        // We clone the query to avoid modifying the main pagination query
        $filteredRevenue = (clone $query)->where('payment_status', 'Lunas')->sum('total');

        $payments = $query->paginate(10)
            ->withQueryString()
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'orderId' => 'ORD' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                    'userName' => $order->nama,
                    'method' => $order->pembayaran,
                    'total' => $order->total ?? 0,
                    'status' => $order->payment_status ?? 'Pending',
                    'date' => $order->created_at->format('Y-m-d'),
                    'proof' => $order->infoPembayaran && $order->infoPembayaran->bukti_pembayaran 
                        ? asset('storage/' . $order->infoPembayaran->bukti_pembayaran) 
                        : '/images/sample-proof.jpg',
                ];
            });

        // Get Min Year for Filter
        $minYear = Reservasi::min(DB::raw('YEAR(created_at)')) ?? date('Y');

        return Inertia::render('admin/Payments', [
            'payments' => $payments,
            'totalAllRevenue' => $totalAllRevenue,
            'filteredRevenue' => $filteredRevenue,
            'filters' => $request->only(['month', 'year']),
            'minYear' => (int) $minYear,
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
