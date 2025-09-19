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
                    <form action="{{ route('admin.settings.update', ['resource' => 'blogs']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf


                        <div x-data="{ enabled: {{ (int) $settings->get('is_blogs_listing_enabled') === 1 ? 'true' : 'false' }}, listingHeadingEnabled: {{ $settings->get('listing_heading_enabled') ? 'true' : 'false' }}, listingBannerEnabled: {{ $settings->get('listing_banner_enabled') ? 'true' : 'false' }} }">
                            <div class="form-box mb-4">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Listing Page</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" name="is_blogs_listing_enabled" :value="enabled ? 1 : 0">
                                            <input class="form-check-input" type="checkbox" id="blogs_listing_enabled"
                                                x-model="enabled">
                                            <label class="form-check-label" for="blogs_listing_enabled"
                                                x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="form-fields d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <input type="hidden" name="listing_heading_enabled"
                                                        :value="listingHeadingEnabled ? 1 : 0">
                                                    <div class="title title--sm mb-0">Listing H1 Heading:</div>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input data-toggle-switch class="form-check-input" type="checkbox"
                                                            id="listing_heading_enabled_switch" value="1"
                                                            name="listing_heading_enabled" x-model="listingHeadingEnabled">
                                                        <label class="form-check-label"
                                                            for="listing_heading_enabled_switch">Enabled</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12" x-show="listingHeadingEnabled" x-transition>
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Main Listing Heading Text:</div>
                                                </div>
                                                <input class="field" type="text" name="listing_heading_text"
                                                    value="{{ $settings->get('listing_heading_text') ?? 'Explore Blogs from Across the UAE' }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12" x-show="listingHeadingEnabled" x-transition>
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Main Listing Heading Color:</div>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="heading-color-picker" data-color-picker></label>
                                                    <input id="heading-color-picker" type="text" data-color-picker-input
                                                        name="listing_heading_color"
                                                        value="{{ $settings->get('listing_heading_color') ?? '#243064' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-5">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="form-fields d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <input type="hidden" name="listing_banner_enabled"
                                                        :value="listingBannerEnabled ? 1 : 0">
                                                    <div class="title title--sm mb-0">Listing Banner:</div>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input data-toggle-switch class="form-check-input" type="checkbox"
                                                            id="listing_banner_enabled_switch" value="1"
                                                            name="listing_banner_enabled" x-model="listingBannerEnabled">
                                                        <label class="form-check-label"
                                                            for="listing_banner_enabled_switch">Enabled</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4" x-show="listingBannerEnabled" x-transition>
                                            @php
                                                $listingBannerImage = $settings->get('listing_banner_image');
                                                $listingBannerImageAltText = $settings->get(
                                                    'listing_banner_image_alt_text',
                                                );
                                            @endphp
                                            <div class="form-fields">
                                                <div class="upload" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box {{ empty($listingBannerImage) ? 'show' : '' }}"
                                                            data-upload-box>
                                                            <input type="file" name="listing_banner_image"
                                                                data-error="Feature Image" id="listing_banner_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i>
                                                            </div>
                                                            <label for="listing_banner_image"
                                                                class="upload-box__btn themeBtn">Upload Image</label>
                                                        </div>
                                                        <div class="upload-box__img {{ !empty($listingBannerImage) ? 'show' : '' }}"
                                                            data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn>
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <a href="{{ asset($listingBannerImage) }}" class="mask"
                                                                data-fancybox="gallery">
                                                                <img src="{{ asset($listingBannerImage ?? 'admin/assets/images/loading.webp') }}"
                                                                    alt="Uploaded Image" class="imgFluid"
                                                                    data-upload-preview>
                                                            </a>
                                                            <input type="text" name="listing_banner_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="{{ $listingBannerImageAltText }}">
                                                        </div>
                                                    </div>
                                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                                        Please upload a valid image file
                                                    </div>
                                                </div>
                                                <div class="dimensions text-center mt-3">
                                                    <strong>Dimensions:</strong> 1350 × 400
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="enabled" x-transition>
                                <a href="{{ route('frontend.blogs.index') }}" target="_blank"
                                    class="themeBtn ms-auto mb-4">View
                                    Listing
                                    Page</a>
                                @php
                                    $blogSeoSettings = (object) collect($settings ?? [])
                                        ->filter(fn($value, $key) => str_starts_with($key, 'blog_seo'))
                                        ->toArray();
                                @endphp

                                <x-seo-options-entity-based :seo="$blogSeoSettings" resource="blogs" entity="blog_seo" />
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
