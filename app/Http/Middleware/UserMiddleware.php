<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna login sebagai user
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);  // Lanjutkan jika role user
        }

        return redirect('/')->with('msg', 'Unauthorized access!');
    }
}
