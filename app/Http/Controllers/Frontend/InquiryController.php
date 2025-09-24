<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        if (! ($settings->get('is_contact_us_page_enabled') && (int) $settings->get('is_contact_us_page_enabled') === 1)) {
            abort(404);
        }

        $tours = Tour::where('status', 'publish')->get();

        return view('frontend.contact-us', compact('tours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_dial_code' => 'required|string|max:255',
            'phone_country_code' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'persons' => 'required|integer',
            'start_date' => 'required|date',
            'package' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);
        $inquiry = Inquiry::create($validated);

        $this->sendAdminInquiryEmail($inquiry);

        return redirect()->route('frontend.index')->with('notify_success', 'Inquiry Submitted Successfully!');
    }

    private function sendAdminInquiryEmail($inquiry)
    {
        try {
            $settings = Setting::pluck('value', 'key');
            $headerLogo = $settings->get('header_logo') ?? asset('admin/assets/images/placeholder-logo.png');
            $template = 'emails.admin-new-inquiry';
            $subject = 'New Inquiry';

            $data = [
                'logo' => asset($headerLogo),
                'details_link' => route('admin.inquiries.edit', $inquiry->id),
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone_dial_code' => $inquiry->phone_dial_code,
                'phone_number' => $inquiry->phone_number,
                'package' => $inquiry->package ?? 'N/A',
                'persons' => $inquiry->persons ?? 'N/A',
                'start_date' => $inquiry->start_date,
            ];

            $finalSubject = $subject.' - '.env('MAIL_FROM_NAME');
            $adminEmail = $settings->get('admin_email') ?? env('MAIL_FROM_ADDRESS');

            Mail::send($template, ['data' => $data], function ($message) use ($adminEmail, $finalSubject) {
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->to($adminEmail)->subject($finalSubject);
            });
        } catch (\Throwable $e) {
            \Log::error('Failed to send admin inquiry email: '.$e->getMessage());
        }
    }
}
