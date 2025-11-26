<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB; 

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = \App\Models\Reservasi::orderBy('created_at', 'desc')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'customer' => $order->nama,
                'address' => $order->alamat,
                'service' => $order->layanan,
                'stuff' => is_array($order->barang) ? implode(', ', $order->barang) : $order->barang,
                'message' => $order->pesan,
                'orderStatus' => $order->status_pesanan ?? $order->status,
                'paymentStatus' => $order->status_pembayaran ?? $order->payment_status,
                'pricePerKg' => $order->harga_per_kg ?? 3000,
                'weight' => $order->berat ?? 0,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
            ];
        });

        return inertia('admin/Orders', [
            'orders' => $orders,
        ]);
    }

    public function getData()
    {
        $orders = Reservasi::orderBy('created_at', 'desc')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'customer' => $order->nama,
                'address' => $order->alamat,
                'service' => $order->layanan,
                'stuff' => is_array($order->barang) ? implode(', ', $order->barang) : $order->barang,
                'message' => $order->pesan,
                'orderStatus' => $order->status_pesanan ?? $order->status,
                'paymentStatus' => $order->status_pembayaran ?? $order->payment_status,
                'pricePerKg' => $order->harga_per_kg ?? 3000,
                'weight' => $order->berat ?? 0,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
            ];  
        });

        return response()->json([
            'orders' => $orders
        ]);
    }


    public function update(Request $request, $id)
    {
        $order = Reservasi::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string',
            'status_pembayaran' => 'required|string',
            'berat' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'nullable|integer|min:0',
        ]);

        $validated['total'] = $validated['berat'] * $validated['harga_per_kg'];

        $order->update($validated);

        return back()->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Reservasi::findOrFail($id)->delete();

        if (Reservasi::count() === 0) {
            DB::statement('ALTER TABLE reservasis AUTO_INCREMENT = 1');
        }
        
        return back()->with('success', 'Order berhasil dihapus.');
    }
}