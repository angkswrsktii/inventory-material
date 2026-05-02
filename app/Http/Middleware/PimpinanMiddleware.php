<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hanya Pimpinan (dan Admin) yang bisa akses.
 * Dipakai untuk: approval purchase order, laporan lengkap, manajemen user.
 */
class PimpinanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['pimpinan', 'admin'])) {
            abort(403, 'Akses ditolak. Hanya Pimpinan yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
