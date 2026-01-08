<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        $users = User::latest()->get();

        return view('admin.users-management.list', compact('users'))->with('title', 'Users');
    }

    public function create()
    {
        return view('admin.users-management.add')->with('title', 'Add New User');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'phone' => 'nullable',
            'age' => 'nullable|integer|min:1|max:120',
            'country' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable',
        ]);

        $validatedData['signup_method'] = 'email';
        User::create($validatedData);

        return redirect()
            ->route('admin.users.index')
            ->with('notify_success', 'User added successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users-management.edit', compact('user'))->with('title', ucfirst(strtolower($user->full_name)));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable',
            'age' => 'nullable|integer|min:1|max:120',
            'country' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'status' => 'nullable',
        ]);

        $user->update($validatedData);

        return redirect()
            ->route('admin.users.index')
            ->with('notify_success', 'User updated successfully.');
    }
}
