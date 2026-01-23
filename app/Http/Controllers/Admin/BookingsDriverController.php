<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingDriver;
use Illuminate\Http\Request;

class BookingsDriverController extends Controller
{
    public function index()
    {
        $items = BookingDriver::latest()->get();
        return view('admin.tours.bookings.drivers.list', compact('items'))->with('title', 'Drivers');
    }

    public function create()
    {
        return view('admin.tours.bookings.drivers.add')->with('title', 'Add New Driver');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'status' => 'required|in:active,inactive',
        ]);

        BookingDriver::create($validatedData);

        return redirect()->route('admin.booking-drivers.index')->with('notify_success', 'Driver added successfully.');
    }

    public function edit($id)
    {
        $driver = BookingDriver::findOrFail($id);

        return view('admin.tours.bookings.drivers.edit', compact('driver'))->with('title', ucfirst(strtolower($driver->name)));
    }

    public function update(Request $request, $id)
    {
        $driver = BookingDriver::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'status' => 'required|in:active,inactive',
        ]);
        $driver->update($validatedData);

        return redirect()->route('admin.booking-drivers.index')->with('notify_success', 'Driver updated successfully.');
    }
}
