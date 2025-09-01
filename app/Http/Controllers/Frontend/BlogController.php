<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tour;

class BlogController extends Controller
{
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $relatedBlogs = $blog->category->blogs()->where('status', 'publish')->where('id', '!=', $blog->id)->take(5)->get();
        $tours = Tour::where('status', 'publish')->get();
        $data = compact('blog', 'relatedBlogs', 'tours');

        return view('frontend.blogs.details')->with('title', ucfirst($blog->title))->with($data);
    }
}
