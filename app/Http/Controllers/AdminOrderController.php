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
                'paymentStatus' => $order->payment_status ?? $order->status_pembayaran,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'total' => $order->total,
                'parsedItems' => $order->order_details, // Pass saved details
                'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
                'whatsapp' => $order->whatsapp,
            ];
        });

        return inertia('admin/Orders', [
            'orders' => $orders,
            'products' => \App\Models\Product::all(),
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
                'paymentStatus' => $order->payment_status ?? $order->status_pembayaran,
                'pricePerKg' => $order->harga_per_kg ?? 0,
                'weight' => $order->berat ?? 0,
                'orderDate' => $order->tanggal,
                'returnDate' => $order->tanggal_kembali,
                'deliveryMethod' => $order->metode_pengantaran,
                'date' => $order->created_at->format('d M Y | H:i') . ' WIB',
                'whatsapp' => $order->whatsapp,
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
            'status_pesanan' => 'required|string',
            'status_pembayaran' => 'required|string',
            'berat' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'nullable|array', // Validate items array
        ]);

        $updateData = [
            'status' => $validated['status_pesanan'],
            'payment_status' => $validated['status_pembayaran'],
            'status_pesanan' => $validated['status_pesanan'],
            'status_pembayaran' => $validated['status_pembayaran'],
            'berat' => $validated['berat'] ?? 0,
            'harga_per_kg' => $validated['harga_per_kg'] ?? 0,
            'total' => $validated['total'],
            'order_details' => $validated['items'] ?? null, // Save items
            'barang' => isset($validated['items']) ? collect($validated['items'])->pluck('name')->toArray() : [], // Sync barang column
        ];

        $order->update($updateData);

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
