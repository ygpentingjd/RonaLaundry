<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AdminMiddleware triggered');
        
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            return redirect('/')->with('error', 'Akses ditolak. Anda bukan admin.');
        }
        return $next($request);
    }
}