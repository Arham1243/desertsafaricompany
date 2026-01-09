<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $bookings = Order::where('user_id', $user_id)
            ->latest()
            ->get();

        return view('user.bookings.list', compact('bookings'))->with('title', 'Tour Bookings');
    }

    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $booking = Order::where('user_id', $user_id)->findOrFail($id);

        return view('user.bookings.edit', compact('booking'))->with('title', 'Booking Details');
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
            ->route('user.bookings.edit', $booking->id)
            ->with('notify_success', 'Booking has been cancelled successfully.');
    }

    public function pay($id)
    {
        $userId = Auth::id();

        $booking = Order::where('user_id', $userId)
            ->where('payment_status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->findOrFail($id);

        return view('user.bookings.pay', compact('booking'))
            ->with('title', 'Booking Payment');
    }

    public function paymentProcess(Request $request, $id, PaymentService $paymentService)
    {
        $user_id = Auth::id();
        $booking = Order::where('user_id', $user_id)->findOrFail($id);

        return $paymentService->processPayment($request, $booking);
    }
}
