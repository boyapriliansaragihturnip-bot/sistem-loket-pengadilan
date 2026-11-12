<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PetugasMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'petugas') {
            return $next($request);
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Akses ditolak. Anda bukan Petugas loket.');
    }
}