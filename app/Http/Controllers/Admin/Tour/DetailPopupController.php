<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\TourDetailPopup;
use Illuminate\Http\Request;

class DetailPopupController extends Controller
{
    public function index()
    {
        $items = TourDetailPopup::latest()->get();

        return view('admin.tours.popups.list', compact('items'))->with('title', 'All Detail Popups');
    }

    public function create()
    {
        return view('admin.tours.popups.add');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:policy,info',
            'main_heading' => 'required|string|max:255',
            'popup_trigger_text' => 'required|string|max:255',
            'user_showing_text' => 'required|string|max:255',
            'content' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $data['content'] = json_encode($data['content']);

        $popup = TourDetailPopup::create($data);

        return redirect()->route('admin.tour-popups.index')->with('notify_success', 'Popup Added successfully.');
    }

    public function edit($id)
    {
        $item = TourDetailPopup::findOrFail($id);

        return view('admin.tours.popups.edit', compact('item'))->with('title', ucfirst(strtolower($item->main_heading)));
    }

    public function update(Request $request, $id)
    {
        $popup = TourDetailPopup::findOrFail($id);

        $data = $request->validate([
            'type' => 'required|in:policy,info',
            'main_heading' => 'required|string|max:255',
            'popup_trigger_text' => 'required|string|max:255',
            'user_showing_text' => 'required|string|max:255',
            'content' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $data['content'] = json_encode($data['content']);

        $popup->update($data);

        return redirect()->route('admin.tour-popups.index')->with('notify_success', 'Popup Updated successfully.');
    }
}
