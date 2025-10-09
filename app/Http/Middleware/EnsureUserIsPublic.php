<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('loginuser')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        if (!$user->is_public_user) {
            Auth::logout();
            return redirect()->route('loginuser')
                ->with('error', 'Akses ditolak. Anda harus login sebagai pengguna umum.');
        }

        return $next($request);
    }
}
