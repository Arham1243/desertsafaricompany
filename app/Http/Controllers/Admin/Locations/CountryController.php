<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Tour;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    public function index()
    {
        $items = Country::latest()->get();

        return view('admin.locations.countries-management.list', compact('items'))->with('title', 'Countries');
    }

    public function create()
    {
        $tours = Tour::where('status', 'publish')->get();

        return view('admin.locations.countries-management.add', compact('tours'))->with('title', 'Add New Country');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'iso_alpha2' => 'nullable|min:2|max:2',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);

        $validatedData['iso_alpha2'] = strtolower($validatedData['iso_alpha2'] ?? '');
        $validatedData['json_content'] = json_encode($request->input('json_content', null));
        $slug = $this->createSlug($validatedData['name'], 'countries');

        $featuredImage = null;
        $bannerImage = null;

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Location/Country/Featured-images');
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $this->simpleUploadImg($request->file('banner_image'), 'Location/Country/Banner-images');
        }

        $sectionData = $request->all()['section_content'];
        foreach ($sectionData as $sectionKey => $content) {
            $existingSectionContent = [];
            $updatedContent[$sectionKey] = $this->handleSectionData($content, $existingSectionContent[$sectionKey] ?? [], $sectionKey);
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_image' => $featuredImage,
            'banner_image' => $bannerImage,
            'section_content' => json_encode($updatedContent),
        ]);

        $item = Country::create($data);

        handleSeoData($request, $item, 'Country');

        return redirect()->route('admin.countries.edit', $item->id)->with('notify_success', 'Country Added successfully!');
    }

    public function edit($id)
    {
        $item = Country::find($id);
        $tours = Tour::where('status', 'publish')->get();
        $seo = $item->seo()->first();

        return view('admin.locations.countries-management.edit', compact('item', 'seo', 'tours'))->with('title', ucfirst(strtolower($item->name)));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'iso_alpha2' => 'nullable|min:2|max:2',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);

        $validatedData['json_content'] = json_encode($request->input('json_content', null));
        $validatedData['iso_alpha2'] = strtolower($validatedData['iso_alpha2'] ?? '');

        $item = Country::find($id);
        $slugText = isset($validatedData['slug']) && $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'countries', $item->slug);
        $featuredImage = $item->featured_image;
        $bannerImage = $item->banner_image;

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Location/Country/Featured-images', $item->featured_image);
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $this->simpleUploadImg($request->file('banner_image'), 'Location/Country/Banner-images', $item->banner_image);
        }

        $sectionData = $request->all()['section_content'];
        foreach ($sectionData as $sectionKey => $content) {
            $existingSectionContent = $item->section_content ? json_decode($item->section_content, true) : [];
            $updatedContent[$sectionKey] = $this->handleSectionData($content, $existingSectionContent[$sectionKey] ?? [], $sectionKey);
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_image' => $featuredImage,
            'banner_image' => $bannerImage,
            'section_content' => json_encode($updatedContent),
        ]);

        $item->update($data);

        handleSeoData($request, $item, 'Country');

        return redirect()->route('admin.countries.edit', $item->id)
            ->with('notify_success', 'Country updated successfully.');
    }

    public function handleSectionData(array $newData, ?array $existingData, string $sectionKey)
    {
        switch ($sectionKey) {
            case 'guide':
                return $newData;
        }
    }
}
