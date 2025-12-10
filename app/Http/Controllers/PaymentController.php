<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Simpan file ke storage/app/public/bukti_pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Fetch reservasi to get the payment method
        $reservasi = Reservasi::findOrFail($id);

        // Path disimpan ke database
        // contoh: bukti_pembayaran/aks82h1hj.jpg
        Pembayaran::updateOrCreate(
            ['reservasi_id' => $id],
            [
                'user_id' => Auth::id(),
                'payment_method' => $reservasi->pembayaran,
                'total_amount' => 0,
                'status' => 'Pending',
                'bukti_pembayaran' => $path
            ]
        );

        // Update status reservasi
        Reservasi::where('id', $id)->update([
            'payment_status' => 'Pending'
        ]);

        return redirect()->route('mylaundry')->with('success', 'Bukti pembayaran berhasil diupload!');
    }
}
