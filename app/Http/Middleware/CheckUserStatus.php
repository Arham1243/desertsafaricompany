<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === 'inactive') {
                Auth::logout();

                return redirect()->route('auth.login')
                    ->withErrors(['email' => 'Your account is suspended. Please contact the admin.'])
                    ->with('notify_error', 'Your account is suspended. Please contact the admin.');
            }
        }

        return $next($request);
    }
}
