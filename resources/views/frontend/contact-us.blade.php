@extends('frontend.layouts.main')
@section('content')
    <div class="contact-us my-5">
        <div class="container">
            <div class="text-document text-center mb-5">
                <h1
                    @if ($settings->get('contact_us_h1_heading_color')) style="color: {{ $settings->get('contact_us_h1_heading_color') }}" @endif>
                    {{ $settings->get('contact_us_h1_heading_text') ?? '' }}</h1>
                <p
                    @if ($settings->get('contact_us_paragraph_color')) style="color: {{ $settings->get('contact_us_paragraph_color') }}" @endif>
                    {{ $settings->get('contact_us_paragraph_text') ?? '' }}</p>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="contact-us__details editor-content" style="color: #000">
                        {!! $settings->get('contact_us_left_column_text') ?? '' !!}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="contact-us__form">
                        <h5 class="heading"
                            @if ($settings->get('contact_us_form_heading_color')) style="color: {{ $settings->get('contact_us_form_heading_color') }}" @endif>
                            {{ $settings->get('contact_us_form_heading_text') ?? '' }}</h5>
                        <p
                            @if ($settings->get('contact_us_form_paragraph_color')) style="color: {{ $settings->get('contact_us_form_paragraph_color') }}" @endif>
                            {{ $settings->get('contact_us_form_paragraph_text') ?? '' }}</p>

                        <form action="{{ route('frontend.contact-us.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-fields">
                                        <label class="title">Name <span class="text-danger">*</span> </label>
                                        <input type="text" name="name" class="field" value="{{ old('name') }}"
                                            required="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-fields">
                                        <label class="title">Email <span class="text-danger">*</span> </label>
                                        <input type="email" name="email" class="field" value="{{ old('email') }}"
                                            required="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-fields">
                                        <label class="title">Phone <span class="text-danger">*</span> </label>
                                        <div data-flag-input-wrapper>
                                            <input type="hidden" name="phone_dial_code" data-flag-input-dial-code
                                                value="971">
                                            <input type="hidden" name="phone_country_code" data-flag-input-country-code
                                                value="ae">
                                            <input type="text" name="phone_number" class="field flag-input"
                                                data-flag-input value="{{ old('tour[pricing][phone_number]') }}"
                                                placeholder="Phone" data-error="phone" inputmode="numeric" pattern="[0-9]*"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-fields">
                                        <label class="title">No. of Persons <span class="text-danger">*</span> </label>
                                        <input type="number" name="persons" class="field" value="{{ old('persons') }}"
                                            required="" min="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-fields">
                                        <label class="title">Start Date <span class="text-danger">*</span> </label>
                                        <input type="date" name="start_date" class="field"
                                            value="{{ old('start_date') }}" required="">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-fields">
                                        <label class="title">Start Package <span class="text-danger">*</span> </label>
                                        <select name="package" class="field" required="">
                                            <option value="">Select Package</option>
                                            @foreach ($tours as $package)
                                                <option value="{{ $package->title }}"
                                                    {{ old('package') == $package->title ? 'selected' : '' }}>
                                                    {{ $package->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-fields">
                                        <label class="title">Message </label>
                                        <textarea name="message" rows="3" class="field" required="">{{ old('message') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="primary-btn primary-btn--full">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 mt-5 pt-2">
                    <div class="contact-us__map">
                        <iframe
                            src="https://www.google.com/maps?q={{ $settings->get('contact_us_iframe_address') ?? 'United Arab Emirates' }}&output=embed"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script>
        function initializeFlagInputs() {
            $("[data-flag-input-wrapper]").each(function() {
                var $wrapper = $(this);
                var input = $wrapper.find("[data-flag-input]");

                if (input.length > 0) {
                    input.intlTelInput({
                        initialCountry: "ae",
                        separateDialCode: true,
                    });

                    function updateCountryCode() {
                        var countryData = input.intlTelInput("getSelectedCountryData");
                        if (countryData && countryData.dialCode) {
                            $wrapper
                                .find("[data-flag-input-country-code]")
                                .val(countryData.iso2);
                            $wrapper
                                .find("[data-flag-input-dial-code]")
                                .val(countryData.dialCode);
                        }
                    }

                    input.on("countrychange", function(e) {
                        updateCountryCode();
                    });

                    var countryCode = $wrapper
                        .find("[data-flag-input-country-code]")
                        .val();
                    if (countryCode) {
                        input.intlTelInput("setCountry", countryCode);
                    }
                }
            });
        }
        $(document).ready(function() {
            initializeFlagInputs();
        });
    </script>
@endpush
