<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tour;

class BookingController extends Controller
{
    public function index()
    {
        $tours = Tour::all();

        // Get selected tour ID from request or default to first tour
        $selectedTourId = request('tour_id');
        if (!$selectedTourId && $tours->isNotEmpty()) {
            $selectedTourId = $tours->first()->id;
        }

        // Fetch bookings for the selected tour
        $bookings = [];
        if ($selectedTourId) {
            $orders = Order::whereNotNull('cart_data')
                ->get();

            foreach ($orders as $order) {
                $cartData = json_decode($order->cart_data, true);
                if (isset($cartData['tours'][$selectedTourId])) {
                    $tourData = $cartData['tours'][$selectedTourId];
                    $bookings[] = [
                        'order_id' => $order->id,
                        'booking_confirm_date' => $tourData['start_date'] ?? null,
                        'total_price' => $tourData['total_price'] ?? 0,
                        'customer_name' => $order->user->full_name ?? 'Guest',
                        'payment_status' => $order->payment_status ?? 'Pending',
                        'payment_type' => $order->payment_type ?? null
                    ];
                }
            }
        }

        return view('admin.tours.booking-calendar.main', compact('tours', 'bookings'))
            ->with('title', 'Tours Booking Calendar');
    }
}
