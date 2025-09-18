<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogMedia;
use App\Models\BlogTag;
use App\Models\City;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourAuthor;
use App\Models\User;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
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
        $blogs = Blog::latest()->get();
        $data = compact('blogs');

        return view('admin.blogs.blogs-management.list')->with('title', 'All Blogs')->with($data);
    }

    public function create()
    {
        $tours = Tour::where('status', 'publish')->get();
        $dropdownBlogs = Blog::where('status', 'publish')->get();
        $authors = TourAuthor::where('status', 'active')->get();
        $countries = Country::where('status', 'publish')->where('available_for_blogs', 1)->get();
        $cities = City::where('status', 'publish')->get();
        $categories = BlogCategory::where('is_active', 1)->get();
        $tags = BlogTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $data = compact('tours', 'countries', 'cities', 'categories', 'users', 'tags', 'dropdownBlogs', 'authors');

        return view('admin.blogs.blogs-management.add')->with('title', 'Add New Blog')->with($data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:blog_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
            'json_content' => 'nullable',
            'may_also_like' => 'nullable',
        ]);

        $slug = $this->createSlug($validatedData['title'], 'blogs');

        $featuredToursIds = json_encode($validatedData['featured_tours_ids'] ?? null);
        $mayAlsoLikeIds = json_encode($validatedData['may_also_like'] ?? null);
        $jsonContent = json_encode($validatedData['json_content'] ?? null);

        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Blogs/Featured-images');
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_tours_ids' => $featuredToursIds,
            'featured_image' => $featuredImage,
            'may_also_like' => $mayAlsoLikeIds,
            'json_content' => $jsonContent,
        ]);

        $blog = Blog::create($data);

        if (! empty($validatedData['tags_ids'])) {
            $blog->tags()->attach($validatedData['tags_ids']);
        }

        if (! empty($validatedData['gallery'])) {
            $this->uploadMultipleImages(
                'gallery',  // Input name for the images
                'Blog/Gallery',  // Folder to store images
                new BlogMedia,  // Pass the model class name here
                'image_path',  // Column name for image path
                'alt_text',  // Column name for alt text
                $validatedData['gallery_alt_texts'] ?? null,  // Pass alt texts if provided
                'blog_id',  // Pass the foreign key column name
                $blog->id  // Pass the blog_id as the foreign key value
            );
        }

        handleSeoData($request, $blog, 'Blog');

        return redirect()->route('admin.blogs.edit', $blog->id)->with('notify_success', 'Blog Added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        $tours = Tour::where('status', 'publish')->get();
        $categories = BlogCategory::where('is_active', 1)->get();
        $authors = TourAuthor::where('status', 'active')->get();
        $countries = Country::where('status', 'publish')->where('available_for_blogs', 1)->get();
        $cities = City::where('status', 'publish')->get();
        $tags = BlogTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $dropdownBlogs = Blog::where('status', 'publish')->where('id', '!=', $blog->id)->get();
        $seo = $blog->seo()->first();
        $data = compact('tours', 'categories', 'users', 'tags', 'blog', 'seo', 'countries', 'cities', 'dropdownBlogs', 'authors');

        return view('admin.blogs.blogs-management.edit')->with('title', ucfirst(strtolower($blog->title)))->with($data);
    }

    public function deleteMedia(BlogMedia $media)
    {
        if (! $media) {
            return redirect()->back()->with('notify_error', 'Media not found');
        }
        $this->deletePreviousImage($media->image_path ?? null);
        $media->delete();

        return redirect()->back()->with('notify_success', 'Media deleted successfully');
    }

    public function update(Request $request, Blog $blog)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'content_line_limit' => 'nullable',
            'status' => 'nullable|in:publish,draft',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'city_id' => 'nullable|integer|exists:cities,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:blog_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
            'json_content' => 'nullable',
            'may_also_like' => 'nullable',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['title'];
        $slug = $this->createSlug($slugText, 'blogs', $blog->slug);

        $featuredToursIds = json_encode($validatedData['featured_tours_ids'] ?? null);
        $mayAlsoLikeIds = json_encode($validatedData['may_also_like'] ?? null);
        $jsonContent = json_encode($validatedData['json_content'] ?? null);

        $featuredImage = $blog->featured_image;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $this->simpleUploadImg($request->file('featured_image'), 'Blogs/Featured-images', $blog->featured_image);
        }

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_tours_ids' => $featuredToursIds,
            'featured_image' => $featuredImage,
            'may_also_like' => $mayAlsoLikeIds,
            'json_content' => $jsonContent,
        ]);

        // Update the blog entry
        $blog->update($data);

        // Update tags
        if (! empty($validatedData['tags_ids'])) {
            $blog->tags()->sync($validatedData['tags_ids']);
        } else {
            $blog->tags()->detach();
        }

        // Handle gallery images
        if (! empty($validatedData['gallery'])) {
            $this->uploadMultipleImages(
                'gallery',
                'Blog/Gallery',
                new BlogMedia,
                'image_path',
                'alt_text',
                $validatedData['gallery_alt_texts'] ?? null,
                'blog_id',
                $blog->id
            );
        }

        handleSeoData($request, $blog, 'Blog');

        return redirect()->route('admin.blogs.edit', $blog->id)->with('notify_success', 'Blog updated successfully!');
    }
}
