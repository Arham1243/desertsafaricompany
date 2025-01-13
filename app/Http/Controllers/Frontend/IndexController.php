<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ImageTable;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\TourReview;
use App\Traits\Sluggable;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use Sluggable;

    public function __construct()
    {
        $logo = Imagetable::where('table_name', 'logo')->latest()->first();
        View()->share('config', $this->getConfig());
        View()->share('logo', $logo);
    }

    public function blog_details()
    {
        return view('blog-details')->with('title', 'Blog Details');
    }

    public function privacy_policy()
    {
        return view('privacy-policy')->with('title', 'Privacy Policy');
    }

    public function terms_conditions()
    {
        return view('terms-conditions')->with('title', 'Terms & Conditions');
    }

    public function blog()
    {
        return view('blog')->with('title', 'Blog');
    }

    public function index()
    {
        $query = Page::where('slug', 'homepage');

        if (request()->query('viewer') !== 'admin') {
            $query->where('status', 'publish');
        }
        $page = $query->firstOrFail();
        $sections = $page->sections()->withPivot('content')->orderBy('pivot_order')->get();
        $bannerSection = $sections->filter(function ($item) {
            return $item['section_key'] === 'banner';
        })->first();
        $bannerContent = $bannerSection ? json_decode($bannerSection->pivot->content) : null;
        $reviewDetails = null;
        if ($bannerContent && isset($bannerContent->is_review_enabled) && $bannerContent->review_type !== 'custom') {
            $fetchReviewController = new FetchReviewController;
            $request = app('request');
            $reviewContent = $fetchReviewController->fetchReview($request, $type = $bannerContent->review_type);
            if ($reviewContent) {
                $reviewDetails = $reviewContent->getData(true);
            }
        }

        return view('frontend.page-builder.page', compact('page', 'sections', 'reviewDetails'));

    }

    public function cart()
    {
        return view('cart')->with('title', 'Cart');
    }

    public function checkout()
    {
        return view('checkout')->with('title', 'Checkout');
    }

    public function make_slug()
    {
        $entries = City::latest()->get();
        foreach ($entries as $entry) {
            $slug = $this->createSlug($entry['name'], 'cities');
            $entry->slug = $slug;
            $entry->save();
        }

        return 'Done';
    }

    public function wishlist()
    {
        return view('wishlist')->with('title', 'Wishlist');
    }

    public function save_newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Newsletter::create($request->all());

        return redirect()->back()->with('notify_success', 'Newsletter Signup successfully.');
    }

    public function save_review(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'tour_id' => 'required',
            'title' => 'required|string|max:255',
            'review' => 'required|string',
            'rating' => 'required',
        ]);
        $review = TourReview::create($validated);

        return back()->with('notify_success', 'Review Pending For Admin Approval!');
    }
}
