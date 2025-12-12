<?php

namespace App\Http\Middleware;

use App\Filament\Nasabah\Pages\Auth\NasabahLogin;
use Closure;
use Filament\Facades\Filament;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsRegularUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login, tampilkan halaman login nasabah
        if (!Auth::check()) {
            return redirect()->route('nasabah.login');
        }

        // Jika sudah login Admin

        if (!Auth::check() || Auth::user()->id ===   1) {
            abort(403, 'dilarang');
        }
        return $next($request);
    }
}
