<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Tour;
use Stevebauman\Location\Facades\Location;

class BlogController extends Controller
{
    public function index()
    {
        $defaultCountry = $this->resolveCountry();

        $countries = Country::where('status', 'publish')->where('available_for_blogs', 1)->get();
        $cities = City::where('status', 'publish')->get();
        $categories = BlogCategory::get();

        $blogs = $this
            ->filterBlogs($defaultCountry)
            ->paginate(30)
            ->appends(request()->query());

        $data = compact('countries', 'cities', 'categories', 'defaultCountry', 'blogs');

        return view('frontend.blogs.index')->with('title', 'Blogs')->with($data);
    }

    protected function resolveCountry()
    {
        $countryParam = request('country');
        if ($countryParam) {
            return Country::where('status', 'publish')
                ->where('available_for_blogs', 1)
                ->where('iso_alpha2', strtolower($countryParam))
                ->first();
        }

        $ip = request()->ip();
        if ($ip === '127.0.0.1') {
            $ip = '8.8.8.8';
        }

        $location = Location::get($ip);
        $countryCode = strtolower($location?->countryCode ?? 'ae');

        $country = Country::where('status', 'publish')
            ->where('available_for_blogs', 1)
            ->where('iso_alpha2', $countryCode)
            ->first();

        return $country ?: Country::where('status', 'publish')
            ->where('iso_alpha2', 'ae')
            ->first();
    }

    protected function filterBlogs($defaultCountry)
    {
        $query = Blog::where('status', 'publish');

        if ($city = request('city')) {
            $query->where('city_id', $city);
        }

        if ($category = request('category')) {
            $query->where('category_id', $category);
        }

        if (! request()->has('city') && ! request()->has('category')) {
            $cityIds = $defaultCountry->cities()->pluck('id');
            $query->whereIn('city_id', $cityIds);
        }

        if ($sort = request('sort')) {
            switch ($sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'a_to_z':
                    $query->orderBy('title', 'asc');
                    break;
                case 'z_to_a':
                    $query->orderBy('title', 'desc');
                    break;
            }
        }

        return $query;
    }

    public function show($country, $city, $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->whereHas('city', fn ($q) => $q->where('slug', $city)->whereHas('country', fn ($q2) => $q2->where('iso_alpha2', $country)))
            ->firstOrFail();

        $relatedBlogs = $blog->category
            ? $blog->category->blogs()->where('status', 'publish')->where('id', '!=', $blog->id)->take(5)->get()
            : collect();

        $tours = Tour::where('status', 'publish')->get();

        return view('frontend.blogs.details')
            ->with('title', ucfirst($blog->title))
            ->with(compact('blog', 'relatedBlogs', 'tours'));
    }
}
