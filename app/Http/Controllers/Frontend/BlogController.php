<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogReaction;
use App\Models\City;
use App\Models\Country;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourAuthor;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class BlogController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        if (! ($settings->get('is_blogs_listing_enabled') && (int) $settings->get('is_blogs_listing_enabled') === 1)) {
            abort(404);
        }

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
        $userIp = request()->ip();

        $reaction = BlogReaction::where('blog_id', $blog->id)
            ->where('ip_address', $userIp)
            ->first()
            ?->reaction;

        $authors = TourAuthor::where('status', 'active')->get();
        $allBlogs = Blog::where('status', 'publish')->where('id', '!=', $blog->id)->get();

        $tours = Tour::where('status', 'publish')->get();

        return view('frontend.blogs.details')
            ->with('title', ucfirst($blog->title))
            ->with(compact('blog', 'allBlogs', 'tours', 'authors', 'reaction'));
    }

    public function saveReaction(Request $request, Blog $blog)
    {
        $request->validate([
            'reaction' => 'required|in:like,dislike',
        ]);

        $ip = $request->ip();

        BlogReaction::updateOrCreate(
            ['blog_id' => $blog->id, 'ip_address' => $ip],
            ['reaction' => $request->reaction]
        );

        return response()->json(['success' => true]);
    }
}
