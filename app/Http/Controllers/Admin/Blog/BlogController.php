<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogMedia;
use App\Models\BlogTag;
use App\Models\Tour;
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
        $categories = BlogCategory::where('is_active', 1)->get();
        $tags = BlogTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $data = compact('tours', 'categories', 'users', 'tags');

        return view('admin.blogs.blogs-management.add')->with('title', 'Add New Blog')->with($data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable',
            'top_highlighted_tour_id' => 'nullable|integer|exists:tours,id',
            'featured_tours_ids' => 'array|max:4',
            'featured_tours_ids.*' => 'nullable|integer|exists:tours,id',
            'status' => 'nullable|in:publish,draft',
            'user_id' => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:blog_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'gallery_alt_texts' => 'array',
            'gallery_alt_texts.*' => 'string|max:255',
        ]);

        $slug = $this->createSlug($validatedData['title'], 'blogs');

        $featuredToursIds = json_encode($validatedData['featured_tours_ids'] ?? null);

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_tours_ids' => $featuredToursIds,
        ]);

        $blog = Blog::create($data);

        if (! empty($validatedData['tags_ids'])) {
            $blog->tags()->attach($validatedData['tags_ids']);
        }

        $this->uploadImg('featured_image', 'Blog/Featured-images', $blog, 'featured_image');

        if (! empty($validatedData['gallery'])) {
            $this->uploadMultipleImages(
                'gallery', // Input name for the images
                'Blog/Gallery', // Folder to store images
                new BlogMedia, // Pass the model class name here
                'image_path', // Column name for image path
                'alt_text', // Column name for alt text
                $validatedData['gallery_alt_texts'] ?? null, // Pass alt texts if provided
                'blog_id', // Pass the foreign key column name
                $blog->id // Pass the blog_id as the foreign key value
            );
        }

        handleSeoData($request, $blog, 'Blog');

        return redirect()->route('admin.blogs.index')->with('notify_success', 'Blog Added successfully!');
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
        $tags = BlogTag::where('is_active', 1)->get();
        $users = User::where('is_active', 1)->get();
        $seo = $blog->seo()->first();
        $data = compact('tours', 'categories', 'users', 'tags', 'blog', 'seo');

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
            'title' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable',
            'top_highlighted_tour_id' => 'nullable|integer|exists:tours,id',
            'featured_tours_ids' => 'array|max:4',
            'featured_tours_ids.*' => 'nullable|integer|exists:tours,id',
            'status' => 'nullable|in:publish,draft',
            'user_id' => 'nullable|integer|exists:users,id',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'tags_ids' => 'array',
            'tags_ids.*' => 'integer|exists:blog_tags,id',
            'featured_image' => 'nullable|image',
            'feature_image_alt_text' => 'nullable|string|max:255',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:2048',
            'gallery_alt_texts' => 'array',
            'gallery_alt_texts.*' => 'string|max:255',
            'deleted_gallery_ids' => 'array',
            'deleted_gallery_ids.*' => 'integer|exists:blog_media,id',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['title'];
        $slug = $this->createSlug($slugText, 'blogs', $blog->slug);

        $featuredToursIds = json_encode($validatedData['featured_tours_ids'] ?? null);

        $data = array_merge($validatedData, [
            'slug' => $slug,
            'featured_tours_ids' => $featuredToursIds,
        ]);

        // Update the blog entry
        $blog->update($data);

        // Update tags
        if (! empty($validatedData['tags_ids'])) {
            $blog->tags()->sync($validatedData['tags_ids']);
        } else {
            $blog->tags()->detach();
        }
        $this->uploadImg('featured_image', 'Blog/Featured-images', $blog, 'featured_image');

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

        return redirect()->route('admin.blogs.index')->with('notify_success', 'Blog updated successfully!');
    }
}
