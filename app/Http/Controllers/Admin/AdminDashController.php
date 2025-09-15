<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDashController extends Controller
{
    public function dashboard()
    {
        $users = User::where('is_active', 1)->where('email_verified', '!=', null)->get();
        $tours = Tour::where('status', 'publish')->get();
        $totalPayments = Order::where('payment_status', 'paid')->sum('total_amount');
        $data = compact('users', 'tours', 'totalPayments');

        return view('admin.dashboard')->with('title', 'Dashboard')->with($data);
    }

    public function checkFilename(Request $request)
    {
        $filename = $request->query('filename');

        if (! $filename) {
            return response()->json(['exists' => false]);
        }

        $allFiles = Storage::disk('public')->allFiles('uploads');
        foreach ($allFiles as $file) {
            if (basename($file) === $filename) {
                return response()->json([
                    'exists' => true,
                    'path' => Storage::disk('public')->url($file),
                ]);
            }
        }

        return response()->json(['exists' => false]);
    }
}
