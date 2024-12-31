<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function index()
    {
        $popups = Popup::latest()->get();

        return view('admin.popup-management.list', compact('popups'))->with('title', 'Popups');
    }

    public function create()
    {
        return view('admin.popup-management.add')->with('title', 'Add New Popup');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|min:5|max:60',
            'content' => 'required',
            'included_pages' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $validatedData['included_pages'] = json_encode($validatedData['included_pages']);
        Popup::create($validatedData);

        return redirect()->route('admin.popups.index')->with('notify_success', 'Popup added successfully.');
    }

    public function edit($id)
    {
        $item = Popup::findOrFail($id);

        return view('admin.popup-management.edit', compact('item'))->with('title', ucfirst(strtolower($item->title)));
    }

    public function update(Request $request, $id)
    {
        $popup = Popup::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string|min:5|max:60',
            'content' => 'required',
            'included_pages' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $validatedData['included_pages'] = json_encode($validatedData['included_pages']);
        $popup->update($validatedData);

        return redirect()->route('admin.popups.index')->with('notify_success', 'Popup updated successfully.');
    }
}
