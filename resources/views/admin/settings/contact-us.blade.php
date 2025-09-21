@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.settings.index') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Settings</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'contact-us']) }}" method="POST"
                        enctype="multipart/form-data" id="validation-form">
                        @csrf
                        <div x-data="{ enabled: {{ (int) $settings->get('is_contact_us_page_enabled') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box mb-4">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Contact Us Page</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" name="is_contact_us_page_enabled"
                                                :value="enabled ? 1 : 0">
                                            <input class="form-check-input" type="checkbox" id="contact_us_page_enabled"
                                                x-model="enabled">
                                            <label class="form-check-label" for="contact_us_page_enabled"
                                                x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row mb-4">
                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>H1 Heading Text:</div>
                                                </div>
                                                <input class="field" type="text" name="contact_us_h1_heading_text"
                                                    value="{{ $settings->get('contact_us_h1_heading_text') ?? 'Contact Us & Get Support' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>H1 Heading Color:</div>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="heading-color-picker" data-color-picker></label>
                                                    <input id="heading-color-picker" type="text" data-color-picker-input
                                                        name="contact_us_h1_heading_color"
                                                        value="{{ $settings->get('contact_us_h1_heading_color') ?? '#053070' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Paragraph Text:</div>
                                                </div>
                                                <input class="field" type="text" name="contact_us_paragraph_text"
                                                    value="{{ $settings->get('contact_us_paragraph_text') ?? 'Happy Tours is ready to assist you any time of the day. We are open 24/7 for any inquiries or suggestions you have in mind. Call any contact details below, drop us an email or visit our office; and we will answer to you promptly.' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Paragraph Color:</div>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="paragraph-color-picker" data-color-picker></label>
                                                    <input id="paragraph-color-picker" type="text"
                                                        data-color-picker-input name="contact_us_paragraph_color"
                                                        value="{{ $settings->get('contact_us_paragraph_color') ?? '#000000' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="row mb-4">
                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Form Heading Text:</div>
                                                </div>
                                                <input class="field" type="text" name="contact_us_form_heading_text"
                                                    value="{{ $settings->get('contact_us_form_heading_text') ?? 'Booking and reservations' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Form Heading Color:</div>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="paragraph-color-picker" data-color-picker></label>
                                                    <input id="paragraph-color-picker" type="text"
                                                        data-color-picker-input name="contact_us_form_heading_color"
                                                        value="{{ $settings->get('contact_us_form_heading_color') ?? '#053070' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Form Paragraph Text:</div>
                                                </div>
                                                <input class="field" type="text" name="contact_us_form_paragraph_text"
                                                    value="{{ $settings->get('contact_us_form_paragraph_text') ?? 'Booking and reservations' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Form Paragraph Color:</div>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="form-paragraph-color-picker" data-color-picker></label>
                                                    <input id="form-paragraph-color-picker" type="text"
                                                        data-color-picker-input name="contact_us_form_paragraph_color"
                                                        value="{{ $settings->get('contact_us_form_paragraph_color') ?? '#000000' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Address for map iframe:</div>
                                                </div>
                                                <input class="field" type="text" name="contact_us_iframe_address"
                                                    value="{{ $settings->get('contact_us_iframe_address') ?? 'Desert Safari in Dubai with happy, France R23 - Office No S19 - Dubai International City - France Cluster - Dubai - United Arab Emirates' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="row">
                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Left Column Text:</div>
                                                </div>
                                                <textarea class="field editor" name="contact_us_left_column_text">{{ $settings->get('contact_us_left_column_text') ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="enabled" x-transition>
                                <a href="{{ route('frontend.contact-us.index') }}" target="_blank"
                                    class="themeBtn ms-auto mb-4">View
                                    Contact
                                    Page</a>
                                @php
                                    $contactUsSeoSettings = (object) collect($settings ?? [])
                                        ->filter(fn($value, $key) => str_starts_with($key, 'contact_us_seo'))
                                        ->toArray();
                                @endphp

                                <x-seo-options-entity-based :seo="$contactUsSeoSettings" resource="contact-us"
                                    entity="contact_us_seo" />
                            </div>
                        </div>
                        <button style=" position: sticky; bottom: 1rem; " class="themeBtn ms-auto ">Save Changes <i
                                class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
@endpush
