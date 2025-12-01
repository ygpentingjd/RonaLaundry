<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // tampilkan daftar user -> pass ke Inertia
    public function index()
    {
        $users = User::all()->map(function ($user) {
            return [
                'id_user'        => $user->id,
                'username'       => $user->username,
                'email'          => $user->email,
                'role'           => $user->role,
                'nomor_telepon'  => $user->nomor_telepon,
                'alamat_lengkap' => $user->alamat_lengkap,
                'created_at'     => $user->created_at,
                'updated_at'     => $user->updated_at,
                'status'         => $user->isOnline() ? 'Online' : 'Offline',
                'last_activity'  => $user->last_activity,
            ];
        });

        return inertia('admin/Users', [
            'users' => $users,
        ]);
    }


    // simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(['Active','Inactive'])],
            'password' => 'nullable|string|min:6'
        ]);

        // kalau tidak ada password, isi default (atau kirim email set password)
        $password = $validated['password'] ?? bcrypt('password123');
        

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $password,
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'role' => $validated['role'] ?? 'User',
            'status' => $validated['status'],
            // map field lain jika ada, mis. role_id, phone, dsb.
        ]);

        // return created user (sama shape seperti index)
        return response()->json([
            'user' => [
                'id_user' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'alamat_lengkap' => $user->alamat_lengkap,
                'status' => $user->status,
                'nomor_telepon' => $user->nomor_telepon,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }

    // update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'role' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'alamat_lengkap' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(['Active','Inactive'])],
            'password' => 'nullable|string|min:6'
        ]);

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->alamat_lengkap = $validated['alamat_lengkap'] ?? $user->alamat_lengkap;
        $user->nomor_telepon = $validated['nomor_telepon'] ?? $user->nomor_telepon;
        $user->status = $validated['status'];
        $user->save();

        return response()->json([
            'user' => [
                'id_user' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'alamat_lengkap' => $user->alamat_lengkap,
                'status' => $user->status,
                'nomor_telepon' => $user->nomor_telepon,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 200);
    }

    // hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted'], 200);
    }
}