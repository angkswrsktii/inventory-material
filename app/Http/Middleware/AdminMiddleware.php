<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hanya Admin atau Pimpinan yang bisa akses.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'pimpinan'])) {
            abort(403, 'Akses ditolak. Hanya Pimpinan atau Administrator yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
