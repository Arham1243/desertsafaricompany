<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Setting;
use App\Models\TourReview;
use App\Traits\Sluggable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    use Sluggable;

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

    public function send_bulk_email(Request $request)
    {
        $recipients = [
            [
                'email' => 'arham404khan@gmail.com',
                'name' => 'Arham Khan',
                'course' => 'MERN Stack Development',
                'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
            ],
            [
                'email' => 'ashnak151@gmail.com',
                'name' => 'Ashna Khan',
                'course' => 'AI Mastery Bootcamp',
                'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
            ],
            [
                'email' => 'safiahaider650@gmail.com',
                'name' => 'Safia Haider',
                'course' => 'UI/UX Design Fundamentals',
                'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
            ],
        ];

        foreach ($recipients as $data) {
            $subject = "You're Selected: Join the {$data['course']} Bootcamp by Hiba Skills Academy";
            try {
                Mail::send('emails.welcome-email', [
                    'name' => $data['name'],
                    'course' => $data['course'],
                    'whatsapp' => $data['whatsapp'],
                ], function ($message) use ($data, $subject) {
                    $message->to($data['email'])->subject($subject);
                });
            } catch (\Exception $e) {
                Log::error("Mail to {$data['email']} failed: ".$e->getMessage());
            }
        }

        return response()->json(['status' => 'Bulk emails sent']);
    }
}
