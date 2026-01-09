<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get filtered query
        $bookingsQuery = $this->getFilteredBookings($request);

        // Execute query
        $bookings = $bookingsQuery->with('user')->latest()->get();

        return view('admin.tours.bookings.list', compact('bookings'))->with('title', 'Tour Bookings');
    }

    private function getFilteredBookings(Request $request)
    {
        $query = Order::query();

        // Filter by booking start date
        if ($request->filled('start_date')) {
            $query->whereRaw("
        JSON_SEARCH(cart_data, 'one', ?) IS NOT NULL
    ", [$request->start_date]);
        }

        // Filter by order created at date
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        return $query;
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
