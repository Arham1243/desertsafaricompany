<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\News;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourReview;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = TourCategory::whereNull('parent_category_id')
            ->orderBy('name', 'asc')
            ->get();

        $dropdownCategories = TourCategory::get();

        $data = compact('categories', 'dropdownCategories');

        return view('admin.tours.categories.main')->with('title', 'Tour Categories')->with($data);
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
            'name' => 'nullable|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_category_id' => 'nullable|exists:tour_categories,id',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'tour_categories');
        $data = array_merge($validatedData, ['slug' => $slug]);

        TourCategory::create($data);

        return redirect()->route('admin.tour-categories.index')->with('notify_success', 'Category Added successfully.');
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
        $category = TourCategory::findOrFail($id);
        $tours = Tour::where('status', 'publish')->get();
        $countries = Country::where('status', 'publish')->latest()->get();
        $dropdownCategories = TourCategory::where('status', 'publish')
            ->where('id', '!=', $id)
            ->get();
        $allCategories = TourCategory::where('status', 'publish')->latest()->get();
        $toursReviews = TourReview::where('status', 'active')->get();
        $news = News::where('status', 'publish')
            ->get();
        $seo = $category->seo()->first();
        $cities = City::where('status', 'publish')->latest()->get();
        $data = compact('category', 'seo', 'tours', 'toursReviews', 'dropdownCategories', 'allCategories', 'cities', 'countries', 'news');

        return view('admin.tours.categories.edit')->with('title', ucfirst(strtolower($category->name)))->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $category = TourCategory::findOrFail($id);

        $slugText = $request['slug'] ?? $request['name'];
        $slug = $this->createSlug($slugText, 'tour_categories', $category->slug);

        $data['slug'] = $slug;

        $data['json_content'] = json_encode($request->input('json_content', null));
        $fieldsToNullify = ['parent_category_id', 'city_id'];

        foreach ($fieldsToNullify as $field) {
            if (! $request->has($field)) {
                $data[$field] = null;
            }
        }

        $sectionData = $request->all()['content'] ?? [];
        $updatedContent = [];
        foreach ($sectionData as $sectionKey => $content) {
            $existingSectionContent = $category->section_content ? json_decode($category->section_content, true) : [];
            $updatedContent[$sectionKey] = $this->handleSectionData($content, $existingSectionContent[$sectionKey] ?? [], $sectionKey);
        }
        $data['section_content'] = json_encode($updatedContent);
        $category->update($data);

        handleSeoData($request, $category, 'Tours/Categories');

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $this->simpleUploadImg($image, 'Tours/Categories/Feature-Images');
                $altText = $request['gallery_alt_texts'][$index] ?? null;
                $category->media()->create([
                    'file_path' => $path,
                    'alt_text' => $altText,
                ]);
            }
        }

        return redirect()->route('admin.tour-categories.edit', $category->id)->with('notify_success', 'Category Updated successfully.');
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

            case 'faq_section':
                return $newData;

            case 'newsletter':
                $newData['left_image'] = $this->handleImageField($newData, $existingData, $sectionKey, 'left_image');

                return $newData;

            case 'news_section':
                return $newData;
        }
    }

    protected function handleImageField($newData, $existingData, $sectionKey, $field)
    {
        if (isset($newData[$field])) {
            return $this->simpleUploadImg(
                $newData[$field],
                "Tours/Categories/Sections/{$sectionKey}",
                $existingData[$field] ?? null
            );
        }

        return $existingData[$field] ?? null;
    }

    public function getByCity(Request $request)
    {
        $cityId = $request->input('city_id');

        $query = TourCategory::where('status', 'publish');

        if (! empty($cityId)) {
            $query->where('city_id', $cityId);
        }

        return response()->json(
            $query->get(['id', 'name', 'parent_category_id'])
        );
    }

    public function duplicate($id)
    {
        $category = TourCategory::findOrFail($id);

        $newCategory = $category->replicate();

        $newCategory->name = $category->name.' - Copy';
        $newCategory->status = 'draft';
        $newCategory->slug = $this->createSlug($newCategory->name, 'tour_categories');

        $newCategory->save();
        $this->duplicateSeoData($category, $newCategory);
        $this->duplicateMedia($category, $newCategory);

        return redirect()->route('admin.tour-categories.index')->with('notify_success', 'Category duplicated successfully.');
    }

    public function duplicateSeoData($category, $newCategory)
    {
        $category->load('seo');

        if ($category->seo) {
            $newSeoData = $category->seo->replicate();

            $newSeoData->seoable_id = $newCategory->id;
            $newSeoData->seoable_type = get_class($newCategory);

            $newSeoData->save();
        }
    }

    private function duplicateMedia($category, $newCategory)
    {
        foreach ($category->media as $media) {
            $newCategory->media()->create([
                'file_path' => $media->file_path,
                'alt_text' => $media->alt_text,
            ]);
        }
    }
}
