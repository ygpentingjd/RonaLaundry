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
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'total' => $order->total,  // ⬅️ DITAMBAH
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
<<<<<<< HEAD
                'orderStatus' => $order->status,
                'paymentStatus' => $order->status_pembayaran ?? $order->payment_status,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'total' => $order->total,  // ⬅️ DITAMBAH
=======
                'message' => $order->pesan,
                'orderStatus' => $order->status_pesanan ?? $order->status,
                'paymentStatus' => $order->status_pembayaran ?? $order->payment_status,
                'pricePerKg' => $order->harga_per_kg ?? 3000,
                'weight' => $order->berat ?? 0,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
>>>>>>> bdb195f75d5f9ae047eb171293ab64aaa5c52af1
                'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
            ];  
        });

        return response()->json([
            'orders' => $orders
        ]);
    }

<<<<<<< HEAD
=======

>>>>>>> bdb195f75d5f9ae047eb171293ab64aaa5c52af1
    public function update(Request $request, $id)
    {
        $order = Reservasi::findOrFail($id);

        $validated = $request->validate([
            'status_pesanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'total' => 'required|integer|min:0',
        ]);

<<<<<<< HEAD
        $order->update([
            'status' => $validated['status'],
            'payment_status' => $validated['status_pembayaran'],
            'total' => $validated['total'],
        ]);
=======
        $updateData = [
            'status' => $validated['status_pesanan'],
            'payment_status' => $validated['status_pembayaran'],
            'berat' => $validated['berat'],
            'harga_per_kg' => $validated['harga_per_kg'],
            'total' => ($validated['berat'] ?? 0) * ($validated['harga_per_kg'] ?? 0),
        ];

        $order->update($updateData);
>>>>>>> bdb195f75d5f9ae047eb171293ab64aaa5c52af1

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
