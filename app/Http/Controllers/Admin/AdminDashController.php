<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tour;
use App\Models\User;

class AdminDashController extends Controller
{
    public function dashboard()
    {
        $users = User::where('is_active', 1)->get();
        $tours = Tour::where('status', 'publish')->get();
        $totalPayments = Order::where('payment_status', 'paid')->sum('total_amount');
        $data = compact('users', 'tours', 'totalPayments');

        return view('admin.dashboard')->with('title', 'Dashboard')->with($data);
    }
}
