<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Bahasa yang didukung. Default: id (Bahasa Indonesia).
     */
    protected array $supported = ['id', 'en'];

    public function handle(Request $request, Closure $next)
    {
        // Ambil locale dari session, fallback ke 'id'
        $locale = Session::get('locale', 'id');

        if (!in_array($locale, $this->supported)) {
            $locale = 'id';
        }

        App::setLocale($locale);

        return $next($request);
    }
}