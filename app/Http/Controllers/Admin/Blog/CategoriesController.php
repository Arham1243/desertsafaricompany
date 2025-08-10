<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Traits\Sluggable;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use Sluggable;
    use UploadImageTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = BlogCategory::whereNull('parent_category_id')
            ->orderBy('name', 'asc')
            ->get();

        $dropdownCategories = BlogCategory::get();

        $data = compact('categories', 'dropdownCategories');

        return view('admin.blogs.categories.main')->with('title', 'Blogs Categories')->with($data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|min:3|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_category_id' => 'nullable|exists:blog_categories,id',
        ]);

        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'blog_categories');
        $data = array_merge($validatedData, ['slug' => $slug]);

        BlogCategory::create($data);

        return redirect()->route('admin.blogs-categories.index')->with('notify_success', 'Category Added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        $dropdownCategories = BlogCategory::whereNotIn('id', [$id])->get();
        $seo = $category->seo()->first();
        $data = compact('category', 'dropdownCategories', 'seo');

        return view('admin.blogs.categories.edit')->with('title', ucfirst(strtolower($category->name)))->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_category_id' => 'nullable|exists:blog_categories,id',
        ]);

        $category = BlogCategory::findOrFail($id);
        $slugText = $validatedData['slug'] != '' ? $validatedData['slug'] : $validatedData['name'];
        $slug = $this->createSlug($slugText, 'blog_categories', $category->slug);

        $data = array_merge($validatedData, ['slug' => $slug]);
        $category->update($data);

        handleSeoData($request, $category, 'Blog-Categories');

        return redirect()->route('admin.blogs-categories.index')->with('notify_success', 'Category updated successfully.');
    }
}
