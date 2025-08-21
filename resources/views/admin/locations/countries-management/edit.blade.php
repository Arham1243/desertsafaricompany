@extends('admin.layouts.main')
@section('content')
    @php
        $sectionContent = json_decode($item->section_content);
        $jsonContent = json_decode($item->json_content, true) ?? null;
        $guideContent = $sectionContent->guide ?? null;
    @endphp
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
                            @if ($item->iso_alpha2)
                                <div class="permalink">
                                    <div class="title">Permalink:</div>
                                    <div class="title">
                                        <div class="full-url">{{ buildUrl(url('/')) }}/{{ $item->iso_alpha2 }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if ($item->iso_alpha2)
                            <a href="{{ route('locations.country', $item->iso_alpha2) }}" target="_blank"
                                class="themeBtn">View
                                Country</a>
                        @endif
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
                                                    data-error="Name" data-required>
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @php
                                            $countries = require resource_path(
                                                'views/admin/locations/countries-management/countries.php',
                                            );
                                        @endphp

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">ISO Alpha-2 Code <span
                                                        class="text-danger">*</span>:</label>
                                                <select name="iso_alpha2" class="field select2-select"
                                                    data-error="ISO Alpha-2 Code" data-required>
                                                    <option value="" disabled>Select Country</option>
                                                    @foreach ($countries as $name => $code)
                                                        <option value="{{ $code }}"
                                                            {{ old('iso_alpha2', $item->iso_alpha2) == $code ? 'selected' : '' }}>
                                                            {{ ucfirst($name) }} - {{ $code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('iso_alpha2')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Content:</label>
                                                <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                                {!! old('content', $item->content) !!}
                                            </textarea>
                                                @error('content')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-fields">
                                                <label class="title">Lines to Display Before "Read More" </label>
                                                <input oninput="this.value = Math.abs(this.value)" type="number"
                                                    min="0" name="content_line_limit" class="field"
                                                    value="{{ old('content_line_limit', $item->content_line_limit) }}"
                                                    data-error="content_line_limit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ enabled: {{ isset($jsonContent['first_tour_block']['is_enabled']) && $jsonContent['first_tour_block']['is_enabled'] == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">First Tour Block</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0"
                                                name="json_content[first_tour_block][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="first_tour_block" value="1"
                                                name="json_content[first_tour_block][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="first_tour_block">Enabled</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="form-fields mb-4">
                                        <label class="title">Heading:</label>
                                        <input name="json_content[first_tour_block][heading]" type="text" class="field"
                                            value="{{ $jsonContent ? $jsonContent['first_tour_block']['heading'] : '' }}">
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Select Tours:</label>
                                        <select name="json_content[first_tour_block][tour_ids][]" multiple
                                            class="select2-select">
                                            @foreach ($tours as $firstTourBlockT)
                                                <option value="{{ $firstTourBlockT->id }}"
                                                    {{ in_array($firstTourBlockT->id, $jsonContent['first_tour_block']['tour_ids'] ?? []) ? 'selected' : '' }}>
                                                    {{ $firstTourBlockT->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ enabled: {{ isset($jsonContent['second_tour_block']['is_enabled']) && $jsonContent['second_tour_block']['is_enabled'] == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Second Tour Block</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0"
                                                name="json_content[second_tour_block][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="second_tour_block" value="1"
                                                name="json_content[second_tour_block][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="second_tour_block">Enabled</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="form-fields mb-4">
                                        <label class="title">Heading:</label>
                                        <input name="json_content[second_tour_block][heading]" type="text"
                                            class="field"
                                            value="{{ $jsonContent ? $jsonContent['second_tour_block']['heading'] : '' }}">
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Select Tours:</label>
                                        <select name="json_content[second_tour_block][tour_ids][]" multiple
                                            class="select2-select">
                                            @foreach ($tours as $secondTourBlockT)
                                                <option value="{{ $secondTourBlockT->id }}"
                                                    {{ in_array($secondTourBlockT->id, $jsonContent['second_tour_block']['tour_ids'] ?? []) ? 'selected' : '' }}>
                                                    {{ $secondTourBlockT->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box" x-data="{
                                enabled: {{ isset($guideContent->is_enabled) && $guideContent->is_enabled == '1' ? 'true' : 'false' }},
                                btnEnabled: {{ isset($guideContent->is_button_enabled) && $guideContent->is_button_enabled == '1' ? 'true' : 'false' }},
                                linkType: '{{ $guideContent->btn_link_type ?? 'link' }}'
                            }">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Guide Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0"
                                                name="section_content[guide][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="guide_enabled" value="1"
                                                name="section_content[guide][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="guide_enabled">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/guide.png') }}" data-fancybox="gallery"
                                        class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>
                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Title & Text Color <span
                                                                class="text-danger">*</span>:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="title-color-picker" data-color-picker></label>
                                                            <input id="title-color-picker" type="hidden"
                                                                name="section_content[guide][title_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->title_color ?? '#2820E1' }}"
                                                                data-error="Title Color" inputmode="text">
                                                            <input type="text" name="section_content[guide][title]"
                                                                value="{{ $guideContent->title ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Sub title & Text Color <span
                                                                class="text-danger">*</span>:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="subtitle-color-picker" data-color-picker></label>
                                                            <input id="subtitle-color-picker" type="hidden"
                                                                name="section_content[guide][subtitle_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->subtitle_color ?? '#000000' }}"
                                                                data-error="Sub title Color" inputmode="text">
                                                            <input type="text" name="section_content[guide][subtitle]"
                                                                value="{{ $guideContent->subtitle ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-fields">
                                                        <label class="title">Description & Text Color <span
                                                                class="text-danger">*</span>:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="description-color-picker"
                                                                data-color-picker></label>
                                                            <input id="description-color-picker" type="hidden"
                                                                name="section_content[guide][description_color]"
                                                                data-color-picker-input
                                                                value="{{ $guideContent->description_color ?? '#2E5776' }}"
                                                                data-error="Description Color" inputmode="text">
                                                            <input type="text"
                                                                name="section_content[guide][description]"
                                                                value="{{ $guideContent->description ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 my-4">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <label class="title title--sm mb-0">Call to Action Button:</label>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input type="hidden" value="0"
                                                            name="section_content[guide][is_button_enabled]">
                                                        <input data-toggle-switch class="form-check-input" type="checkbox"
                                                            id="cta_btn_enabled_guide" value="1"
                                                            name="section_content[guide][is_button_enabled]"
                                                            x-model="btnEnabled">
                                                        <label class="form-check-label"
                                                            for="cta_btn_enabled_guide">Enabled</label>
                                                    </div>
                                                </div>

                                                <div class="row" x-show="btnEnabled">
                                                    <div class="col-12 mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                id="link_type_link"
                                                                name="section_content[guide][btn_link_type]"
                                                                value="link" x-model="linkType">
                                                            <label class="form-check-label" for="link_type_link">Normal
                                                                Link</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                id="link_type_category"
                                                                name="section_content[guide][btn_link_type]"
                                                                value="category" x-model="linkType">
                                                            <label class="form-check-label"
                                                                for="link_type_category">Category Page</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4" x-show="linkType === 'link'">
                                                        <div class="form-fields">
                                                            <label class="title d-flex align-items-center gap-2 lh-1">
                                                                <div class="mt-1 text-dark">Button Link:</div>
                                                                <button data-bs-placement="top"
                                                                    title="<div class='d-flex flex-column'> <div class='d-flex gap-1'> <strong>Link:</strong> https://abc.com</div> <div class='d-flex gap-1'><strong>Phone:</strong> tel:+971xxxxxxxxx</div> <div class='d-flex gap-1'><strong>Whatsapp:</strong> https://wa.me/971xxxxxxxxx</div> </div>"
                                                                    type="button" data-tooltip="tooltip"
                                                                    class="tooltip-lg">
                                                                    <i class='bx bxs-info-circle'></i>
                                                                </button>
                                                            </label>
                                                            <input class="field" type="text"
                                                                name="section_content[guide][btn_link]"
                                                                value="{{ $guideContent->btn_link ?? '' }}"
                                                                placeholder="Enter button link"
                                                                data-error="Button Link" />
                                                        </div>
                                                    </div>

                                                    <div class="col-12 mb-4" x-show="linkType === 'category'"
                                                        x-transition>
                                                        <div class="form-fields">
                                                            <label class="title">Attach Category page to button:</label>
                                                            <select name="section_content[guide][btn_link_category_id]"
                                                                class="select2-select" data-error="Category"
                                                                should-sort="false">
                                                                <option value="" disabled>Select Category</option>
                                                                {!! renderCategories($categories, $guideContent->btn_link_category_id ?? null) !!}
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark"> Button Background
                                                                Color:</label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="guide-btn-bg-color" data-color-picker></label>
                                                                <input id="guide-btn-bg-color" type="text"
                                                                    name="section_content[guide][btn_background_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $guideContent->btn_background_color ?? '#1c4d99' }}"
                                                                    data-error="Background Color" inputmode="text" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark">Button Text & Text
                                                                Color:</label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="guide-btn-text-color"
                                                                    data-color-picker></label>
                                                                <input id="guide-btn-text-color" type="hidden"
                                                                    name="section_content[guide][btn_text_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $guideContent->btn_text_color ?? '#ffffff' }}"
                                                                    data-error="Text Color" inputmode="text" />
                                                                <input type="text"
                                                                    name="section_content[guide][btn_text]"
                                                                    value="{{ $guideContent->btn_text ?? '' }}"
                                                                    placeholder="" data-error="Button Text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 my-4">
                                            <hr>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Select Box Background Color <span class="text-danger">*</span>:
                                                    </div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="guide-background-color" data-color-picker></label>
                                                    <input id="guide-background-color" type="text"
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
                            <x-seo-options :seo="$seo ?? null" :slug="$item->iso_alpha2 ?? ''" />
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
