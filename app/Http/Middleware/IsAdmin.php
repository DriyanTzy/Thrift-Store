<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->is_admin) {
                return $next($request);
            }

            return redirect('/')->with('error', 'Akses ditolak! Halaman ini hanya untuk Administrator.');
        }

        return redirect()->route('admin.login')->with('error', 'Silakan masuk melalui Portal Khusus Admin.');
    }
}
