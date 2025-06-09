<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\News;
use App\Models\Page;
use App\Models\Section;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    public function index()
    {
        $pages = Page::latest()->get();
        $data = compact('pages');

        return view('admin.pages.pages-management.list')->with('title', 'All Pages')->with($data);
    }

    public function create()
    {
        return view('admin.pages.pages-management.add')->with('title', 'Add New Page');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|min:3|max:255',
            'content' => 'nullable',
            'content' => 'nullable',
            'header_style' => 'nullable',
            'footer_style' => 'nullable',
            'banner_image' => 'nullable',
            'banner_image_alt_text' => 'nullable',
            'status' => 'nullable|in:publish,draft',
        ]);

        $slug = $this->createSlug($validatedData['title'], 'pages');

        $data = array_merge($validatedData, [
            'slug' => $slug,
        ]);

        $page = Page::create($data);
        $this->uploadImg('banner_image', 'Pages/banner-images', $page, 'banner_image');

        handleSeoData($request, $page, 'Page/'.$page->title);

        return redirect()->route('admin.pages.edit', $page->id)->with('notify_success', 'Page Added successfully!');
    }

    public function edit(Page $page)
    {
        $seo = $page->seo()->first();
        $data = compact('page', 'seo');

        return view('admin.pages.pages-management.edit')->with('title', ucfirst(strtolower($page->title)))->with($data);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|min:3|max:255',
            'content' => 'nullable',
            'slug' => 'nullable',
            'content' => 'nullable',
            'header_style' => 'nullable',
            'footer_style' => 'nullable',
            'banner_image' => 'nullable',
            'banner_image_alt_text' => 'nullable',
            'status' => 'nullable|in:publish,draft',
        ]);

        $page = Page::where('id', $id)->firstOrFail();
        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['title'];
        $slug = $this->createSlug($slugText, 'pages', $page->slug);
        $show_page_builder_sections = isset($request->show_page_builder_sections) ? $request->show_page_builder_sections : 0;

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'show_page_builder_sections' => $show_page_builder_sections,
        ]);

        $page->update($data);
        $this->uploadImg('banner_image', 'Pages/banner-images', $page, 'banner_image');

        handleSeoData($request, $page, 'Page/'.$page->title);

        return redirect()->route('admin.pages.index')->with('notify_success', 'Page updated successfully.');
    }

    public function editTemplate(Page $page)
    {
        $tourCategories = TourCategory::withCount('tours')
            ->where('status', 'publish')
            ->get();
        $cities = City::withCount('tours')
            ->where('status', 'publish')
            ->get();
        $tours = Tour::where('status', 'publish')->get();
        $categoryOrder = config('sectionCategories');
        $sectionsByGroup = Section::where('status', 'active')->get()->groupBy('category');
        $sectionsGroups = collect($categoryOrder)->mapWithKeys(function ($category) use ($sectionsByGroup) {
            return [$category => $sectionsByGroup->get($category, collect())];
        });
        $selectedSections = $page->sections->map(function ($section) {
            return [
                'pivot_id' => $section->pivot->id,
                'section_id' => $section->id,
                'name' => $section->name,
                'section_key' => $section->section_key,
                'preview_image' => asset($section->preview_image),
                'template_path' => $section->template_path,
                'order' => $section->pivot->order,
            ];
        })->sortBy('order')
            ->values()
            ->toJson();

        return view('admin.pages.page-builder.main', compact('tours', 'tourCategories', 'cities', 'page', 'sectionsGroups', 'selectedSections'))->with('title', ucfirst(strtolower($page->title)));
    }

    public function storeTemplate(Request $request, $pageId)
    {
        $request->validate([
            'sections.section_id' => 'nullable|array',
            'sections.order' => 'nullable|array',
            'sections.id' => 'nullable|array',
        ]);

        $sectionIds = $request->input('sections.section_id');
        $orders = $request->input('sections.order');
        $ids = $request->input('sections.id', []);

        if (count($sectionIds) !== count($orders)) {
            return redirect()->route('admin.pages.index')->with('notify_error', 'Section IDs and order values do not match.');
        }

        DB::table('page_section')
            ->where('page_id', $pageId)
            ->whereNotIn('id', array_filter($ids))
            ->delete();

        $newData = [];
        $updates = [];

        foreach ($sectionIds as $index => $sectionId) {
            $order = $orders[$index];
            $id = $ids[$index] ?? null;

            if ($id) {
                $updates[] = [
                    'id' => $id,
                    'order' => $order,
                ];
            } else {
                // Add new row
                $newData[] = [
                    'page_id' => $pageId,
                    'section_id' => $sectionId,
                    'order' => $order,
                ];
            }
        }

        // Batch update for existing rows
        foreach ($updates as $update) {
            DB::table('page_section')
                ->where('id', $update['id'])
                ->update(['order' => $update['order']]);
        }

        // Insert new rows in bulk
        if (! empty($newData)) {
            DB::table('page_section')->insert($newData);
        }

        Page::where('id', $pageId)->update([
            'show_page_builder_sections' => 0,
        ]);

        return redirect()->back()->with('notify_success', 'Layout Saved Successfully!');
    }

    public function getSectionTemplate(Request $request, $pageId)
    {
        $templatePath = $request->input('template_path');
        $sectionId = $request->input('section_id');
        $pivotId = $request->input('pivot_id');
        $pageSection = DB::table('page_section')->where('id', $pivotId)->where('page_id', $pageId)->where('section_id', $sectionId)->first();
        $componentView = "admin.pages.page-builder.sections.{$templatePath}";

        if (view()->exists($componentView)) {
            $tours = Tour::where('status', 'publish')->latest()->orderBy('title')->get();
            $cities = City::withCount('tours')
                ->where('status', 'publish')
                ->get();
            $tourCategories = TourCategory::withCount('tours')
                ->where('status', 'publish')
                ->get();
            $countries = Country::where('status', 'publish')
                ->get();
            $testimonials = Testimonial::where('status', 'active')
                ->where('rating', '5')
                ->get();
            $news = News::where('status', 'publish')
                ->get();

            $html = view($componentView, compact('tours', 'news', 'tourCategories', 'cities', 'countries', 'testimonials', 'pageSection'));

            return $html;
        }

        return response()->json(['error' => 'Template not found'], 404);
    }

    public function saveSectionDetails(Request $request, $pageId)
    {
        $sectionKey = Section::where('id', $request->section_id)->first()->section_key;
        $pageSlug = Page::where('id', $pageId)->first()->slug;

        $existingSection = DB::table('page_section')
            ->where('page_id', $pageId)
            ->where('section_id', $request->input('section_id'))
            ->first();

        $existingContent = $existingSection ? json_decode($existingSection->content, true) : [];

        $sectionData = $request->all()['content'];
        $updatedContent = $this->handleSectionData($sectionData, $existingContent, $pageSlug, $sectionKey);

        DB::table('page_section')
            ->updateOrInsert(
                ['page_id' => $pageId, 'section_id' => $request->input('section_id'), 'id' => $request->input('pivot_id')],
                ['content' => json_encode($updatedContent)]
            );

        return redirect()->back()->with('notify_success', 'Section Updated Successfully!');
    }

    public function handleSectionData(array $newData, ?array $existingData, string $pageSlug, string $sectionKey)
    {

        switch ($sectionKey) {
            case 'water_activities_3_box_layout':
                return $this->handlePromotions($newData, $existingData, $pageSlug, $sectionKey);

            case 'banner':
                return $this->handleBanner($newData, $existingData, $pageSlug, $sectionKey);

            case 'cities_with_tour_count_slider':
                $newData['destination_background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'destination_background_image');

                return $newData;

            case 'call_to_action':
                $newData['background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'background_image');
                $newData['background_image_2'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'background_image_2');

                return $newData;

            case 'newsletter_signup':
                $newData['left_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'left_image');

                return $newData;

            case 'app_download':
                $newData['background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'background_image');
                $newData['qr_code_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'qr_code_image');

                return $newData;

            default:
                return $newData;
        }
    }

    private function handlePromotions(array $newData, ?array $existingData, string $pageSlug, string $sectionKey)
    {

        $updatedActivities = [];
        foreach ($newData['activities'] as $i => $section) {
            $newSection = $section;

            if (isset($section['image'])) {
                $image = $section['image'];
                $newSection['image'] = $this->simpleUploadImg($image, "Pages/{$pageSlug}/{$sectionKey}", $existingData['image'] ?? null);
            } else {
                $newSection['image'] = $existingData['activities'][$i]['image'] ?? null;
            }

            $updatedActivities[] = $newSection;
        }
        $newData['activities'] = $updatedActivities;

        return $newData;
    }

    private function handleBanner(array $newData, ?array $existingData, string $pageSlug, string $sectionKey)
    {

        $newData['right_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'right_image');
        $newData['background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'background_image');
        $newData['custom_review_logo_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'custom_review_logo_image');
        $newData['right_image_background'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'right_image_background');
        $newData['destination_background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'destination_background_image');
        $newData['normal_wave_background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'normal_wave_background_image');
        $newData['right_image_wave_background_image'] = $this->handleImageField($newData, $existingData, $pageSlug, $sectionKey, 'right_image_wave_background_image');
        if (isset($newData['carousel_background_images'])) {
            $updatedBackgroundImages = $existingData['carousel_background_images'] ?? [];
            foreach ($newData['carousel_background_images'] as $i => $image) {
                if (is_uploaded_file($image)) {
                    $updatedBackgroundImages[$i] = $this->simpleUploadImg($image, "Pages/{$pageSlug}/{$sectionKey}", $existingData['carousel_background_images'][$i] ?? null);
                } else {
                    $updatedBackgroundImages[$i] = $existingData['carousel_background_images'][$i] ?? null;
                }
            }
            $newData['carousel_background_images'] = $updatedBackgroundImages;
        } else {
            $newData['carousel_background_images'] = $existingData['carousel_background_images'] ?? [];
        }
        if (isset($newData['carousel_alt_text'])) {
            foreach ($newData['carousel_alt_text'] as $i => $alt) {
                $newData['carousel_alt_text'][$i] = $alt ?: ($existingData['carousel_alt_text'][$i] ?? 'Carousel Image '.($i + 1));
            }
        }

        return $newData;
    }

    protected function handleImageField($newData, $existingData, $pageSlug, $sectionKey, $field)
    {
        if (isset($newData[$field])) {
            return $this->simpleUploadImg(
                $newData[$field],
                "Pages/{$pageSlug}/{$sectionKey}",
                $existingData[$field] ?? null
            );
        }

        return $existingData[$field] ?? null;
    }
}
