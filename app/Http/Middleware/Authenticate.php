<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // If accessing /form route, redirect to public user login
            if ($request->is('form') || $request->is('form/*')) {
                return route('loginuser');
            }
            
            // Otherwise, redirect to admin/staff login
            return route('login');
        }
    }
}
