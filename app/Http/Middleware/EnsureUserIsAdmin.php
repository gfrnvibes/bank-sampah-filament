<?php

namespace App\Http\Middleware;

use App\Filament\Pages\Auth\AdminLogin;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Bukan admin? out.
        if (Auth::id() !== 1) {
            abort(403, 'dilarang!');
        }
        
        return $next($request);
    }
}
