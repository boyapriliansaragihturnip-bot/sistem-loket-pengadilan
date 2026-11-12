<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PenggunaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'pengguna') {
            return $next($request);
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu sebagai Pengguna.');
    }
}