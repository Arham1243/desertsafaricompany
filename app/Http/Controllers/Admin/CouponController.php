<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $items = Coupon::latest()->get();

        return view('admin.coupon-management.list', compact('items'))->with('title', 'Coupons');
    }

    public function create()
    {
        return view('admin.coupon-management.add')->with('title', 'Add New Coupon');
    }

    public function store(Request $request)
    {
        $request->merge([
            'no_expiry' => $request->has('no_expiry') ? 1 : 0,
        ]);

        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'name' => 'required|string|min:3',
            'amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'no_expiry' => 'nullable|boolean',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:active,inactive',
        ]);

        if (! $validatedData['no_expiry']) {
            $validatedData['expiry_date'] = null;
        }

        Coupon::create($validatedData);

        return redirect()->route('admin.coupons.index')->with('notify_success', 'Coupon added successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);

        return view('admin.coupon-management.edit', compact('coupon'))->with('title', ucfirst(strtolower($coupon->name)));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->merge([
            'no_expiry' => $request->has('no_expiry') ? 1 : 0,
        ]);

        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,'.$id,
            'name' => 'required|string|min:3',
            'amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:percentage,fixed',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'no_expiry' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        if (! $validatedData['no_expiry']) {
            $validatedData['expiry_date'] = null;
        }

        $coupon->update($validatedData);

        return redirect()->route('admin.coupons.index')->with('notify_success', 'Coupon updated successfully.');
    }
}
