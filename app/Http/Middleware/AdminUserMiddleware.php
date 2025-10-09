<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_public_user) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Akses ditolak. Anda harus login sebagai admin.');
    }
}