<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    public function index()
    {
        $items = City::latest()->get();

        return view('admin.locations.cities-management.list', compact('items'))->with('title', 'Cities');
    }

    public function create()
    {
        $countries = Country::where('status', 'publish')->get();

        return view('admin.locations.cities-management.add', compact('countries'))->with('title', 'Add New City');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'country_id' => 'nullable|int',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);
        $slug = $this->createSlug($validatedData['name'], 'cities');

        $featuredImage = null;
        $bannerImage = null;

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Location/City/Featured-images');
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $this->simpleUploadImg($request->file('banner_image'), 'Location/City/Banner-images');
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

        $item = City::create($data);

        handleSeoData($request, $item, 'City');

        return redirect()->route('admin.cities.index')->with('notify_success', 'City Added successfully!');
    }

    public function edit($id)
    {
        $item = City::find($id);
        $countries = Country::where('status', 'publish')->get();
        $seo = $item->seo()->first();

        return view('admin.locations.cities-management.edit', compact('item', 'seo', 'countries'))->with('title', ucfirst(strtolower($item->name)));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'country_id' => 'nullable|int',
            'content' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);
        $item = City::find($id);
        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'cities', $item->slug);

        $featuredImage = $item->featured_image;
        $bannerImage = $item->banner_image;

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Location/City/Featured-images', $item->featured_image);
        }

        if ($request->hasFile('banner_image')) {
            $bannerImage = $this->simpleUploadImg($request->file('banner_image'), 'Location/City/Banner-images', $item->banner_image);
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
        handleSeoData($request, $item, 'City');

        return redirect()->route('admin.cities.index')
            ->with('notify_success', 'City updated successfully.');
    }

    public function handleSectionData(array $newData, ?array $existingData, string $sectionKey)
    {
        switch ($sectionKey) {
            case 'guide':
                return $newData;
        }
    }
}
