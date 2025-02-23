<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Izinkan akses ke halaman login tanpa pengecekan role
        if ($request->is('admin/login') || $request->is('admin/password-reset/*')) {
            return $next($request);
        }

        // Cek apakah user sudah login dan memiliki role Admin
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            abort(404); // Redirect ke halaman 404 jika bukan Admin
        }

        return $next($request);
    }
}
