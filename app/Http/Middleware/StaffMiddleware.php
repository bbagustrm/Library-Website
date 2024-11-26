<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna login sebagai staff
        if (Auth::check() && Auth::user()->role === 'staff') {
            return $next($request);  // Lanjutkan jika role staff
        }

        return redirect('/')->with('msg', 'Unauthorized access!');
    }
}
