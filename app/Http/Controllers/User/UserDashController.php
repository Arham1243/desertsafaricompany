<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard')->with('title', 'Dashboard');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('frontend.index');
    }
}
