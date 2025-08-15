<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function login()
    {
        $adminGuard = Auth::guard('admin');

        if ($adminGuard->check()) {
            return redirect()->route('admin.dashboard')->with('notify_success', 'You are already logged in as Admin');
        }

        return view('admin.login', ['title' => 'Admin Login']);
    }

    public function performLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return redirect()->intended('admin/dashboard')->with('notify_success', 'You are logged in as Admin');
        } else {
            return redirect()->back()->withErrors(['email' => 'Invalid Credentials'])->withInput($request->input())->with('notify_error', 'Invalid Credentials');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('notify_success', 'Logged Out!');
    }
}
