<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsTag;
use App\Models\Tour;
use App\Models\User;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::latest()->get();
        $data = compact('news');

        return view('admin.news.news-management.list')->with('title', 'All News')->with($data);
    }

    public function create()
    {
        $tours = Tour::where('status', 'publish')->get();
        $categories = NewsCategory::where('is_active', 1)->get();
        $tags = NewsTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $data = compact('tours', 'categories', 'users', 'tags');

        return view('admin.news.news-management.add')->with('title', 'Add News')->with($data);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'user_id' => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:news_categories,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:news_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
        ]);

        $slug = $this->createSlug($validatedData['title'], 'blogs');

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'News/Featured-images');
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_image' => $featuredImage,
        ]);

        $news = News::create($data);

        if (! empty($validatedData['tags_ids'])) {
            $news->tags()->attach($validatedData['tags_ids']);
        }
        handleSeoData($request, $news, 'News');

        return redirect()->route('admin.news.index')->with('notify_success', 'News Added successfully!');
    }

    public function edit(News $news)
    {
        $tours = Tour::where('status', 'publish')->get();
        $categories = NewsCategory::where('is_active', 1)->get();
        $tags = NewsTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $seo = $news->seo()->first();
        $data = compact('tours', 'categories', 'users', 'tags', 'news', 'seo');

        return view('admin.news.news-management.edit')->with('title', ucfirst(strtolower($news->title)))->with($data);
    }

    public function update(Request $request, News $news)
    {

        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'user_id' => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:news_categories,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:news_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['title'];
        $slug = $this->createSlug($slugText, 'blogs', $news->slug);

        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'News/Featured-images', $news->featured_image);
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_image' => $featuredImage,
        ]);

        // Update the blog entry
        $news->update($data);

        // Update tags
        if (! empty($validatedData['tags_ids'])) {
            $news->tags()->sync($validatedData['tags_ids']);
        } else {
            $news->tags()->detach();
        }
        handleSeoData($request, $news, 'Blog');

        return redirect()->route('admin.news.index')->with('notify_success', 'News updated successfully!');
    }
}
