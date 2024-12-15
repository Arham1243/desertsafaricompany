<?php

namespace App\Http\Controllers\Frontend\Tour;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourFaq;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        return view('frontend.tour.index')
            ->with('title', 'Top Tours');
    }

    public function details($slug)
    {

        $tour = Tour::where('slug', $slug)->first();
        $tourFaqs = TourFaq::where('tour_id', $tour->id)->get();

        if ($tour) {
            $data = compact('tour', 'tourFaqs');

            return view('frontend.tour.details')->with('title', $tour->title)->with($data);
        }

        return redirect()->back()->with('notify_error', 'Page Not Available');
    }

    public function search(Request $request)
    {
        $resourceId = $request->input('resource_id');
        $resourceType = $request->input('resource_type');
        $tours = collect();
        $resourceName = '';

        switch ($resourceType) {
            case 'city':
                $city = City::find($resourceId);
                if ($city) {
                    $tours = $city->tours()->where('status', 'publish')->get();
                    $resourceName = $city->name;
                }
                break;
            case 'country':
                $country = Country::find($resourceId);
                if ($country) {
                    $tours = Tour::whereHas('cities', function ($query) use ($country) {
                        $query->where('country_id', $country->id);
                    })->where('status', 'publish')->get();
                    $resourceName = $country->name;
                }
                break;

            case 'category':
                $category = TourCategory::find($resourceId);
                if ($category) {
                    $tours = $category->tours()->where('status', 'publish')->get();
                    $resourceName = $category->name;
                }
                break;

            default:
                $tours = collect();
                break;
        }

        return view('frontend.tour.search-results', compact('tours', 'resourceType', 'resourceName'))
            ->with('title', 'Tour Search Results');
    }
}
