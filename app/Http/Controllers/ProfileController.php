<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
         /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:255',
            'catatan_lokasi' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}