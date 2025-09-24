<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::all();

        return view('admin.inquiries.list', compact('inquiries'))->with('title', 'Inquiries');
    }

    public function edit($id)
    {
        $inquiry = Inquiry::findOrFail($id);

        return view('admin.inquiries.edit', compact('inquiry'))->with('title', 'Inquiry Details');
    }
}
