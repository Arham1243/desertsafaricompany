<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Redirect to login if not authenticated
        return redirect()->route('admin.login')->with('notify_error', 'You need to login before accessing Admin Dashboard');
    }
}
