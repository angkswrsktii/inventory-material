<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kepala Gudang, Pimpinan, dan Admin bisa akses.
 * Dipakai untuk: approval permintaan barang, purchase request/order, pengelolaan stok.
 */
class KepalaGudangMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['kepala_gudang', 'pimpinan', 'admin'])) {
            abort(403, 'Akses ditolak. Hanya Kepala Gudang, Pimpinan, atau Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
