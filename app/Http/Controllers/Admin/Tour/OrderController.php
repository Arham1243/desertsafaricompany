<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function update(Request $request, $id)
    {
        $booking = Order::findOrFail($id);

        $tourId = $request->tour_id;

        $cartData = json_decode($booking->cart_data, true);

        if (isset($cartData['tours'][$tourId])) {
            // Update start_date at top level
            $cartData['tours'][$tourId]['start_date'] = $request->start_date;

            // Optional: update nested tourData array if exists
            if (isset($cartData['tours'][$tourId]['tourData'][0])) {
                $cartData['tours'][$tourId]['tourData'][0]['start_date'] = $request->start_date;
            }

            $booking->cart_data = json_encode($cartData);
            $booking->save();
        }

        return redirect()
            ->route('admin.bookings.edit', $booking->id)
            ->with('notify_success', 'Booking updated successfully.');
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
