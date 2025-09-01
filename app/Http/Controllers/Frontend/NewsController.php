<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Tour;

class NewsController extends Controller
{
    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $relatedNews = $news->category->news()->where('status', 'publish')->where('id', '!=', $news->id)->take(5)->get();
        $tours = Tour::where('status', 'publish')->get();
        $data = compact('news', 'relatedNews', 'tours');

        return view('frontend.news.details')->with('title', ucfirst($news->title))->with($data);
    }
}
