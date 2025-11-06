<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('MyLaundry', [
            'reservasis' => $reservasis
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
            'tanggal' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();

        Reservasi::create($validated);

        return redirect()->route('mylaundry')->with('success', 'Reservasi berhasil disimpan!');
    }

    public function cancel($id)
    {
        $reservasi = Reservasi::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reservasi->status = 'Batal';
        $reservasi->save();

        return redirect()->back()->with('success', 'Reservasi dibatalkan.');
    }
}