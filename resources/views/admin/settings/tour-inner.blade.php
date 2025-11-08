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
                    <form action="{{ route('admin.settings.update', ['resource' => 'tour-inner']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $selectedBannerStyle = $settings->get('banner_style');
                            $perks = $settings->get('perks');
                        @endphp
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Banner Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="d-flex align-items-center justify-content-center gap-5 px-4">
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" name="banner_style" id="style-1"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-1' ? 'checked' : '') : '' }}
                                            value="style-1" checked />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-1">Style
                                            1
                                            <a href="{{ asset('admin/assets/images/banner-styles/1.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-2" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-2' ? 'checked' : '') : '' }}
                                            value="style-2" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-2">Style
                                            2
                                            <a href="{{ asset('admin/assets/images/banner-styles/2.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-3" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-3' ? 'checked' : '') : '' }}
                                            value="style-3" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-3">Style
                                            3
                                            <a href="{{ asset('admin/assets/images/banner-styles/3.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                    <div class="form-check p-0 ps-1">
                                        <input class="form-check-input" type="radio" id="style-4" name="banner_style"
                                            {{ isset($selectedBannerStyle) ? ($selectedBannerStyle === 'style-4' ? 'checked' : '') : '' }}
                                            value="style-4" />
                                        <label class="form-check-label d-flex align-items-center gap-2" for="style-4">Style
                                            4
                                            <a href="{{ asset('admin/assets/images/banner-styles/4.png') }}"
                                                data-fancybox="gallery" title="section preview"
                                                class="themeBtn section-preview-image section-preview-image--sm"><i
                                                    class="bx bxs-show"></i></a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Color Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Content Color Settings
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Headings Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="heading-color-picker" data-color-picker></label>
                                                <input id="heading-color-picker" type="text" data-color-picker-input
                                                    name="global_heading_color"
                                                    value="{{ $settings->get('global_heading_color') ?? '#343a40' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Paragraphs Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="paragraph-color-picker" data-color-picker></label>
                                                <input id="paragraph-color-picker" type="text" data-color-picker-input
                                                    name="global_paragraph_color"
                                                    value="{{ $settings->get('global_paragraph_color') ?? '#5e6d77' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <hr class="my-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Pricing Box Heading
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/tour-pricing-heading.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Heading Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="pricing-box-heading-color-picker" data-color-picker></label>
                                                <input id="pricing-box-heading-color-picker" type="text"
                                                    data-color-picker-input name="pricing_box_heading_color"
                                                    value="{{ $settings->get('pricing_box_heading_color') ?? '#343a40' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Pricing Tagline
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/tour-pricing-tagline.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm">
                                                    <i class="bx bxs-show"></i>
                                                </a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="pricing-tagline-icon-color-picker" data-color-picker></label>
                                                <input id="pricing-tagline-icon-color-picker" type="text"
                                                    data-color-picker-input name="pricing_tagline_icon_color"
                                                    value="{{ $settings->get('pricing_tagline_icon_color') ?? '#433f46' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="pricing-tagline-text-color-picker" data-color-picker></label>
                                                <input id="pricing-tagline-text-color-picker" type="text"
                                                    data-color-picker-input name="pricing_tagline_text_color"
                                                    value="{{ $settings->get('pricing_tagline_text_color') ?? '#625c66' }}"
                                                    inputmode="text">
                                            </div>
                                            <div class="form-fields mt-3">
                                                <div class="form-check">
                                                    <input type="hidden" name="pricing_tagline_bold" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        {{ $settings->get('pricing_tagline_bold') ? 'checked' : '' }}
                                                        name="pricing_tagline_bold" id="pricing_tagline_bold"
                                                        value="1">
                                                    <label class="form-check-label" for="pricing_tagline_bold">
                                                        Make text Bold
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Badge Icon
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/badge-preview.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Background Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="background-color-picker-badge" data-color-picker></label>
                                                <input id="background-color-picker-badge" type="text"
                                                    data-color-picker-input name="badge_background_color"
                                                    value="{{ $settings->get('badge_background_color') ?? '#edab56' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-badge" data-color-picker></label>
                                                <input id="icon-color-picker-badge" type="text" data-color-picker-input
                                                    name="badge_icon_color"
                                                    value="{{ $settings->get('badge_icon_color') ?? '#000000' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Features Icons
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/feature-icon-preview.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-feature" data-color-picker></label>
                                                <input id="icon-color-picker-feature" type="text"
                                                    data-color-picker-input name="features_icon_color"
                                                    value="{{ $settings->get('features_icon_color') ?? '#1c4d99' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Inclusions Icons
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/inclusion-icon-preview.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-inclusion" data-color-picker></label>
                                                <input id="icon-color-picker-inclusion" type="text"
                                                    data-color-picker-input name="inclusion_icon_color"
                                                    value="{{ $settings->get('inclusion_icon_color') ?? '#1c4d99' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Exclusions Icons
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/exclusion-icon-preview.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-exclusion" data-color-picker></label>
                                                <input id="icon-color-picker-exclusion" type="text"
                                                    data-color-picker-input name="exclusion_icon_color"
                                                    value="{{ $settings->get('exclusion_icon_color') ?? '#d00606' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Itinerary Section
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/itinerary-section-preview.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Background dotted/solid line color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-line"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-line" type="text"
                                                    data-color-picker-input name="itinerary_section_background_line_color"
                                                    value="{{ $settings->get('itinerary_section_background_line_color') ?? '#ff4507' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Pickup circle background color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-pickup-circle"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-pickup-circle"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_pickup_circle_background_color"
                                                    value="{{ $settings->get('itinerary_section_pickup_circle_background_color') ?? '#FD4503' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Pickup circle icon color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-pickup-circle-icon"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-pickup-circle-icon"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_pickup_circle_icon_color"
                                                    value="{{ $settings->get('itinerary_section_pickup_circle_icon_color') ?? '#ffffff' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Dropoff circle background color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-dropoff-circle"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-dropoff-circle"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_dropoff_circle_background_color"
                                                    value="{{ $settings->get('itinerary_section_dropoff_circle_background_color') ?? '#FD4503' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Dropoff circle icon color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-dropoff-circle-icon"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-dropoff-circle-icon"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_dropoff_circle_icon_color"
                                                    value="{{ $settings->get('itinerary_section_dropoff_circle_icon_color') ?? '#ffffff' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Vehicles circle background color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-vehicles-circle"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-vehicles-circle"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_vehicles_circle_background_color"
                                                    value="{{ $settings->get('itinerary_section_vehicles_circle_background_color') ?? '#ffffff' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Vehicles circle icon color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-vehicles-circle-icon"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-vehicles-circle-icon"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_vehicles_circle_icon_color"
                                                    value="{{ $settings->get('itinerary_section_vehicles_circle_icon_color') ?? '#364f65' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Stops circle background color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-stops-circle"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-stops-circle"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_stops_circle_background_color"
                                                    value="{{ $settings->get('itinerary_section_stops_circle_background_color') ?? '#364f65' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mt-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Stops circle icon color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="icon-color-picker-itinerary-section-stops-circle-icon"
                                                    data-color-picker></label>
                                                <input id="icon-color-picker-itinerary-section-stops-circle-icon"
                                                    type="text" data-color-picker-input
                                                    name="itinerary_section_stops_circle_icon_color"
                                                    value="{{ $settings->get('itinerary_section_stops_circle_icon_color') ?? '#ffffff' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Author Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Author prefix
                                                <a href="{{ asset('admin/assets/images/tour-inner-settings/author-text.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                        <div class="form-fields">
                                            <label class="text-dark title">Prefix Text:</label>
                                            <input type="text" name="tour_author_prefix_text"
                                                value="{{ $settings->get('tour_author_prefix_text') ?? 'Designed and Developed by' }}"
                                                class="field">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_enabled_detail_popup_trigger_box') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Detail Popups Settings</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_enabled_detail_popup_trigger_box"
                                            :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox"
                                            id="enable-detail_popup_trigger_box" x-model="enabled">
                                        <label class="form-check-label" for="enable-detail_popup_trigger_box"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box__body" x-show="enabled" x-transition>
                                <div class="form-fields">
                                    <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                        Popup trigger Box Settings
                                        <a href="{{ asset('admin/assets/images/tour-detail-popup-trigger.png') }}"
                                            data-fancybox="gallery" title="section preview"
                                            class="themeBtn section-preview-image section-preview-image--sm"><i
                                                class="bx bxs-show"></i></a></label>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon:</div>
                                                <a class="p-0 nav-link" href="//v2.boxicons.com"
                                                    target="_blank">boxicons</a>
                                            </div>
                                            <div x-data="{ icon: '{{ $settings->get('detail_popup_trigger_box_icon') ?? 'bx bxs-check-circle' }}' }" class="d-flex align-items-center gap-3">
                                                <input type="text" id="detail_popup_trigger_box_icon"
                                                    name="detail_popup_trigger_box_icon" class="field" x-model="icon">
                                                <i :class="`${icon} bx-sm`" style="font-size: 1.5rem"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Icon Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color
                                                    Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="detail_popup_trigger_box_icon_color" data-color-picker></label>
                                                <input id="detail_popup_trigger_box_icon_color" type="text"
                                                    data-color-picker-input name="detail_popup_trigger_box_icon_color"
                                                    value="{{ $settings->get('detail_popup_trigger_box_icon_color') ?? '#1c4d99' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Box Background:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color
                                                    Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="detail_popup_trigger_box_background_color"
                                                    data-color-picker></label>
                                                <input id="detail_popup_trigger_box_background_color" type="text"
                                                    data-color-picker-input
                                                    name="detail_popup_trigger_box_background_color"
                                                    value="{{ $settings->get('detail_popup_trigger_box_background_color') ?? '#EDF4FA' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Box Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color
                                                    Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="detail_popup_trigger_box_text_color" data-color-picker></label>
                                                <input id="detail_popup_trigger_box_text_color" type="text"
                                                    data-color-picker-input name="detail_popup_trigger_box_text_color"
                                                    value="{{ $settings->get('detail_popup_trigger_box_text_color') ?? '#000000' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        @php
                                            $selectedPopups = $settings->get('detail_popup_ids')
                                                ? json_decode($settings->get('detail_popup_ids'))
                                                : [];
                                        @endphp

                                        <input type="hidden" name="detail_popup_ids[]" value="">
                                        <div class="form-fields">
                                            <label class="title" style="text-transform: initial">Select Detail
                                                Popup(s):</label>
                                            <select name="detail_popup_ids[]" class="field select2-select" multiple>
                                                @foreach ($detailPopups as $popup)
                                                    <option value="{{ $popup->id }}"
                                                        {{ in_array($popup->id, $selectedPopups) ? 'selected' : '' }}>
                                                        {{ $popup->main_heading }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('help_whatsapp_is_enabled') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">WhatsApp Settings</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="help_whatsapp_is_enabled" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="help_whatsapp_is_enabled"
                                            x-model="enabled">
                                        <label class="form-check-label" for="help_whatsapp_is_enabled"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-box__body" x-show="enabled" x-transition>
                                <div class="form-fields">
                                    <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                        WhatsApp Settings
                                        <a href="{{ asset('admin/assets/images/whatsapp-help.png') }}"
                                            data-fancybox="gallery" title="section preview"
                                            class="themeBtn section-preview-image section-preview-image--sm">
                                            <i class="bx bxs-show"></i>
                                        </a>
                                    </label>
                                </div>

                                <div class="row">
                                    <!-- Heading -->
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="text-dark title" for="help_whatsapp_heading">Heading:</label>
                                            <input type="text" id="help_whatsapp_heading" name="help_whatsapp_heading"
                                                value="{{ $settings->get('help_whatsapp_heading') ?? 'Need Help in booking?' }}"
                                                class="field">
                                        </div>
                                    </div>

                                    <!-- Heading Text Color -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Heading Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="help_whatsapp_heading_text_color" data-color-picker></label>
                                                <input id="help_whatsapp_heading_text_color" type="text"
                                                    data-color-picker-input name="help_whatsapp_heading_text_color"
                                                    value="{{ $settings->get('help_whatsapp_heading_text_color') ?? '#000000' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button Text Color -->
                                    <div class="col-md-6 col-12 mb-4">
                                         <div class="form-fields">
                                            <label class="text-dark title" for="help_whatsapp_button_text">Button Text:</label>
                                            <input type="text" id="help_whatsapp_button_text" name="help_whatsapp_button_text"
                                                value="{{ $settings->get('help_whatsapp_button_text') ?? 'Chat on WhatsApp' }}"
                                                class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Button Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="help_whatsapp_button_text_color" data-color-picker></label>
                                                <input id="help_whatsapp_button_text_color" type="text"
                                                    data-color-picker-input name="help_whatsapp_button_text_color"
                                                    value="{{ $settings->get('help_whatsapp_button_text_color') ?? '#ffffff' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Button Link -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="d-flex align-items-center gap-2 lh-1">
                                                <div class="mt-1 text-dark title">Button Link & Background:</div>
                                                <button data-bs-placement="top"
                                                    title="<div class='d-flex flex-column'>
                                    <div class='d-flex gap-1'><strong>Link:</strong> https://abc.com</div>
                                    <div class='d-flex gap-1'><strong>Phone:</strong> tel:+971xxxxxxxxx</div>
                                    <div class='d-flex gap-1'><strong>WhatsApp:</strong> https://wa.me/971xxxxxxxxx</div>
                                </div>"
                                                    type="button" data-tooltip="tooltip" class="tooltip-lg">
                                                    <i class='bx bxs-info-circle'></i>
                                                </button>
                                            </div>
                                            <input type="text" id="help_whatsapp_button_link"
                                                name="help_whatsapp_button_link"
                                                value="{{ $settings->get('help_whatsapp_button_link') ?? 'https://wa.me/12345678900' }}"
                                                class="field">
                                        </div>
                                    </div>

                                    <!-- Button Background -->
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="text-dark title d-flex align-items-center gap-2">
                                                <div>Button Background:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                    target="_blank">Get Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="help_whatsapp_button_background_color"
                                                    data-color-picker></label>
                                                <input id="help_whatsapp_button_background_color" type="text"
                                                    data-color-picker-input name="help_whatsapp_button_background_color"
                                                    value="{{ $settings->get('help_whatsapp_button_background_color') ?? '#2E9E73' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_enabled_why_book_with_us') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">"Why Book With Us?" Settings</div>

                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_enabled_why_book_with_us" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="enable-why-book-with-us"
                                            x-model="enabled">
                                        <label class="form-check-label" for="enable-why-book-with-us"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-box__body" x-show="enabled" x-transition>
                                <div class="form-fields">
                                    <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                        Icons Color Settings
                                        <a href="{{ asset('admin/assets/images/tour-inner-settings/perks-icon-preview.png') }}"
                                            data-fancybox="gallery" title="section preview"
                                            class="themeBtn section-preview-image section-preview-image--sm"><i
                                                class="bx bxs-show"></i></a></label>
                                </div>
                                <div class="form-fields">
                                    <div class=" text-dark title d-flex align-items-center gap-2">
                                        <div>Icon Color:</div>
                                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get
                                            Color Codes</a>
                                    </div>
                                    <div class="field color-picker" data-color-picker-container>
                                        <label for="icon-color-picker-perks" data-color-picker></label>
                                        <input id="icon-color-picker-perks" type="text" data-color-picker-input
                                            name="perks_icon_color"
                                            value="{{ $settings->get('perks_icon_color') ?? '#1c4d99' }}"
                                            inputmode="text">
                                    </div>
                                </div>
                                <div class="form-fields mt-4 pt-2">
                                    <div x-data="featuresManager">
                                        <div class="repeater-table">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">
                                                            <div class="d-flex align-items-center gap-2">
                                                                Icon:
                                                                <a class="p-0 nav-link" href="//v2.boxicons.com"
                                                                    target="_blank">boxicons</a>
                                                            </div>
                                                        </th>
                                                        <th scope="col">Title</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <input type="hidden" value="[]" name="perks">
                                                    <template x-for="(feature, index) in features" :key="index">
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <input type="text" class="field"
                                                                        :name="`perks[${index}][icon]`"
                                                                        x-model="feature.icon"
                                                                        @input="$el.nextElementSibling.className = feature.icon"
                                                                        placeholder="Enter icon class">
                                                                    <i style="font-size: 1.5rem"
                                                                        :class="` ${feature.icon}  `" data-preview-icon></i>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" :name="`perks[${index}][title]`"
                                                                    x-model="feature.title" class="field" maxlength="50"
                                                                    placeholder="Enter title">
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="delete-btn ms-auto delete-btn--static"
                                                                    @click="removeFeature(index)">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                            <button type="button" class="themeBtn ms-auto" @click="addFeature">
                                                Add <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
    <script>
        function featuresManager() {
            return {
                features: @if ($perks)
                    @js(json_decode($perks))
                @else
                    [{
                        icon: '',
                        icon_color: '',
                        title: '',
                    }]
                @endif ,
                addFeature() {
                    this.features.push({
                        icon: '',
                        icon_color: '',
                        title: '',
                    });
                    this.$nextTick(() => {
                        document.querySelectorAll("[data-color-picker-container]").forEach(el => {
                            InitializeColorPickers(el);
                        });
                    });
                },
                removeFeature(index) {
                    this.features.splice(index, 1);
                }
            };
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
@endpush
