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

    public function cancel($id)
    {
        $booking = Order::findOrFail($id);

        if ($booking->payment_status !== 'pending' || $booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->with('notify_error', 'Only pending bookings can be cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('admin.bookings.edit', $booking->id)
            ->with('notify_success', 'Booking has been cancelled successfully.');
    }
}
