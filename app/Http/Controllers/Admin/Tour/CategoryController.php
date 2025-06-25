<?php

namespace App\Http\Controllers\Admin\Tour;

use App\Http\Controllers\Controller;
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
        $dropdownCategories = TourCategory::whereNotIn('id', [$id])->get();
        $toursReviews = TourReview::where('status', 'active')->get();
        $seo = $category->seo()->first();
        $data = compact('category', 'seo', 'tours', 'toursReviews', 'dropdownCategories');

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

        $data['bottom_featured_tour_ids'] = json_encode($request->input('bottom_featured_tour_ids', []));
        $data['recommended_tour_ids'] = json_encode($request->input('recommended_tour_ids', []));
        $data['tour_reviews_ids'] = json_encode($request->input('tour_reviews_ids', []));

        $sectionData = $request->input('content', []);
        $updatedContent = [];
        foreach ($sectionData as $sectionKey => $content) {
            $existingSectionContent = $category->section_content ? json_decode($category->section_content, true) : [];
            $updatedContent[$sectionKey] = $this->handleSectionData($content, $existingSectionContent[$sectionKey] ?? [], $sectionKey);
        }
        $data['section_content'] = json_encode($updatedContent);
        $category->update($data);

        handleSeoData($request, $category, 'Tours/Categories');

        $this->uploadImg('featured_image', 'Tours/Categories/Featured-image', $category, 'featured_image');

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                $path = $this->simpleUploadImg($image, 'Testimonial/Other-images');
                $altText = $request['gallery_alt_texts'][$index] ?? null;
                $category->media()->create([
                    'file_path' => $path,
                    'alt_text' => $altText,
                ]);
            }
        }

        return redirect()->route('admin.tour-categories.index')->with('notify_success', 'Category updated successfully.');
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
}
