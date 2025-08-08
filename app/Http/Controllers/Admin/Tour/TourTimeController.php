<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourReview;
use App\Models\TourTime;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class TourTimeController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $times = TourTime::latest()->get();

        return view('admin.tours.times.main')->with('title', 'Tour Times')->with('times', $times);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'tour_times');
        $data = array_merge($validatedData, ['slug' => $slug]);

        TourTime::create($data);

        return redirect()->route('admin.tour-times.index')->with('notify_success', 'Time Added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $time = TourTime::findOrFail($id);
        $tours = Tour::where('status', 'publish')->get();
        $allCategories = TourCategory::where('status', 'publish')->latest()->get();
        $toursReviews = TourReview::where('status', 'active')->get();
        $seo = $time->seo()->first();
        $cities = City::where('status', 'publish')->latest()->get();
        $data = compact('time', 'seo', 'tours', 'toursReviews', 'allCategories', 'cities');

        return view('admin.tours.times.edit')->with('title', ucfirst(strtolower($time->name)))->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();
        $time = TourTime::findOrFail($id);

        $slugText = $request['slug'] ?? $request['name'];
        $slug = $this->createSlug($slugText, 'tour_times', $time->slug);

        $data['slug'] = $slug;

        $data['json_content'] = json_encode($request->input('json_content', null));

        $sectionData = $request->all()['content'] ?? [];
        $updatedContent = [];
        foreach ($sectionData as $sectionKey => $content) {
            $existingSectionContent = $time->section_content ? json_decode($time->section_content, true) : [];
            $updatedContent[$sectionKey] = $this->handleSectionData($content, $existingSectionContent[$sectionKey] ?? [], $sectionKey);
        }
        $data['section_content'] = json_encode($updatedContent);
        $time->categories()->sync($request->input('category_ids', []));
        $time->update($data);

        handleSeoData($request, $time, 'Tours/Times');

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $this->simpleUploadImg($image, 'Tours/Times/Feature-Images');
                $altText = $request['gallery_alt_texts'][$index] ?? null;
                $time->media()->create([
                    'file_path' => $path,
                    'alt_text' => $altText,
                ]);
            }
        }

        return redirect()->route('admin.tour-times.edit', $time->id)->with('notify_success', 'Time Updated successfully.');
    }

    public function handleSectionData(array $newData, ?array $existingData, string $sectionKey)
    {
        switch ($sectionKey) {
            case 'tour_count':
                $newData['background_image'] = $this->handleImageField($newData, $existingData, $sectionKey, 'background_image');

                return $newData;

            case 'call_to_action':
                $newData['background_image'] = $this->handleImageField($newData, $existingData, $sectionKey, 'background_image');

                return $newData;

            case 'newsletter':
                $newData['left_image'] = $this->handleImageField($newData, $existingData, $sectionKey, 'left_image');

                return $newData;
        }
    }

    protected function handleImageField($newData, $existingData, $sectionKey, $field)
    {
        if (isset($newData[$field])) {
            return $this->simpleUploadImg(
                $newData[$field],
                "Tours/Times/Sections/{$sectionKey}",
                $existingData[$field] ?? null
            );
        }

        return $existingData[$field] ?? null;
    }

    public function getByCity($cityId)
    {
        $categories = TourTime::where('city_id', $cityId)
            ->where('status', 'publish')
            ->get(['id', 'name']);

        return response()->json($categories);
    }
}
