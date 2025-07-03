<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\TourAuthor;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {

        $authors = TourAuthor::where('system', '!=', 1)->get();
        $systemAuthor = TourAuthor::where('system', 1)->first();
        $data = compact('authors', 'systemAuthor');

        return view('admin.tours.authors.main')->with('title', 'Tour Authors')->with($data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
        ]);

        TourAuthor::create($validatedData);

        return redirect()->route('admin.tour-authors.index')->with('notify_success', 'Author Added successfully.');
    }

    public function edit($id)
    {
        $author = TourAuthor::findOrFail($id);
        $data = compact('author');

        return view('admin.tours.authors.edit')->with('title', ucfirst(strtolower($author->name)))->with($data);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        $author = TourAuthor::findOrFail($id);

        $author->update($validatedData);

        return redirect()->route('admin.tour-authors.index')->with('notify_success', 'Author updated successfully.');
    }
}
