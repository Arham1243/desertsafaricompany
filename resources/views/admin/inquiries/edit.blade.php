@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.inquiries.edit', $inquiry) }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-wrapper">
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Inquiry Details</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Name <span class="text-danger">*</span> :</label>
                                            <input readonly type="text" name="name" class="field"
                                                value="{{ old('name', $inquiry->name) }}" placeholder="Name"
                                                data-error="Name">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Email <span class="text-danger">*</span> :</label>
                                            <input readonly type="email" name="email" class="field"
                                                value="{{ old('email', $inquiry->email) }}" placeholder="Email"
                                                data-error="Email">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Phone <span class="text-danger">*</span> :</label>
                                            <input readonly type="text" name="phone" class="field"
                                                value="{{ old('phone', makePhoneNumber($inquiry->phone_dial_code, $inquiry->phone_number)) }}"
                                                placeholder="Phone" data-error="Phone">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">No. of Persons <span class="text-danger">*</span>
                                                :</label>
                                            <input readonly type="number" name="persons" class="field"
                                                value="{{ old('persons', $inquiry->persons) }}" min="1">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Start Date <span class="text-danger">*</span> :</label>
                                            <input readonly type="text" name="start_date" class="field"
                                                value="{{ old('start_date', formatDate($inquiry->start_date)) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Package <span class="text-danger">*</span> :</label>
                                            <input readonly type="text" name="package" class="field"
                                                value="{{ old('package', $inquiry->package) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title">Message :</label>
                                            <textarea readonly name="message" rows="10" class="field">{{ old('message', $inquiry->message) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
