<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Models\TourReview;
use App\Models\Order;
use App\Traits\Sluggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    use Sluggable;

    public function login()
    {
        return redirect()->route('frontend.index');
    }

    public function index()
    {

        $settings = Setting::where('group', 'general')->pluck('value', 'key');
        $homepage = $settings->get('page_for_homepage');
        if (request()->is('/') && ! $homepage) {
            return 'No page selected as homepage. Please select a page from Settings > General Settings > Page for Homepage.';
        }
        $query = Page::find($homepage);
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

    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'nullable',
            'dob' => 'nullable|date',
            'country' => 'nullable|string',
            'city' => 'nullable|string|max:255',
        ]);

        User::where('id', Auth::user()->id)->update($validatedData);

        return back()->with('notify_success', 'Profile updated successfully');
    }

    public function testEmail($id)
    {
        $settings = Setting::pluck('value', 'key');
        $order = Order::findOrFail($id);

        $user = auth()->user() ?? (object) [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
        ];

        $orderRequestData = json_decode($order->request_data ?? '{}');
        $cart = json_decode($order->cart_data, true) ?? [];

        $headerLogo = $settings->get('header_logo') ?? 'admin/assets/images/placeholder-logo.png';

        $data = [
            'settings' => $settings,
            'order_id' => $order->id,
            'customer_name' => $user->full_name,
            'customer_email' => $user->email,
            'customer_phone' => ($orderRequestData->phone_dial_code ?? '+971') . ($orderRequestData->phone_number ?? ''),
            'payment_type' => $order->payment_type,
            'advance_amount' => $order->advance_amount,
            'cart' => $cart,
            'total' => $cart['total_price'] ?? 0,
            'tours' => $cart['tours'] ?? [],
            'logo' => asset($headerLogo),
            'order_link' => url('/admin/orders/' . $order->id),
        ];

        return view('emails.customer-order-success', compact('data'));
    }
}
