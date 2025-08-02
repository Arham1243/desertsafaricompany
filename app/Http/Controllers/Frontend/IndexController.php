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
        $emails = [
            'arham404khan@gmail.com',
        ];

        $subject = "🎉 AI Mastery Bootcamp - You're In!";

        $body = <<<'HTML'
            <p>Dear Ashna Khan,</p>

            <p><strong>𝗖𝗼𝗻𝗴𝗿𝗮𝘁𝘂𝗹𝗮𝘁𝗶𝗼𝗻𝘀!</strong><br>
            You have been selected for the <strong>AI Mastery Bootcamp</strong> organized by <strong>Hiba Skills Academy</strong>.</p>

            <p>📌 <strong>Bootcamp Details:</strong><br>
            <strong>Domain:</strong> Course Name<br>
            <strong>Duration:</strong> 10th August 2025 - 12th August 2025 (3 Days)</p>

            <p>📢 <strong>Mandatory Steps:</strong><br>
            ✅ <strong>Join the Official WhatsApp Group:</strong> <a href="https://whatsapp.com/channel/0029VbB6MtADjiOYfzZVNY28" target="_blank">Click to Join</a><br>
            ✅ <strong>Join our WhatsApp Community:</strong> <a href="https://whatsapp.com/channel/0029VbB6MtADjiOYfzZVNY28" target="_blank">Click to Join</a><br>
            ✅ <strong>Like & Follow our Facebook Page:</strong> <a href="https://www.facebook.com/profile.php?id=61577976457541" target="_blank">Click to Join</a><br>
            ✅ <strong>Connect with us on LinkedIn:</strong> <a href="https://www.linkedin.com/company/hibaskillacademy/" target="_blank">Click to Join</a></p>

            <p>🎯 <strong>Your First Task</strong><br>
            We’ve shared a Welcome Poster (editable).</p>

            <p>📌 <strong>What You Need to Do:</strong></p>
            <ol>
            <li>Open the poster link (but don’t edit it directly)</li>
            <li>Go to File → Make a Copy</li>
            <li>Add your name and picture</li>
            <li>From the Share button, Download the post</li>
            <li>Post it on LinkedIn as an achievement</li>
            <li>Tag <strong>@HibaSkillsAcademy</strong> and use tags: <code>#HibaSkillsAcademy</code></li>
            </ol>

            <p>📽️ <strong>Canva Tutorial:</strong> <a href="https://drive.google.com/file/d/1p2ctW5oHb-K3GYuKrz8EJgCc8jc_pjRv/view" target="_blank">Click to Watch</a></p>

            <p>📌 Timely completion is required to receive your certificate.</p>

            <p>Best,<br>
            Ashna Khan<br>
            HR Team, Hiba Skills Academy</p>
            HTML;

        foreach ($emails as $email) {
            Mail::html($body, function ($msg) use ($email, $subject) {
                $msg->to($email)->subject($subject);
            });
        }

        return response()->json(['status' => 'Bulk emails sent']);
    }
}
