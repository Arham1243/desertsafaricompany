<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectUserIfAuthenticated
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return redirect()->route('user.dashboard')->with('notify_success', 'You are already logged in.');
        }

        return $next($request);
    }
}
