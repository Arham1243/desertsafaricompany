<?php

namespace App\Http\Controllers\Admin\Locations;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourCategory;
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
        $categories = TourCategory::where('status', 'publish')->get();
        $tours = Tour::where('status', 'publish')->get();

        return view('admin.locations.cities-management.add', compact('countries', 'categories', 'tours'))->with('title', 'Add New City');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'country_id' => 'nullable|int',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);
        $slug = $this->createSlug($validatedData['name'], 'cities');
        $validatedData['json_content'] = json_encode($request->input('json_content', null));
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

        return redirect()->route('admin.cities.edit', $item->id)->with('notify_success', 'City Added successfully!');
    }

    public function edit($id)
    {
        $item = City::find($id);
        $countries = Country::where('status', 'publish')->get();
        $tours = Tour::where('status', 'publish')->get();
        $categories = TourCategory::where('status', 'publish')->get();
        $seo = $item->seo()->first();

        return view('admin.locations.cities-management.edit', compact('item', 'seo', 'countries', 'tours', 'categories'))->with('title', ucfirst(strtolower($item->name)));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'country_id' => 'nullable|int',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'featured_image' => 'nullable|image',
            'featured_image_alt_text' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image',
            'banner_image_alt_text' => 'nullable|string|max:255',
        ]);
        $item = City::find($id);
        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'cities', $item->slug);
        $validatedData['json_content'] = json_encode($request->input('json_content', null));
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

        return redirect()
            ->route('admin.cities.edit', $item->id)
            ->with('notify_success', 'City updated successfully.');
    }

    public function handleSectionData(array $newData, ?array $existingData, string $sectionKey)
    {
        switch ($sectionKey) {
            case 'guide':
                return $newData;
        }
    }

    public function getByCountry($countryId)
    {
        $cities = City::where('country_id', $countryId)->select('id', 'name')->get();

        return response()->json($cities);
    }

    public function duplicate($id)
    {
        $city = City::findOrFail($id);

        $newCity = $city->replicate();

        $newCity->name = $city->name.' - Copy';
        $newCity->status = 'draft';
        $newCity->slug = $this->createSlug($newCity->name, 'cities');

        $newCity->save();
        $this->duplicateSeoData($city, $newCity);

        return redirect()->route('admin.cities.index')->with('notify_success', 'City duplicated successfully.');
    }

    public function duplicateSeoData($city, $newCity)
    {
        $city->load('seo');

        if ($city->seo) {
            $newSeoData = $city->seo->replicate();

            $newSeoData->seoable_id = $newCity->id;
            $newSeoData->seoable_type = get_class($newCity);

            $newSeoData->save();
        }
    }
}
