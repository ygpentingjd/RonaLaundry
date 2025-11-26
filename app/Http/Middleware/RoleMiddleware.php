<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Jika belum login → redirect ke login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Jika role tidak cocok → tolak
        if (Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}