<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImageTable;
use App\Models\Order;
use App\Models\Tour;
use App\Models\User;
use Schema;

class AdminDashController extends Controller
{
    public function __construct()
    {
        $logo = ImageTable::where('table_name', 'logo')->latest()->first();
        View()->share('logo', $logo);
    }

    public function dashboard()
    {
        $users = User::where('is_active', 1)->get();
        $tours = Tour::where('status', 'publish')->get();
        $totalPayments = Order::where('payment_status', 'paid')->sum('total_amount');
        $data = compact('users', 'tours', 'totalPayments');

        return view('admin.dashboard')->with('title', 'Dashboard')->with($data);
    }

    //------------------------- User Management -------------------------
    public function usersListing()
    {
        $users = User::get();
        $data = compact('users');

        return view('admin.users-management.list')->with('title', 'Users Management')->with($data);
    }

    public function editUser($id)
    {
        // $user = User::find($id);
        // if ($user) {
        // $data = compact('user');
        $user = User::find($id);

        // Get all columns for the `users` table
        $table = (new User)->getTable();
        $columns = Schema::getColumnListing($table);

        // Remove columns you don't want to show
        $excludeColumns = ['id', 'created_at', 'updated_at', 'password']; // Example exclusions
        $fields = array_diff($columns, $excludeColumns);

        return view('admin.users-management.edit', compact('user', 'fields'))->with('title', 'View User Details');
        //     return view('admin.users-management.edit')->with('title', 'View User Details')->with($data);
        // }
        // return redirect()->back()->with('notify_error', "User Not Found!");
    }

    public function suspendUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->is_active = $user->is_active ? 0 : 1;
            $user->save();
            $statusMessage = $user->is_active
                ? 'User Activated Successfully!'
                : 'User Suspended Successfully!';

            return redirect()->back()->with('notify_success', $statusMessage);
        }

        return redirect()->back()->with('notify_error', 'User Not Found!');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();

            return redirect()->back()->with('notify_success', 'User Deleted Successfuly!!');
        }

        return redirect()->back()->with('notify_error', 'User Not Found!');
    }
    //------------------------- User Management -------------------------

}
