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
        $types = TourDetailPopup::pluck('type')->toArray();

        if (in_array('cancellation_policy', $types) && in_array('reserve_now_and_pay_later', $types)) {
            abort(404);
        }

        return view('admin.tours.popups.add');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:cancellation_policy,reserve_now_and_pay_later',
            'main_heading' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        TourDetailPopup::create($data);

        return redirect()->route('admin.tour-popups.index')->with('title', 'Add Detail Popup');
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
            'main_heading' => 'required|string|max:255',
            'popup_trigger_text' => 'required|string|max:255',
            'user_showing_text' => 'required|string|max:255',
            'content' => 'required|array',
            'status' => 'required|in:active,inactive',
        ]);

        $data['content'] = json_encode($data['content']);

        $popup->update($data);

        return redirect()->route('admin.tour-popups.index');
    }
}
