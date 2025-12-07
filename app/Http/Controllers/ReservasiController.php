<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    public function index()
{
    $reservasis = Reservasi::with('infoPembayaran')
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($r) {
            return [
                'id' => $r->id,
                'layanan' => $r->layanan,
                'tanggal' => $r->tanggal,
                'nama' => $r->nama,
                'alamat' => $r->alamat,

                // Payment status
                'payment_status' => $r->payment_status,
                'pembayaran' => $r->pembayaran,

                // Bukti pembayaran
                'bukti_pembayaran' => $r->infoPembayaran->bukti_pembayaran ?? null,

                // Status order
                'status' => $r->status,

                // Total harga final dari admin
                'total' => $r->total,
                'items' => $r->order_details, // Pass detailed items
                'barang' => is_array($r->barang) ? $r->barang : [],

                'tanggal_kembali' => $r->tanggal_kembali,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
            ];
        });

    return Inertia::render('MyLaundry', [
        'reservasis' => $reservasis
    ]);
}



    public function create()
    {
        return Inertia::render('Reservasi', [
            'user' => Auth::user(),
            'products' => \App\Models\Product::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'whatsapp' => 'required|string',
            'layanan' => 'required|string',
            'barang' => 'required|array',
            'alamat' => 'required|string',
            'pembayaran' => 'required|string',
            'metode_pengantaran' => 'required|string',
            'tanggal' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'pesan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu Penjemputan';
        $validated['payment_status'] = 'Pending'; // atau sesuai logika

        Reservasi::create($validated);

        return redirect()->route('mylaundry')->with('success', 'Reservasi berhasil disimpan!');
    }

    public function cancel($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // VALIDASI: Hanya bisa batal jika status masih 'Menunggu Penjemputan'
        if ($reservasi->status !== 'Menunggu Penjemputan') {
            return redirect()->back()->withErrors(['message' => 'Pesanan tidak bisa dibatalkan karena sudah diproses/jemput.']);
        }

        $reservasi->delete();

        if (Reservasi::count() === 0) {
            DB::statement('ALTER TABLE reservasis AUTO_INCREMENT = 1');
        }

        return redirect()->back()->with('success', 'Reservasi dibatalkan.');
    }
}