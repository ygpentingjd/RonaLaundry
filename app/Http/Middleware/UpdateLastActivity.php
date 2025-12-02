<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateLastActivity
{
    public function handle($request, Closure $next)
    {
        try {
            if (Auth::check()) {

                /** @var \App\Models\User $user */
                $user = Auth::user();

                if ($user) {
                    $user->update([
                        'last_activity' => now(),
                        'status' => 'Online',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Update last activity error: ' . $e->getMessage());
        }

        return $next($request);
    }
}