<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {

        $bookings = Order::with('user')->latest()->get();

        return view('admin.tours.bookings.list', compact('bookings'))->with('title', 'Tour Bookings');
    }

    public function edit($id)
    {
        $booking = Order::findOrFail($id);

        return view('admin.tours.bookings.edit', compact('booking'))->with('title', 'Booking Details');
    }
}
