<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
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
        // $recipients = [
        //     [
        //         'email' => 'israrmir606@gmail.com',
        //         'name' => 'Israr Hussain',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdulrafay13737@gmail.com',
        //         'name' => 'Abdul Rafay ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jawadjabar07@gmail.com',
        //         'name' => 'Jawad jabar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nidaarshad120@gmail.com',
        //         'name' => 'Nida Arshad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'engalihyder@gmail.com',
        //         'name' => 'Ali Hyder',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm12984471@gmail.com',
        //         'name' => 'Mubashir Manzoor ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmadsaeed7440@gmail.com',
        //         'name' => 'Saeed Ahmad',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shaikhahmedfaraz64@gmail.com',
        //         'name' => 'Ahmed Faraz Shaikh',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abasit8857@gmail.com',
        //         'name' => 'Abdul Basit',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Hinatanveer097@gmail.com',
        //         'name' => 'Hina Tanveer',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ayeshakhan21nov@gmail.com',
        //         'name' => 'Aisha Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdulraheem120602@gmail.com',
        //         'name' => 'Abdul Raheem Shahzad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jawadirfan756@gmail.com',
        //         'name' => 'Jawad Irfan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mehak5814@gmail.com',
        //         'name' => 'Mehak Zahra ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sheikhmehrali5@gmail.com',
        //         'name' => 'Mehr Ali ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mubashirrao0612@gmail.com',
        //         'name' => 'Mubashir Ahmad Saeed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'uzairkheshgi0@gmail.com',
        //         'name' => 'Uzairkhan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sarimsaleem01@gmail.com',
        //         'name' => 'MUHAMMAD SARIM SALEEM',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'asadkhans2310861@gmail.com',
        //         'name' => 'Muhammad Asad khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syed.hassan2003@gmail.com',
        //         'name' => 'Hyder Hassan ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Hostel 11 uet Peshawar ',
        //         'name' => 'Nouman khalid',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mediqadri1166@gmail.com',
        //         'name' => 'Muhammad Hammad',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ali.73shan@gmail.com',
        //         'name' => 'Ali Shan ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aimanayyaz753@gmail.com',
        //         'name' => 'Aiman Ayyaz',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'raomateen2851@gmail.com',
        //         'name' => 'Mateen Ahmad Saeed ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saad.zahid.810@gmail.com',
        //         'name' => 'Muhammad Saad Zahid ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'murtaza0123official@gmail.com',
        //         'name' => 'M.Murtaza',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'developer15246@gmail.com',
        //         'name' => 'Muhammad Fayaz',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mohsinafridi22150@gmail.com',
        //         'name' => 'Mohsin khan ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alisaqibh1@gmail.com',
        //         'name' => 'Saqib Ali ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'afaq97218@gmail.com',
        //         'name' => 'Afaq Ahmad Khan ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'anasmustafa464@gmail.com',
        //         'name' => 'Anas Mustafa ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mehak5814@gmail.com',
        //         'name' => 'Mehak zahra ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ayeshakhan21nov@gmail.com',
        //         'name' => 'Aisha khan',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saboortahir453@gmail.com',
        //         'name' => 'Saboor Tahir',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'medimughal07@gmail.com',
        //         'name' => 'Muhammad Hammad',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Yaseen ',
        //         'name' => 'Talha ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => '0333 5997325 ',
        //         'name' => 'Hadiya Khokhar ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'musaddiqamahmood0t7@gmail.com',
        //         'name' => 'Musaddiqa Mahmood ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmadumer2003@gmail.com',
        //         'name' => 'Ahmad Umer',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadjamal1144@gmail.com',
        //         'name' => 'Muhammad Jamal ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'kashafshk306@gmail.com',
        //         'name' => 'KASHAF ALI',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saadhafeez612@gmail.com',
        //         'name' => 'Saad Hafeez',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedu3312@gmail.com',
        //         'name' => 'syed umar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hafizmustafatoor@gmail.com',
        //         'name' => 'Mustafa Ahmed Toor ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jazeelahmad55@gmail.com',
        //         'name' => 'Jazeel Ahmad',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fazaian2004@gmail.com',
        //         'name' => 'Kiran Fatima',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadkhursheed2022@gmail.com',
        //         'name' => 'Muhammad Khursheed ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ibadrahman72342@gmail.com',
        //         'name' => 'Ibad Ur Rahman',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'arfawaqar702@gmail.com',
        //         'name' => 'Arfa Waqar',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nazarfarid793@gmail.com',
        //         'name' => 'Nazar Farid ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'usmansyed.peshawar@gmail.com',
        //         'name' => 'Muhammad Usman',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saadbinwaqastoor@gmail.com',
        //         'name' => 'Saad Bin Waqas',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'msaadjunaid2000@gmail.com',
        //         'name' => 'Muhammed Saad Junaid ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zakirkhushik110@gmail.com',
        //         'name' => 'Zakir Hussain ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sajjadhuzaifa225@gmail.com',
        //         'name' => 'Muhammad Huzaifa sajjad ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Zunairakashaf2005@gmail.com',
        //         'name' => 'Zunaira kashaf zain ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ibadk304@gmail.com',
        //         'name' => 'IBAD ULLAH ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ridayousafzai6@gmail.com ',
        //         'name' => 'Rida Gul ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sarahmumtaz357@gmail.com',
        //         'name' => 'Sarah Mumtaz ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Khanzalan1122@gmail.com ',
        //         'name' => 'Zalan khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aqsatariq786ad@gmail.com',
        //         'name' => 'Aqsa Tariq ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'warishabilal05@gmail.com',
        //         'name' => 'Warishabilal05@gmail.com',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sulemanmajeed0503@gmail.com',
        //         'name' => 'Suleman Majeed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmadshahzadafridi56@gmail.com',
        //         'name' => 'Ahmad shahzad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadumairr13@gmail.com',
        //         'name' => 'Muhammad Umair Raza ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Inshalsaqibsiddiqui@gmail.com',
        //         'name' => 'Muhammad Inshal Saqib Siddiqui ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alibsit42@gmail.com',
        //         'name' => 'Ali Ishtiaq ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdullahmuhammad822@gmail.com',
        //         'name' => 'Muhammad Abdullah ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'burhan.abbasi@gmail.com',
        //         'name' => 'Daneen Batool',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '23pwcse2247@uetpeshawar.edu.pk',
        //         'name' => 'Talal Azhar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdulahadicp221138@gmail.com',
        //         'name' => 'Abdul Ahad',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shanzafatima61@gmail.com',
        //         'name' => 'Shanza Fatima ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sm8304520@gmail.com',
        //         'name' => 'Saima Bibi ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mohammadinam1068@gmail.com',
        //         'name' => 'Mohammad Inam ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hafeezquantum@gmail.com',
        //         'name' => 'Hafeez ahmad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sajjadahmad5578@gmail.com',
        //         'name' => 'Sajjad Ahmad',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'inaveed0003@gmail.com',
        //         'name' => 'Muhammad Naveed',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ru94499@gmail.com',
        //         'name' => 'Rahmatullah',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadasad58889@gmail.com',
        //         'name' => 'Muhammad Asad ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'omaimamunib@gmail.com',
        //         'name' => 'Syeda Omaima Munib',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmadkhanraj01@gmail.com',
        //         'name' => 'Muhammad Ahmad khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mohdhaseb2004@gmail.com',
        //         'name' => 'Muhammad Haseeb ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'farhadkhan96622@gmail.com',
        //         'name' => 'Farhad Khan',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdull16102004@gmail.com',
        //         'name' => 'Abdullah Basharat',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zoham432@gmail.com',
        //         'name' => 'Zoha Memon',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alyansameer01@gmail.com',
        //         'name' => 'Alyan Sameer ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aliahmdian87@gmail.com ',
        //         'name' => 'Waqas Ahmed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mairazubair98@gmail.com',
        //         'name' => 'Maira Zubair ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umerk9957@gmail.com',
        //         'name' => 'Umer Bi Mukaram ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nasiknehal08@gmail.com',
        //         'name' => 'Nasik Nehal Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'www.elenakhan34@gmail.com',
        //         'name' => 'Aleena Aiman ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Hamzaarif3463@gmail.com',
        //         'name' => 'Hamza Arif',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'computerscientist285196@gmail.com',
        //         'name' => 'Mateen Ahmad ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Talhamir3367@gmail.com',
        //         'name' => 'Talha khan khushmir',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdulmananghaffari@gmail.com',
        //         'name' => 'Abdul manan ghaffari ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hamzashakeel12980@gmail.com',
        //         'name' => 'Hamza Shakeel',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saadahmad2005khan@gmail.com',
        //         'name' => 'Saad Ahmad ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hooriashakeel973@gmail.com',
        //         'name' => 'Hooria',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedahmadalishah39@gmail.com',
        //         'name' => 'Syed Ahmad Ali Shah',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'yaqoobmsd2514@gmail.com',
        //         'name' => 'Muhammad Yaqoob',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khankhanareeba5363@gmail.com',
        //         'name' => 'Areeba Khan',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aminarazzaq72@gmail.com',
        //         'name' => 'Amina',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fatimafarooq1909@gmail.com',
        //         'name' => 'Fatima Farooq ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fahadwaqar706@gmail.com',
        //         'name' => 'Fahad Waqar ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Sadafmaratab24@gmail.com ',
        //         'name' => 'Sadaf Maratab',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadwasiukhtoon@gmail.com',
        //         'name' => 'Muhammad Wasim',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'danishicp99@gmail.com',
        //         'name' => 'Danish Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saifsaqi2565@gmail.com',
        //         'name' => 'Hafiz Saif Ullah Saqib ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '24pwind0847@uetpeshawar.edu.pk',
        //         'name' => 'Muhammad wesal Tariq ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ayeshamalikarif1@gmail.com',
        //         'name' => 'Ayesha Arif ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jawadahmed220842@gmail.com',
        //         'name' => 'Jawad Ahmed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hazratbilal20458@gmail.com',
        //         'name' => 'Hazrat Bilal ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'rbagum675@gmail.com',
        //         'name' => 'Lubaba basri',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'yahoo89751@gmail.com ',
        //         'name' => 'Wajid ali',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zahidjanarmy@gmail.com',
        //         'name' => 'Zahid Jan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '24pwcse2402@uetpeshawar.edu.pk',
        //         'name' => 'Abbas Khan',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'habibaabbasi11@gmail.com',
        //         'name' => 'Um e Habiba ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => '24pwbcs1208@uetpeshawar.edu.pk',
        //         'name' => 'Yaseen Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zahidhameed322578@gmail.com',
        //         'name' => 'Zahid Hameed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umairhamza056@gmail.com',
        //         'name' => 'Umair Hamza ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sadiakhann413@gmail.com',
        //         'name' => 'sadia khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shahab19616@abasyn.edu.pk',
        //         'name' => 'Muhammad Shahab ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umairhabib0306@gmail.com',
        //         'name' => 'Muhammad Umair Habib',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Siddiqui26rafayus@gmail.com',
        //         'name' => 'Abdul Rafay Siddiqui',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umairhabib0306@gmail.com',
        //         'name' => 'Muhammad Umair Habib',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm.umair.habib.pro@gmail.com',
        //         'name' => 'Muhammad Umair Habib',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fazalurrehman564@gmail.com',
        //         'name' => 'Fazal Ur Rehman ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shahkar99khan@gmail.com',
        //         'name' => 'Muhammad Shahkar Khan',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'afaqahmadshewa1234@gmail.com',
        //         'name' => 'Afaq Ahmad ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hasnainswati2004@gmail.com',
        //         'name' => 'Hasnain ahmad ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'taha91555@gmail.com',
        //         'name' => 'Muhammad Haris Ali',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'iqrarani1204@gmail.com',
        //         'name' => 'Iqra Rani ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammad.ahmad318@outlook.com',
        //         'name' => 'Muhammad Ahmad',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mzaydmalik@gmail.com',
        //         'name' => 'M. Zayd Dawood ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm.saleemyameen.91@gmail.com',
        //         'name' => 'Muhammad Saleem',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mughalsrkar2003@gmail.com',
        //         'name' => 'Mukhbit ilahi',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'na7iralee1122@gmail.com',
        //         'name' => 'Nasir Ali ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'kumailaskari22@gmail.com',
        //         'name' => 'Kumail Fiaz',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mzaydmalik@gmail.com',
        //         'name' => 'M. Zayd Dawood ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'momeeza18@gmail.com',
        //         'name' => 'Momeeza',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadibad759@gmail.com',
        //         'name' => 'Muhammad Ibad Malik ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'groupbest12345@gmail.com',
        //         'name' => 'Waleed Abulkhair ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mzaydmalik@gmail.com',
        //         'name' => 'M. Zayd Dawood ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mahnoorahmed928@gmail.com',
        //         'name' => 'Mahnoor ahmad',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saeedsania429@gmail.com',
        //         'name' => 'Sania saeed ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'eshamukhtar09@gmail.com',
        //         'name' => 'Esha Mukhtar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mateenahmed778@gmail.com',
        //         'name' => 'Mateen Ahmed Shah',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'anibaamjad017@gmail.com',
        //         'name' => 'Aniba Amjad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Zaighamm186@gmail.com ',
        //         'name' => 'Muhammad Zaigham ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedusk.851@gmail.com',
        //         'name' => 'Syed sohaib hassan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aaima355321@gmail.com',
        //         'name' => 'Aima Nadeem',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'awaisnoor418@gmail.com',
        //         'name' => 'Awais noor',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'madimughal59@gmail.com',
        //         'name' => 'Madiha',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'uroojshoaib999@gmail.com',
        //         'name' => 'Urooj Shoaib ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khalil.rehman0807@gmail.com',
        //         'name' => 'Khalil ur rehman',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fidakhattak19@gmail.com',
        //         'name' => 'Muhammad Fida ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abrargondal640@gmail.com',
        //         'name' => 'Muhammad Abrar Hussain Nawaz Gondal ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahsan.bscs5007@student.iiu.edu.pk',
        //         'name' => 'Ahsan Fayyaz ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Dheri Sikandarpur Mohalla Bagh Wala house 009',
        //         'name' => 'Aasiya ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aftabmohi@gmail.com',
        //         'name' => 'Aftab Mohiuddin',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aleenabutt183@gmail.com',
        //         'name' => 'Aleena Bashir ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => '08iftikhar@gmail.com',
        //         'name' => 'Iftikhar Ali ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'iamhassan235@gmail.com',
        //         'name' => 'Hassan Ali',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'arwazulkarnain@gmail.com',
        //         'name' => 'ARWA Zulkarnain ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'stillblack2014@gmail.com',
        //         'name' => 'Ejaz Ali ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'lekhrajkalani81@gmail.com',
        //         'name' => 'Lekhraj Kalani ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'arwazulkarnain@gmail.com',
        //         'name' => 'Arwa Zulkarnain ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadammarbcs@gmail.com',
        //         'name' => 'Muhammad Ammar ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadammarbcs@gmail.com',
        //         'name' => 'Muhammad Ammar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umairhabib0306@gmail.com',
        //         'name' => 'Muhammad Umair Habib',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm.umair.habib.pro@gmail.com',
        //         'name' => 'Muhammad Umair Habib',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sufaidsung234@gmail.com',
        //         'name' => 'Nizam ullah ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'palwashakh988@gmail.com',
        //         'name' => 'Zupash Zulfiqar',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'malikareeb369@gmail.com',
        //         'name' => 'Areeb Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'palwashakh988@gmail.com',
        //         'name' => 'Zupash Zulfiqar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ranaabdullahnaeem007@gmail.com',
        //         'name' => 'Rana Abdullah ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'bilalsaab385@gmail.com',
        //         'name' => 'Muhammad Bilal',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wardagakkhar353@gmail.com',
        //         'name' => 'Warda Riaz ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'urwaurwa97@gmail.com',
        //         'name' => 'Urwa kanwal',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'areejnasim94@gmail.com',
        //         'name' => 'Areej Nasim ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'awaiskhn0364@gmail.com',
        //         'name' => 'Muhammad Awais',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'makhdoomhussainpro@gmail.com',
        //         'name' => 'Makhdoom Hassan Abbas',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'rajamuhammadmakhdoomhussain@gmail.com',
        //         'name' => 'Raja Muhammad Makhdoom Hussain',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mtauseefanjum0@gmail.com',
        //         'name' => 'Muhammad Tauseef Anjum ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammad.yousafahmad22@gmail.com',
        //         'name' => 'Muhammad yousaf ahmad',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadhashim200229@gmail.com',
        //         'name' => 'Muhammad Hashim ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wahabejaz.binary@gmail.com',
        //         'name' => 'Wahab Ejaz',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'raatketotay@gmail.com',
        //         'name' => 'SAADULLAH ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm.a.h.works17@gmail.com',
        //         'name' => 'Muhammad Ali Hassan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abubakarzafar940@gmail.com',
        //         'name' => 'Muhammad Abubakar Zafar ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'code.with.hamxa@gmail.com',
        //         'name' => 'Muhammad Hamza Javed',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hk0551154@gmail.com',
        //         'name' => 'Hamdia khan',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadharissss4455@gmail.com',
        //         'name' => 'Muhammad Haris ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'AhmedAsif6349@gmail.com',
        //         'name' => 'Ahmed Asif ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'immohsinali1999@gmail.com',
        //         'name' => 'Muhsin Ali ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'habibharoon2001@gmail.com',
        //         'name' => 'Muhammad Habib Haroon ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'AhmedAsif6349@gmail.com',
        //         'name' => 'Ahmed Asif ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'AhmedAsif6349@gmail.com',
        //         'name' => 'Ahmed Asif ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'AhmedAsif6349@gmail.com',
        //         'name' => 'Ahmed Asif ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'noorulwara2701@gmail.com',
        //         'name' => 'Noor ul wara ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'uzairahmedua118846@gmail.com',
        //         'name' => 'Uzair Ahmed ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Haiderijaz529@gmail.com ',
        //         'name' => 'Muhammad Haider Ijaz ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Amin.398@hotmail.com',
        //         'name' => 'Fazle Amin',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'um518917@gmail.com',
        //         'name' => 'Uzair Hassan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'btchashir123@gmail.com',
        //         'name' => 'Hashir raza',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hamzanadeem3439@gmail.com',
        //         'name' => 'Hamza Nadeem',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'irfanhaiderattash@gmail.com',
        //         'name' => 'Irfan Haider ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alisaidkhan59@gmail.com',
        //         'name' => 'Ali Said',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haseebmir190@gmail.com',
        //         'name' => 'Haseeb Pervaiz',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khan203221012@gmail.com',
        //         'name' => 'Mahnoor khan swati',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'trojanhorsegamma@gmail.com',
        //         'name' => 'Hammad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sparklebloom512@gmail.com',
        //         'name' => 'FIZZA JABEEN ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sharifullah7087@gmail.com',
        //         'name' => 'Sharif Ullah ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'malikmuhammadali0034@gmail.com',
        //         'name' => 'Malik Muhammad Ali',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sanaqayyum444@gmail.com',
        //         'name' => 'Sana Qayyum',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hannanpro24689@gmail.com',
        //         'name' => 'Abdul Hannan',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sawantee30@gmail.com',
        //         'name' => 'Sawante bibi',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ayeshasaed17@gmail.com',
        //         'name' => 'Ayesha Saeed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'talhashakeel960@gmail.com',
        //         'name' => 'Muhammad Talha Shakeel ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zaanibkhaan@gmail.com',
        //         'name' => 'zanib bibi',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wardasaeed312@gmail.com',
        //         'name' => 'Warda saeed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aliraza445059@gmail.com',
        //         'name' => 'Ali Raza',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zali40868@gmail.com',
        //         'name' => 'Muhammad Awais',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khadijamujeeb888@gmail.com',
        //         'name' => 'Khadija Mujeeb ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muaarij.devforge@gmail.com',
        //         'name' => 'Syed Muaarij Nadeem',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fa21-bce-008@cuiatk.edu.pk',
        //         'name' => 'Abdullah Asif',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fahadwahid214@gmail.com',
        //         'name' => 'Fahad wahid ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedmujtba099@gmail.com',
        //         'name' => 'Syed Muhammad Mujtaba Naqvi ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Adnankhaandir2233@gmail.com',
        //         'name' => 'Adnan khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'foziabano204@gmail.com',
        //         'name' => 'Fozia Bano',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umairazhar850@gmail.com',
        //         'name' => 'Umair Azhar',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Emanmudasir19@gmail.com',
        //         'name' => 'Eman mudasir',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abuzarsafdar152@gmail.com ',
        //         'name' => 'Abuzar Safdar ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nomiemerge9901@gmail.com',
        //         'name' => 'Noman shakir ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haroonurrashid877@gmail.com',
        //         'name' => 'Haroon Ur Rashid ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haroonurrashid877@gmail.com',
        //         'name' => 'Haroon Ur Rashid ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haroonurrashid877@gmail.com',
        //         'name' => 'Haroon Ur Rashid ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'anilahayat2006@gmail.com',
        //         'name' => 'Anila Bano ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'amnamv777@gmail.com',
        //         'name' => 'Bibi Aamina ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadibrahimdoaba@gmail.com',
        //         'name' => 'Muhammad Ibrahim',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'm. ishaqkhan0308@gmail.com',
        //         'name' => 'Muhammad Ishaq ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'bash23013@gmail.com',
        //         'name' => 'Abdul Basit ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'taskeenmustafanizamani.bscsf22@iba-suk.edu.pk',
        //         'name' => 'Taskeen Mustafa',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmed.jahenzaib123@gmail.com',
        //         'name' => 'Ahmed Jahenzaib ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'amnaaurangzeb95@gmail.com',
        //         'name' => 'Amna',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'bash23013@gmail.com',
        //         'name' => 'Abdul Basit ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ubaid01.qureshi@gmail.com',
        //         'name' => 'Obaid Qureshi ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'chandlayyah@gmail.com',
        //         'name' => 'Malik Imran Ali ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ummara1411@gmail.com',
        //         'name' => 'Ummara Aasim',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'M.ibniayub1001@gmail.com',
        //         'name' => 'Mian Ubaid Ullah ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'uzairawan5577@gmail.com',
        //         'name' => 'Uzair Arif ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmedkhan81x@gmail.com',
        //         'name' => 'Ahmed Khan',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mujtabaalee92@gmail.com',
        //         'name' => 'Mujtaba Ali ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nimramano2324@gmail.com',
        //         'name' => 'Nimra Bibi',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Nazishfatima899@gmail.com ',
        //         'name' => 'Nazish Fatima ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => '21pwcse2032@uetpeshawar.edu.pk',
        //         'name' => 'M Zubair Qazi',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mehakgordhandas@gmail.com',
        //         'name' => 'Mehak Gordhan Das',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'salimali121472@gmail.com',
        //         'name' => 'Salim Ali',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sirhammadsaeed@gmail.com',
        //         'name' => 'Hammad Saeed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jabeenaleeza2004@gmail.com',
        //         'name' => 'Aleeza jabeen ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'anisasma195@gmail.com',
        //         'name' => 'Asma Anees',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Zainabfarrukh251@gmail.con',
        //         'name' => 'Zainab',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadalishoaibrana@gmail.com',
        //         'name' => 'Muhammad Ali Haider Rana ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zohafatima251pk@gmail.com',
        //         'name' => 'Zoha Fatima ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedasumayyahzahoor@gmail.com',
        //         'name' => 'Syeda Sumayyah Zahoor ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aanassiddiqui21@gmail.com',
        //         'name' => 'Muhammad Anas Siddiqui ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'asadullahafzaal123@gmail.com',
        //         'name' => 'Asad Ullah Afzaal ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muneerkunbhar351@gmail.com',
        //         'name' => 'Muneer',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadhasnainali0320@gmail.com',
        //         'name' => 'Muhammad Hasnain Ali',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mbabar9204@gmail.com',
        //         'name' => 'Muhammad Babar ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ehtashamakber@gmail.com',
        //         'name' => 'Engr. Ehtasham Akber',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zohaahmed181@gmail.com',
        //         'name' => 'Syeda Zoha Ahmed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'misbahulhasan219@gmail.com',
        //         'name' => 'Misbah Ul Hasan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahmadqaisar.official@gmail.com',
        //         'name' => 'Muhammad Ahmad Qaisar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muskanyounis9@gmail.com',
        //         'name' => 'Muskan Younis ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syyedafatimashoaib@gmail.com',
        //         'name' => 'Syyeda fatima shoaib',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'syedabinish60@gmail.com',
        //         'name' => 'BINISH ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'attaullah22942@gmail.com',
        //         'name' => 'Attaullah Khan ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fatehanaz497@gmail.com',
        //         'name' => 'Fateha naz ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fizzaahmed971@gmail.com',
        //         'name' => 'Fizza Ahmed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zulekha2024ubit@gmail.com',
        //         'name' => 'Zulekha Noor ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhib.khan0246@gmail.com',
        //         'name' => 'Muhib khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hasnain6915786@gmail.com',
        //         'name' => 'Muhammad Hasnain Akram ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muneebabro511@gamil.com',
        //         'name' => 'Muneeb ur Rehman',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'razarais28@gmail.com',
        //         'name' => 'Muhammad Ahmed Raza',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 's.aliadnan06032001@gmail.com',
        //         'name' => 'Syed Ali Adnan Naqvi ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ua1214.aa@gmail.com',
        //         'name' => 'Muhammad Usman Ansari',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alishaessa07@gmail.com',
        //         'name' => 'Alisha Essa ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umaimabibi05@gmail.com',
        //         'name' => 'Umaima bibi',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fatimamasood292@gmail.com',
        //         'name' => 'Fatima Masood ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdullahkidwai45@gmail.com',
        //         'name' => 'Muhammad Abdullah Kidwai ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'taahanadeem8@gmail.com',
        //         'name' => 'Taaha nadeem ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sbabid6@gmail.com',
        //         'name' => 'Syeda Ambreen Abid',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Khadijatul.kk77@gmail.com',
        //         'name' => 'Khadija Tul Kubra',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fatimanaveed835@gmail.com',
        //         'name' => 'Fatima naveed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '13996.fdcarf@gmail.com',
        //         'name' => 'Sami Ur Rehman',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aimak748@gmail.comp',
        //         'name' => 'Aaima khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'afzalxdreader@gmail.com',
        //         'name' => 'Muhammad Afzal Iqbal ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'lakg3104@gmail.com',
        //         'name' => 'Laiba Ali',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'laibajawaid.khan@gmail.com',
        //         'name' => 'Laiba Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shaheerahmedsiddiqui999@gmail.com',
        //         'name' => 'Shaheer Ahmed Siddiqui',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hafsatariq987123@gmail.com',
        //         'name' => 'Syeda Hafsa Tariq ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aleemsaid1100@gmail.com',
        //         'name' => 'Aleem Said',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sohahanif91@gmail.com',
        //         'name' => 'Soha Hanif ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'reebkhan004@gmail.com',
        //         'name' => 'Areeba Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'itsmeaimankhan89@gmail.com',
        //         'name' => 'Aiman Iqbal',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hamidghafoor25@gmail.com',
        //         'name' => 'Muhammad Hamid ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'usmankhan4826@gmail.com',
        //         'name' => 'Muhammad Usman Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'samrahniazi677@gmail.com',
        //         'name' => 'Samrah Niazi ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zubiajunaid954@gmail.com',
        //         'name' => 'Zubia Junaid ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahsan2004ahmed@gmail.com',
        //         'name' => 'Ahsan Ahmed ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'inshirahussain.7@gmail.com',
        //         'name' => 'Inshirah Hussain ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aleeeezayy@gmail.com',
        //         'name' => 'Aleeza Javed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadzeeshanr525@gmail.com',
        //         'name' => 'Muhammad Zeeshan Raza ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'omajamshed123@gmail.com',
        //         'name' => 'Oma Jamshed ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ma12345n12@gmail.com',
        //         'name' => 'Muhammad Abdullah',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => '3usmanahmed7@gmail.com',
        //         'name' => 'MUHAMMAD USMAN',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Maria.masood11may@gmail.com',
        //         'name' => 'Maria masood',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'rameezaliraja210@gmail.com',
        //         'name' => 'Rameez Ali',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zainababdullah252006@gmail.com',
        //         'name' => 'Zainab Abdullah ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zohabhussain91@gmail.com',
        //         'name' => 'zohab hussain',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ullahfaizan1234@gmail.com',
        //         'name' => 'FAIZAN ULLAH ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abroambreen9@gmail.com',
        //         'name' => 'Ambreen ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hassansiddiqui9322@gmail.com',
        //         'name' => 'Muhammad Hassan Siddiqui ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'kduaa0791@gmail.com',
        //         'name' => 'DUAA KHALID ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'avidreaderamna@gmail.com',
        //         'name' => 'Amna Saif',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'babarnaseer716@gmail.com',
        //         'name' => 'Khawaja Babar Naseer ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ali123zaheer000@gmail.com',
        //         'name' => 'Ali Mohammed Zaheer ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shamaim.2412@gmail.com',
        //         'name' => 'Shamaim Zafar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Itxazy01@gmail.com',
        //         'name' => 'Ayaz ahmad',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wahabcreations2161@gmail.com',
        //         'name' => 'Abdul Wahab ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'huriyazafar3@gmail.com',
        //         'name' => 'Huriya zafar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mahishaikh304@gmail.com',
        //         'name' => 'Maheen',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'samiasana080@gmail.com',
        //         'name' => 'Samia sana',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'jaweriakhan6906@gmail.com',
        //         'name' => 'Jaweria Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abd226232@gmail.com',
        //         'name' => 'Abdullah',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shaharyarkhalid55@gmail.com',
        //         'name' => 'Muhammad Shaharyar Khalid ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'asifjanedu3@gmail.com ',
        //         'name' => 'Muhammad Asif jan ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'maryum3156@gmail.com',
        //         'name' => 'Maryum Iftikhar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wajeehabaig369@gmail.com',
        //         'name' => 'Wajeeha Baig',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'daniyalali3236@gmail.com',
        //         'name' => 'Daniyal ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mumarsaleem03@gmail.com',
        //         'name' => 'Muhammad Umar ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Tahir',
        //         'name' => 'Umer',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'amirzeb012017@gmail.com',
        //         'name' => 'Amir zeb ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahtisamiqbal003@gmail.com',
        //         'name' => 'Ahtisam Iqbal',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '24pwbCS1280@uetpeshawar.edu.pk',
        //         'name' => 'Warisha Elahi',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'fatehalikhan434@gmail.com',
        //         'name' => 'Fateh Ali Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'inshirahusssain.12@gmail.com',
        //         'name' => 'Inshirah Hussain',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ambreenazhar.79@gmail.com',
        //         'name' => 'Zayyan Hussain',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'apex_sublime@hotmail.com',
        //         'name' => 'Aisha iqbal',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'afaqalinagra@gmail.com',
        //         'name' => 'Afaq Ali Nagra',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aroobahussain288@gmail.com',
        //         'name' => 'Arooba',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aneesazakria21@gmail.com',
        //         'name' => 'Aneesa Zakria',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'abdulmoeedaamer@yahoo.com',
        //         'name' => 'Abdul Moeed Aamer ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'areejshoaib430@gmail.com',
        //         'name' => 'Areej Shoaib',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'alishbaharoon988@gmail.com',
        //         'name' => 'Alishba Haroon ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'raheelaulakh@gmail.com',
        //         'name' => 'Raheel Aulakh',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'dentistruhee@gmail.com',
        //         'name' => 'Anita Bai',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ali.awan.9988771122@gmail.com',
        //         'name' => 'Muhammad Abdullah Ali Tanveer',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mdawoodhassan180806@gmail.com',
        //         'name' => 'Muhammad Dawood Hassan',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'yousufmuhammad223@gmail.com',
        //         'name' => 'Muhammad yousuf ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'umaymadanish6@gmail.com',
        //         'name' => 'Syeda Umayma Danish',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'esh01734@gmail.com',
        //         'name' => 'Eshmaal Hashmi ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'drzinc2009@gmail.com',
        //         'name' => 'Beenish Latif',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ali03172267239@gmail.com',
        //         'name' => 'Raheel Asghar Ali ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'lishayjadoon@gmail.com',
        //         'name' => 'Alishba jadoon ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'rameenf5519@gmail.com',
        //         'name' => 'Rameen Fatima',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aqsaarshi5@gmail.com',
        //         'name' => 'Aqsa Arshad Rasheed',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zulujee87@gmail.com',
        //         'name' => 'Zulqarnain Haider',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sthashmi09@gmail.com',
        //         'name' => 'Tahir Aslam Hashmi ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mfaizanraza03@gmail.com',
        //         'name' => 'M Faizan Raza',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'Uzairajput8@gmail.com',
        //         'name' => 'Uzair tariq',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'rafaihabbasi306@gmail.com',
        //         'name' => 'Abdul Rafaih Mujeeb Abbasi ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mmuneebashraf@gmail.com',
        //         'name' => 'MUHAMMAD MUNEEB ASHRAF ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mm9427288@gmail.com',
        //         'name' => 'Mehwish Batool ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saramanzoor76@gmail.com',
        //         'name' => 'Sara Manzoor ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'chem_guru@yahoo.com',
        //         'name' => 'Jaweria shamshad',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ali2haris3@gmail.com',
        //         'name' => 'Haris Ali',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hamzaawan11189@gamil.com',
        //         'name' => 'Hamza Shahid',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'laibasajid.777@gmail.com',
        //         'name' => 'Laiba Sajid',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hussainansari2605@gmail.com',
        //         'name' => 'Muhammad Hussain ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'agha177458@gmail.com',
        //         'name' => 'Agha Faseeh Ahmed Khan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ahlamazam8@gmail.com',
        //         'name' => 'Ahlam Azam',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'itsmrahmadasghar@gmail.com',
        //         'name' => 'Muhammad Ahmad ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'farinaabrar59@gmail.com',
        //         'name' => 'Farina ibrar ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'muhammadareesh299@gmail.com',
        //         'name' => 'Areesh ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haziq.uet.se.s5.1@gmail.com',
        //         'name' => 'Haziq Asif',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'talhayounas7727@gmail.com',
        //         'name' => 'Hafiz Muhammad Talha ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'pc2797.amna@gmail.com',
        //         'name' => 'Amna Moeen ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sheeza0015@gmail.com',
        //         'name' => 'Sheeza Sulaiman ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'amalriz64@gmail.com',
        //         'name' => 'Amal Rizwan ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ashnahamid2895@gmail.com',
        //         'name' => 'Ashna Hamid ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'siddiquaq83@gmail.com',
        //         'name' => 'Siddiqua Qureshi ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'ashmalmustafa0@gmail.com',
        //         'name' => 'Ashmal Mustafa ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aizazshah218@gmail.com',
        //         'name' => 'Aizaz Ahmad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'saadatbutt444@gmail.com',
        //         'name' => 'Saadat Ali ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'zafarhassaan83@gmail.com',
        //         'name' => 'Hassaan zafar',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'asmaramehboob@gmail.com ',
        //         'name' => 'Asmara Mehboob',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mhamza.rnd@gmail.com',
        //         'name' => 'Muhammad Hamza ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'talhamumtaz676789@gmail.com',
        //         'name' => 'Talha Mumtaz ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khushalmubaariik@gmail.com',
        //         'name' => 'Khushal Mubarik ',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'daniyaljatt275@gmail.com',
        //         'name' => 'Daniyal Ali',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'happinesswhat31@gmail.com',
        //         'name' => 'Muhammad Abdullah',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'khafiulkonain@gmail.com',
        //         'name' => 'Khafi ul Konain',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'eyeamadnan@gmail.com',
        //         'name' => 'Muhammad Adnan',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hamzaabdulghaffar786@gmail.com',
        //         'name' => 'Ali Hamza ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'awaisrazaqm@gmail.com',
        //         'name' => 'Muhammad Awais Razaq ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shahfaisalsahibzada6@gmail.com',
        //         'name' => 'Shah Faisal sahibzada ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'wasifnaseer350@gmail.com',
        //         'name' => 'Muhammad Wasif Naseer',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'summayamalik030@gmail.com',
        //         'name' => 'Sumayya Malik ',
        //         'course' => 'UI/UX Design Fundamentals',
        //         'whatsapp' => 'https://chat.whatsapp.com/IbHwFWL4pdkB7wFejIOGV5?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'nimrahsaleem114@gmail.com',
        //         'name' => 'Nimrah Saleem ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'www.waniyakhan@20002@gmail.com',
        //         'name' => 'Waniya Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'hibafatima.neduet@gmail.com',
        //         'name' => 'Hiba Fatima ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'haiderzaidi140904@gmail.com',
        //         'name' => 'Syed Haider Abbas Zaidi',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'shehramkhan281@gmail.com',
        //         'name' => 'Shehram khan',
        //         'course' => 'Blockchain Basics',
        //         'whatsapp' => 'https://chat.whatsapp.com/H7lpEBgUPBWFhl2h96ISVm?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'arainamaan08@gmail.com',
        //         'name' => 'Amaan Shahid ',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'aizazkhan6241@gmail.com',
        //         'name' => 'Muhammad Aizaz Khan',
        //         'course' => 'MERN Stack Development',
        //         'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'mfawadkhan7070@gmail.com',
        //         'name' => 'Muhammad Fawad Khan',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => '07husain07@gmail.com',
        //         'name' => 'Muhammad Hussain ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'happyaddi99@gmail.com',
        //         'name' => 'Zaid Ahmad ',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        //     [
        //         'email' => 'sabika.abbas5582@gmail.com ',
        //         'name' => 'Sabika Abbas',
        //         'course' => 'AI Mastery Bootcamp',
        //         'whatsapp' => 'https://chat.whatsapp.com/J9oPeuQ3pUi3E0dpsnjxfT?mode=ac_t',
        //     ],
        // ];
        $recipients = [
            [
                'email' => 'arham404khan@gmail.com',
                'name' => 'Arham Khan',
                'course' => 'MERN Stack Development',
                'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
            ],
            [
                'email' => 'safiahaider650@gmail.com',
                'name' => 'Safia Haider',
                'course' => 'Artificial intelligence',
                'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
            ],
            [
                'email' => 'xehipe2364@hostbyt.com',
                'name' => 'Xehipe',
                'course' => 'Blockchain',
                'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
            ],
            [
                'email' => 'xehipe2364@hostbyt.com',
                'name' => 'Xehipe',
                'course' => 'Artificial intelligence',
                'whatsapp' => 'https://chat.whatsapp.com/I1gvuiLqPxaKaCm2eAIr5v?mode=ac_t',
            ],
        ];

        foreach ($recipients as $i => $data) {
            try {
                Mail::to($data['email'])->later(
                    now()->addSeconds($i * 3),
                    new WelcomeEmail($data['name'], $data['course'], $data['whatsapp'])
                );
            } catch (\Exception $e) {
                \Log::error("Failed to queue email to {$data['email']}: ".$e->getMessage());
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bulk emails are being processed in the background',
        ]);
    }
}
