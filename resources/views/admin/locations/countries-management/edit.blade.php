@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.countries.edit', $item) }}
            <form action="{{ route('admin.countries.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Country: {{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildUrl(url('/'), 'country/') }}</div>
                                    <input value="{{ !empty($item->slug) ? $item->slug : '#' }}" type="button"
                                        class="link permalink-input" data-field-id="slug">
                                    <input type="hidden" id="slug"
                                        value="{{ !empty($item->slug) ? $item->slug : '' }}" name="slug" data-required
                                        data-error="Slug">
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('country.details', $item->slug) }}" target="_blank" class="themeBtn">View
                            Country</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Country Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Name <span class="text-danger">*</span> :</label>
                                                <input type="text" name="name" class="field"
                                                    value="{{ old('name', $item->name) }}" placeholder="Name"
                                                    data-error="Name">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">ISO Alpha-2 Code <span class="text-danger">*</span>
                                                    :</label>
                                                <input type="text" name="iso_alpha2" class="field"
                                                    value="{{ old('iso_alpha2', $item->iso_alpha2) }}" placeholder="e.g. ae"
                                                    maxlength="2" data-error="ISO Alpha-2 Code">
                                                @error('iso_alpha2')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-fields">
                                                <label class="title">Content <span class="text-danger">*</span>
                                                    :</label>
                                                <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                                {!! old('content', $item->content) !!}
                                            </textarea>
                                                @error('content')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $sectionContent = json_decode($item->section_content);
                                $guideContent = $sectionContent->guide ?? null;
                            @endphp
                            <div class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Guide Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input class="form-check-input" data-toggle-switch=""
                                                {{ isset($guideContent->is_enabled) ? 'checked' : '' }} type="checkbox"
                                                id="guide_enabled" value="1" name="section_content[guide][is_enabled]">
                                            <label class="form-check-label" for="guide_enabled">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/guide.png') }}" data-fancybox="gallery"
                                        class="themeBtn p-2"><i class='bx bxs-show'></i></a>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Title <span class="text-danger">*</span>
                                                            :</label>
                                                        <input type="text" name="section_content[guide][title]"
                                                            class="field" value="{{ $guideContent->title ?? '' }}"
                                                            maxlength="33">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <div class="title d-flex align-items-center gap-2">
                                                            <div>Title Text Color <span class="text-danger">*</span>:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get
                                                                Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="section_content[guide][title_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->title_color ?? '#000000' }}"
                                                                data-error="background Color" inputmode="text">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Sub title <span class="text-danger">*</span>
                                                            :</label>
                                                        <input type="text" name="section_content[guide][subtitle]"
                                                            class="field" value="{{ $guideContent->title ?? '' }}"
                                                            maxlength="80">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <div class="title d-flex align-items-center gap-2">
                                                            <div>Sub title Text Color <span class="text-danger">*</span>:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get
                                                                Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="section_content[guide][subtitle_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->subtitle_color ?? '#000000' }}"
                                                                data-error="background Color" inputmode="text">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Description <span
                                                                class="text-danger">*</span>
                                                            :</label>
                                                        <input type="text" name="section_content[guide][description]"
                                                            class="field" value="{{ $guideContent->title ?? '' }}"
                                                            maxlength="80">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <div class="title d-flex align-items-center gap-2">
                                                            <div>Description Text Color <span class="text-danger">*</span>:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get
                                                                Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="section_content[guide][description_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->description_color ?? '#000000' }}"
                                                                data-error="background Color" inputmode="text">

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr />
                                        </div>
                                        <div class="col-lg-12 py-4">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <label class="title title--sm mb-0">Call to Action
                                                        Button:</label>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input class="form-check-input" data-toggle-switch=""
                                                            {{ isset($guideContent->is_button_enabled) ? 'checked' : '' }}
                                                            type="checkbox" id="cta_btn_enabled_tour" value="1"
                                                            name="section_content[guide][is_button_enabled]">
                                                        <label class="form-check-label"
                                                            for="cta_btn_enabled_tour">Enabled</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title">Button Text <span
                                                                    class="text-danger">*</span>
                                                                :</label>
                                                            <input type="text"
                                                                value="{{ $guideContent->btn_text ?? '' }}"
                                                                name="section_content[guide][btn_text]" class="field"
                                                                placeholder="" data-error="Button Text" maxlength="28">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title">
                                                                <div class="d-flex align-items-center gap-2 lh-1">
                                                                    <div class="mt-1">Button Link </div>
                                                                    <button data-bs-placement="top"
                                                                        title="<div class='d-flex flex-column'> <div class='d-flex gap-1'> <strong>Link:</strong> https://abc.com</div> <div class='d-flex gap-1'><strong>Phone:</strong> tel:+971xxxxxxxxx</div> <div class='d-flex gap-1'><strong>Whatsapp:</strong> https://wa.me/971xxxxxxxxx</div> </div>"
                                                                        type="button" data-tooltip="tooltip"
                                                                        class="tooltip-lg">
                                                                        <i class='bx bxs-info-circle'></i>
                                                                    </button>
                                                                </div>
                                                            </label>
                                                            <input type="text"
                                                                value="{{ $guideContent->btn_link ?? '' }}"
                                                                name="section_content[guide][btn_link]" class="field"
                                                                placeholder="" data-error="Button Link">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-fields">
                                                            <div class="title d-flex align-items-center gap-2">
                                                                <div>
                                                                    Button Background Color <span
                                                                        class="text-danger">*</span>:
                                                                </div>
                                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                    target="_blank">Get Color
                                                                    Codes</a>
                                                            </div>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="color-picker" data-color-picker></label>
                                                                <input id="color-picker" type="text"
                                                                    name="section_content[guide][btn_background_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $guideContent->btn_background_color ?? '#1c4d99' }}"
                                                                    data-error="background Color" inputmode="text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-fields">
                                                            <div class="title d-flex align-items-center gap-2">
                                                                <div>
                                                                    Button Text Color <span class="text-danger">*</span>:
                                                                </div>
                                                                <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                    target="_blank">Get Color
                                                                    Codes</a>
                                                            </div>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="color-picker" data-color-picker></label>
                                                                <input id="color-picker" type="text"
                                                                    name="section_content[guide][btn_text_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $guideContent->btn_text_color ?? '#ffffff' }}"
                                                                    data-error="background Color" inputmode="text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <hr />
                                        </div>
                                        <div class="col-md-12 pt-3">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>
                                                        Select Background Color <span class="text-danger">*</span>:
                                                    </div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="color-picker" data-color-picker></label>
                                                    <input id="color-picker" type="text"
                                                        name="section_content[guide][background_color]"
                                                        data-color-picker-input
                                                        value="{{ $guideContent->background_color ?? '#EEF6FF' }}"
                                                        placeholder="#000000" data-error="background Color"
                                                        inputmode="text" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-seo-options :seo="$seo ?? null" :resource="'country'" :slug="$item->slug" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="publish"
                                            value="publish"
                                            {{ old('status', $item->status ?? '') == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft"
                                            {{ old('status', $item->status ?? '') == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Feature Image</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <div class="upload" data-upload>
                                            <div class="upload-box-wrapper">
                                                <div class="upload-box {{ empty($item->featured_image) ? 'show' : '' }}"
                                                    data-upload-box>
                                                    <input type="file" name="featured_image"
                                                        {{ empty($item->featured_image) ? '' : '' }}
                                                        data-error="Feature Image" id="featured_image"
                                                        class="upload-box__file d-none" accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="featured_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img {{ !empty($item->featured_image) ? 'show' : '' }}"
                                                    data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="{{ asset($item->featured_image) }}" class="mask"
                                                        data-fancybox="gallery">
                                                        <img src="{{ asset($item->featured_image) }}"
                                                            alt="{{ $item->featured_image_alt_text }}" class="imgFluid"
                                                            data-upload-preview>
                                                    </a>
                                                    <input type="text" name="featured_image_alt_text" class="field"
                                                        placeholder="Enter alt text"
                                                        value="{{ $item->featured_image_alt_text }}">
                                                </div>
                                            </div>
                                            <div data-error-message class="text-danger mt-2 d-none text-center">
                                                Please
                                                upload a
                                                valid image file
                                            </div>
                                            @error('featured_image')
                                                <div class="text-danger mt-2 text-center">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="dimensions text-center mt-3">
                                            <strong>Dimensions:</strong> 270 &times; 260
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Banner Image</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <div class="upload" data-upload>
                                            <div class="upload-box-wrapper">
                                                <div class="upload-box {{ empty($item->banner_image) ? 'show' : '' }}"
                                                    data-upload-box>
                                                    <input type="file" name="banner_image"
                                                        {{ empty($item->banner_image) ? '' : '' }}
                                                        data-error="Feature Image" id="banner_image"
                                                        class="upload-box__file d-none" accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="banner_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img {{ !empty($item->banner_image) ? 'show' : '' }}"
                                                    data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="{{ asset($item->banner_image) }}" class="mask"
                                                        data-fancybox="gallery">
                                                        <img src="{{ asset($item->banner_image) }}"
                                                            alt="{{ $item->banner_image_alt_text }}" class="imgFluid"
                                                            data-upload-preview>
                                                    </a>
                                                    <input type="text" name="banner_image_alt_text" class="field"
                                                        placeholder="Enter alt text"
                                                        value="{{ $item->banner_image_alt_text }}">
                                                </div>
                                            </div>
                                            <div data-error-message class="text-danger mt-2 d-none text-center">
                                                Please
                                                upload a
                                                valid image file
                                            </div>
                                            @error('banner_image')
                                                <div class="text-danger mt-2 text-center">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="dimensions text-center mt-3">
                                            <strong>Dimensions:</strong> 1317 &times; 450
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Tours</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        @php
                                            $selectedBestTours = json_decode($item->best_tours_ids, true) ?? [];
                                        @endphp
                                        <label class="title">Best Things To do <span class="text-danger">*</span>
                                            :</label>
                                        <select name="best_tours_ids[]" multiple class="select2-select"
                                            placeholder="Select Tours" data-error="Top 4 featured tours">
                                            @foreach ($tours as $tour)
                                                <option value="{{ $tour->id }}"
                                                    {{ in_array($tour->id, old('best_tours_ids', $selectedBestTours)) ? 'selected' : '' }}>
                                                    {{ $tour->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('best_tours_ids')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        @php
                                            $selectedPopularTours = json_decode($item->popular_tours_ids, true) ?? [];
                                        @endphp
                                        <label class="title">Popular Activities <span class="text-danger">*</span>
                                            :</label>
                                        <select name="popular_tours_ids[]" multiple class="select2-select"
                                            placeholder="Select Tours" data-error="Top 4 featured tours">
                                            @foreach ($tours as $tour)
                                                <option value="{{ $tour->id }}"
                                                    {{ in_array($tour->id, old('popular_tours_ids', $selectedPopularTours)) ? 'selected' : '' }}>
                                                    {{ $tour->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('popular_tours_ids')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document
                .querySelectorAll("[data-color-picker-container]")
                ?.forEach((element) => {
                    InitializeColorPickers(element);
                });
        })
    </script>
@endpush
