@extends('admin.layouts.main')
@section('content')
    @php
        $sectionContent = json_decode($category->section_content);
        $jsonContent = json_decode($category->json_content, true) ?? null;
        $tourCountContent = $sectionContent->tour_count ?? null;
        $callToActionContent = $sectionContent->call_to_action ?? null;
        $newsletterContent = $sectionContent->newsletter ?? null;
        $faqContent = $sectionContent->faq_section ?? null;
        $faqData = [
            'enabled' => $faqContent->is_enabled ?? 0,
            'faqs' => collect($faqContent->question ?? [])
                ->map(function ($q, $i) use ($faqContent) {
                    return [
                        'question' => $q,
                        'answer' => $faqContent->answer[$i] ?? '',
                    ];
                })
                ->values(),
        ];
    @endphp

    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-categories.edit', $category) }}
            <form action="{{ route('admin.tour-categories.update', $category->id) }}" method="POST"
                enctype="multipart/form-data" id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Category: {{ isset($title) ? $title : '' }}</h3>

                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">
                                        {{ buildCategoryDetailUrl($category, false, true) }}/
                                    </div>
                                    <input value="{{ $category->slug ?? '#' }}" type="button" class="link permalink-input"
                                        data-field-id="slug">
                                    <input type="hidden" id="slug" value="{{ $category->slug ?? '#' }}"
                                        name="slug">
                                </div>
                            </div>
                        </div>

                        @if ($category->slug)
                            <a href="{{ buildCategoryDetailUrl($category, true, true) }}" target="_blank"
                                class="themeBtn">View
                                Category</a>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Category Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Category Name <span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="name" class="field"
                                                    value="{{ old('name', $category->name) }}" placeholder="Name"
                                                    data-required data-error="Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Banner Title:</label>
                                                <input name="json_content[h1_banner_text][title]" type="text"
                                                    class="field"
                                                    value="{{ $jsonContent['h1_banner_text']['title'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Banner Subtitle:</label>
                                                <input name="json_content[h1_banner_text][subtitle]" type="text"
                                                    class="field"
                                                    value="{{ $jsonContent['h1_banner_text']['subtitle'] ?? '' }}">
                                            </div>
                                        </div>
                                        <x-admin.city-filter-by-country :isCountryRequired="true" :isCityRequired="false"
                                            :countries="$countries" :cities="$cities" wrapperClass="col-md-8 row pe-0"
                                            selectedCountryId="{{ old('country_id', $category->country_id ?? null) }}"
                                            selectedCityId="{{ old('city_id', $category->city_id ?? null) }}"
                                            countryColClass="col-md-6 mb-4 pe-0" cityColClass="col-md-6 mb-4"
                                            countryName="country_id" cityName="city_id" />
                                        <div class="col-md-4 pe-0 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Parent:</label>
                                                <select name="parent_category_id" class="select2-select category-select"
                                                    data-error="Category">
                                                    <option value="" selected>Parent Category</option>
                                                    @php
                                                        renderCategories(
                                                            $dropdownCategories,
                                                            $category->parent_category_id,
                                                        );
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title d-flex align-items-center gap-2 lh-1">
                                                    Short Description Content
                                                    <button data-bs-placement="top"
                                                        title="Used for category card description" type="button"
                                                        data-tooltip="tooltip" class="tooltip-lg">
                                                        <i class='bx bxs-info-circle'></i>
                                                    </button>
                                                </label>
                                                <textarea class="field" name="short_description" data-placeholder="content" data-error="Content" rows="6"> {{ $category->short_description }} </textarea>
                                                @error('short_description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <div class="form-fields ">
                                                <label class="title">Content
                                                    :</label>
                                                <textarea class="editor" name="long_description" data-placeholder="content" data-error="Content"> {{ $category->long_description }} </textarea>
                                                @error('long_description')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-fields">
                                                <label class="title">Lines to Display Before "Read More" </label>
                                                <input oninput="this.value = Math.abs(this.value)" type="number"
                                                    min="0" name="long_description_line_limit" class="field"
                                                    value="{{ $category->long_description_line_limit }}"
                                                    data-error="long_description_line_limit">
                                            </div>
                                        </div>
                                        <div class="col-12 my-4">
                                            <hr>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-fields">
                                                <div class="title title--sm mb-3">Featured Slider images:</div>
                                                <div class="multiple-upload" data-upload-multiple>
                                                    <input type="file" class="gallery-input d-none" multiple
                                                        data-upload-multiple-input accept="image/*" id="banners"
                                                        name="gallery[]">
                                                    <label class="multiple-upload__btn themeBtn" for="banners">
                                                        <i class='bx bx-plus'></i>
                                                        Choose
                                                    </label>
                                                    <div class="dimensions mt-3">
                                                        <strong>Dimensions:</strong> 1116 &times; 250
                                                    </div>
                                                    <ul class="multiple-upload__imgs" data-upload-multiple-images>
                                                    </ul>
                                                    <div class="text-danger error-message d-none"
                                                        data-upload-multiple-error>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (!$category->media->isEmpty())
                                            <div class="col-12">
                                                <div class="form-fields mt-3">
                                                    <label class="title">Current Slider images:</label>
                                                    <ul class="multiple-upload__imgs">
                                                        @foreach ($category->media as $media)
                                                            <li class="single-image">
                                                                <a href="{{ route('admin.media.destroy', $media->id) }}"
                                                                    onclick="return confirm('Are you sure you want to delete this media?')"
                                                                    class="delete-btn">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </a>
                                                                <a class="mask" href="{{ asset($media->file_path) }}"
                                                                    data-fancybox="gallery">
                                                                    <img src="{{ asset($media->file_path) }}"
                                                                        class="imgFluid" alt="{{ $media->alt_text }}" />
                                                                </a>
                                                                <input type="text" value="{{ $media->alt_text }}"
                                                                    class="field" readonly>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div x-data="{
                                enabled: {{ isset($jsonContent['category_block']['is_enabled']) && $jsonContent['category_block']['is_enabled'] == '1' ? 'true' : 'false' }},
                                headingEnabled: {{ isset($jsonContent['category_block']['heading_enabled']) && $jsonContent['category_block']['heading_enabled'] == '1' ? 'true' : 'false' }}
                            }" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Category Block</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0"
                                                name="json_content[category_block][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="category_block" value="1"
                                                name="json_content[category_block][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="category_block">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/tours-blocks/new-card.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="form-fields mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <label class="title mb-0">Heading</label>
                                            <div class="form-check form-switch" data-enabled-text="Enabled"
                                                data-disabled-text="Disabled">
                                                <input type="hidden" value="0"
                                                    name="json_content[category_block][heading_enabled]">
                                                <input data-toggle-switch class="form-check-input" type="checkbox"
                                                    id="category_block_heading" value="1"
                                                    name="json_content[category_block][heading_enabled]"
                                                    x-model="headingEnabled">
                                                <label class="form-check-label"
                                                    for="category_block_heading">Enabled</label>
                                            </div>
                                        </div>
                                        <input x-show="headingEnabled" x-transition
                                            name="json_content[category_block][heading]" type="text"
                                            class="field mt-3"
                                            value="{{ $jsonContent['category_block']['heading'] ?? '' }}">
                                    </div>

                                    <div class="form-fields mb-4">
                                        @php
                                            $tourBlockCategoryIds = isset(
                                                $jsonContent['category_block']['category_ids'],
                                            )
                                                ? $jsonContent['category_block']['category_ids']
                                                : [];
                                        @endphp
                                        <label class="title">Select categories</label>
                                        <select name="json_content[category_block][category_ids][]" class="select2-select"
                                            data-error="Category" should-sort="true" multiple>
                                            {!! renderCategoriesMulti($dropdownCategories, $tourBlockCategoryIds) !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div x-data="{ enabled: {{ isset($jsonContent['first_tour_block']['is_enabled']) && $jsonContent['first_tour_block']['is_enabled'] == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
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
                                    <a href="{{ asset('admin/assets/images/tours-blocks/new-card.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="form-fields mb-4">
                                        <label class="title">Heading:</label>
                                        <input name="json_content[first_tour_block][heading]" type="text"
                                            class="field"
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
                                <div class="form-box__header d-flex align-items-center justify-content-between">
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
                                    <a href="{{ asset('admin/assets/images/tours-blocks/new-card.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
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
                            <div x-data="{ enabled: {{ isset($tourCountContent->is_enabled) && $tourCountContent->is_enabled == '1' ? 'true' : 'false' }}, btnEnabled: {{ isset($tourCountContent->is_button_enabled) && $tourCountContent->is_button_enabled == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Tour Count Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0" name="content[tour_count][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="tour_count_enabled" value="1"
                                                name="content[tour_count][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="tour_count_enabled">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/ctas-blocks/3.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>
                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-6 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Heading:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="header-color-picker" data-color-picker></label>
                                                            <input id="header-color-picker" type="hidden"
                                                                name="content[tour_count][heading_color]"
                                                                data-color-picker-input
                                                                value="{{ $tourCountContent->heading_color ?? '#ffffff' }}"
                                                                data-error="Heading Color" inputmode="text">

                                                            <input type="text" name="content[tour_count][heading]"
                                                                value="{{ $tourCountContent->heading ?? '' }}"
                                                                placeholder="Heading">
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
                                                    <label class="title title--sm mb-0">Call to Action Button:</label>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input type="hidden" value="0"
                                                            name="content[tour_count][is_button_enabled]">
                                                        <input data-toggle-switch class="form-check-input" type="checkbox"
                                                            id="cta_btn_enabled_tour" value="1"
                                                            name="content[tour_count][is_button_enabled]"
                                                            x-model="btnEnabled">
                                                        <label class="form-check-label"
                                                            for="cta_btn_enabled_tour">Enabled</label>
                                                    </div>
                                                </div>

                                                <div class="row" x-show="btnEnabled" x-transition>

                                                    @php
                                                        $tourCountBtnSeletedCategory = $allCategories
                                                            ->where('id', $tourCountContent->btn_link_category ?? null)
                                                            ->first();
                                                    @endphp

                                                    <div class="col-12">
                                                        <x-admin.category-filter-by-city :cities="$cities" :categories="$allCategories"
                                                            :selectedCityId="$tourCountBtnSeletedCategory->city_id ?? null" :selectedCategoryId="$tourCountBtnSeletedCategory->id ?? null"
                                                            field-name="content[tour_count][btn_link_category]" />
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark">
                                                                Button Background:
                                                            </label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="btn-bg-color" data-color-picker></label>
                                                                <input id="btn-bg-color" type="text"
                                                                    name="content[tour_count][btn_background_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $tourCountContent->btn_background_color ?? '#1c4d99' }}"
                                                                    data-error="Background Color" inputmode="text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark">
                                                                Button Text & Text Color:
                                                            </label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="btn-text-color" data-color-picker></label>
                                                                <input id="btn-text-color" type="hidden"
                                                                    name="content[tour_count][btn_text_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $tourCountContent->btn_text_color ?? '#ffffff' }}"
                                                                    data-error="Text Color" inputmode="text" />

                                                                <input type="text" name="content[tour_count][btn_text]"
                                                                    value="{{ $tourCountContent->btn_text ?? '' }}"
                                                                    data-error="Button Text" placeholder="Button Text" />
                                                            </div>
                                                            <small class="d-block text-muted mt-1">
                                                                Use <code>{x}</code> where you want the tour count to
                                                                appear.
                                                            </small>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-12">
                                            <hr />
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3">Background Style:</label>
                                                <div x-data="{ tour_count_background_type: '{{ isset($tourCountContent->tour_count_background_type) ? $tourCountContent->tour_count_background_type : 'background_image' }}' }">
                                                    <div class="d-flex align-items-center gap-5 px-4">
                                                        <div class="form-check p-0">
                                                            <input class="form-check-input" type="radio"
                                                                id="background_image_count"
                                                                x-model="tour_count_background_type"
                                                                name="content[tour_count][tour_count_background_type]"
                                                                value="background_image" checked />
                                                            <label class="form-check-label"
                                                                for="background_image_count">Background
                                                                Image</label>
                                                        </div>
                                                        <div class="form-check p-0">
                                                            <input class="form-check-input" type="radio"
                                                                id="background_color_2_color_count"
                                                                x-model="tour_count_background_type"
                                                                name="content[tour_count][tour_count_background_type]"
                                                                value="background_color" />
                                                            <label class="form-check-label"
                                                                for="background_color_2_color_count">Background
                                                                Color</label>
                                                        </div>
                                                    </div>
                                                    <div x-show="tour_count_background_type === 'background_image'">
                                                        <div class="row pt-4">
                                                            <div class="col-md-4 mb-4">
                                                                <div class="form-fields">
                                                                    <label class="title">Background Image:</label>
                                                                    <div class="upload upload--sm mx-0" data-upload>
                                                                        <div class="upload-box-wrapper">
                                                                            <div class="upload-box {{ empty($tourCountContent->background_image) ? 'show' : '' }}"
                                                                                data-upload-box>
                                                                                <input type="file"
                                                                                    name="content[tour_count][background_image]"
                                                                                    data-error="Feature Image"
                                                                                    id="background_image_file_count"
                                                                                    class="upload-box__file d-none"
                                                                                    accept="image/*" data-file-input />
                                                                                <div class="upload-box__placeholder">
                                                                                    <i class="bx bxs-image"></i>
                                                                                </div>
                                                                                <label for="background_image_file_count"
                                                                                    class="upload-box__btn themeBtn">Upload
                                                                                    Image</label>
                                                                            </div>
                                                                            <div class="upload-box__img {{ !empty($tourCountContent->background_image) ? 'show' : '' }}"
                                                                                data-upload-img>
                                                                                <button type="button" class="delete-btn"
                                                                                    data-delete-btn="">
                                                                                    <i class="bx bxs-edit-alt"></i>
                                                                                </button>
                                                                                <a href="{{ asset($tourCountContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                                                    class="mask"
                                                                                    data-fancybox="gallery">
                                                                                    <img src="{{ asset($tourCountContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                                                        alt="Uploaded Image"
                                                                                        class="imgFluid"
                                                                                        data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                                                                        data-upload-preview="" />
                                                                                </a>
                                                                                <input type="text"
                                                                                    name="content[tour_count][background_image_alt_text]"
                                                                                    class="field"
                                                                                    placeholder="Enter alt text"
                                                                                    value="{{ $tourCountContent->background_image_alt_text ?? 'Alt Text' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div data-error-message
                                                                            class="text-danger mt-2 d-none text-center">
                                                                            Please upload a valid image file
                                                                        </div>
                                                                        <div class="dimensions text-center mt-3">
                                                                            <strong>Dimensions:</strong> 270 &times; 260
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div x-show="tour_count_background_type === 'background_color'">
                                                        <div class="row pt-4">
                                                            <div class="col-md-12">
                                                                <div class="form-fields">
                                                                    <div class="title d-flex align-items-center gap-2">
                                                                        <div>
                                                                            Select Background Color:
                                                                        </div>
                                                                        <a class="p-0 nav-link"
                                                                            href="//html-color-codes.info"
                                                                            target="_blank">Get Color
                                                                            Codes</a>
                                                                    </div>
                                                                    <div class="field color-picker"
                                                                        data-color-picker-container>
                                                                        <label for="color-picker"
                                                                            data-color-picker></label>
                                                                        <input id="color-picker" type="text"
                                                                            name="content[tour_count][background_color]"
                                                                            data-color-picker-input
                                                                            value="{{ $tourCountContent->background_color ?? '' }}"
                                                                            placeholder="#000000"
                                                                            data-error="background Color"
                                                                            inputmode="text" />
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
                            </div>
                            <div x-data="{ enabled: {{ isset($callToActionContent->is_enabled) && $callToActionContent->is_enabled == '1' ? 'true' : 'false' }}, btnEnabled: {{ isset($callToActionContent->is_button_enabled) && $callToActionContent->is_button_enabled == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Call to Action Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0"
                                                name="content[call_to_action][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="cta_btn_enabled" value="1"
                                                name="content[call_to_action][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="cta_btn_enabled">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/ctas-blocks/1.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2"><i class='bx bxs-show'></i></a>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Heading & Text Color:</label>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="title-color-picker" data-color-picker></label>
                                                    <input id="title-color-picker" type="hidden"
                                                        name="content[call_to_action][title_color]" data-color-picker-input
                                                        value="{{ $callToActionContent->title_color ?? '#000000' }}"
                                                        data-error="Title Color" inputmode="text">

                                                    <input type="text" name="content[call_to_action][title]"
                                                        value="{{ $callToActionContent->title ?? '' }}" placeholder=""
                                                        data-error="title">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Description & Text Color:</label>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="description-color-picker" data-color-picker></label>
                                                    <input id="description-color-picker" type="hidden"
                                                        name="content[call_to_action][description_color]"
                                                        data-color-picker-input
                                                        value="{{ $callToActionContent->description_color ?? '#000000' }}"
                                                        data-error="Description Color" inputmode="text">

                                                    <input type="text" name="content[call_to_action][description]"
                                                        value="{{ $callToActionContent->description ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr />
                                        </div>
                                        <div class="col-lg-12 py-4">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <label class="title title--sm mb-0">Call to Action Button:</label>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input type="hidden"
                                                            name="content[call_to_action][is_button_enabled]"
                                                            value="0">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="cta_btn_enabled_cta" value="1"
                                                            name="content[call_to_action][is_button_enabled]"
                                                            x-model="btnEnabled">
                                                        <label class="form-check-label"
                                                            for="cta_btn_enabled_cta">Enabled</label>
                                                    </div>
                                                </div>

                                                <div class="row" x-show="btnEnabled" x-transition>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark">
                                                                <div class="d-flex align-items-center gap-2 lh-1">
                                                                    <div class="mt-1">Button Link & Background:</div>
                                                                    <button data-bs-placement="top"
                                                                        title="<div class='d-flex flex-column'> <div class='d-flex gap-1'> <strong>Link:</strong> https://abc.com</div> <div class='d-flex gap-1'><strong>Phone:</strong> tel:+971xxxxxxxxx</div> <div class='d-flex gap-1'><strong>Whatsapp:</strong> https://wa.me/971xxxxxxxxx</div> </div>"
                                                                        type="button" data-tooltip="tooltip"
                                                                        class="tooltip-lg">
                                                                        <i class='bx bxs-info-circle'></i>
                                                                    </button>
                                                                </div>
                                                            </label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="cta-btn-bg-color" data-color-picker></label>
                                                                <input id="cta-btn-bg-color" type="hidden"
                                                                    name="content[call_to_action][btn_background_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $callToActionContent->btn_background_color ?? '#1c4d99' }}"
                                                                    data-error="Background Color" inputmode="text" />

                                                                <input type="text"
                                                                    name="content[call_to_action][btn_link]"
                                                                    value="{{ $callToActionContent->btn_link ?? '' }}"
                                                                    placeholder="" data-error="Button Link" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title text-dark">Button Text & Text
                                                                Color:</label>
                                                            <div class="field color-picker" data-color-picker-container>
                                                                <label for="cta-btn-text-color" data-color-picker></label>
                                                                <input id="cta-btn-text-color" type="hidden"
                                                                    name="content[call_to_action][btn_text_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $callToActionContent->btn_text_color ?? '#ffffff' }}"
                                                                    data-error="Text Color" inputmode="text" />

                                                                <input type="text"
                                                                    name="content[call_to_action][btn_text]"
                                                                    value="{{ $callToActionContent->btn_text ?? '' }}"
                                                                    placeholder="" data-error="Button Text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12">
                                            <hr />
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3">Background Style:</label>
                                                <div x-data="{ call_to_action_background_type: '{{ isset($callToActionContent->call_to_action_background_type) ? $callToActionContent->call_to_action_background_type : 'background_image' }}' }">
                                                    <div class="d-flex align-items-center gap-5 px-4">
                                                        <div class="form-check p-0">
                                                            <input class="form-check-input" type="radio"
                                                                name="content[call_to_action][call_to_action_background_type]"
                                                                id="background_image_1"
                                                                x-model="call_to_action_background_type"
                                                                value="background_image" checked />
                                                            <label class="form-check-label"
                                                                for="background_image_1">Background
                                                                Image</label>
                                                        </div>
                                                        <div class="form-check p-0">
                                                            <input class="form-check-input" type="radio"
                                                                id="background_color_2_color"
                                                                x-model="call_to_action_background_type"
                                                                name="content[call_to_action][call_to_action_background_type]"
                                                                value="background_color" />
                                                            <label class="form-check-label"
                                                                for="background_color_2_color">Background
                                                                Color</label>
                                                        </div>
                                                    </div>
                                                    <div x-show="call_to_action_background_type === 'background_image'">
                                                        <div class="row pt-4">
                                                            <div class="col-md-4 mb-4">
                                                                <div class="form-fields">
                                                                    <label class="title">Background Image:</label>
                                                                    <div class="upload upload--sm mx-0" data-upload>
                                                                        <div class="upload-box-wrapper">
                                                                            <div class="upload-box {{ empty($callToActionContent->background_image) ? 'show' : '' }}"
                                                                                data-upload-box>
                                                                                <input type="file"
                                                                                    name="content[call_to_action][background_image]"
                                                                                    data-error="Feature Image"
                                                                                    id="background_image"
                                                                                    class="upload-box__file d-none"
                                                                                    accept="image/*" data-file-input />
                                                                                <div class="upload-box__placeholder">
                                                                                    <i class="bx bxs-image"></i>
                                                                                </div>
                                                                                <label for="background_image"
                                                                                    class="upload-box__btn themeBtn">Upload
                                                                                    Image</label>
                                                                            </div>
                                                                            <div class="upload-box__img {{ !empty($callToActionContent->background_image) ? 'show' : '' }}"
                                                                                data-upload-img>
                                                                                <button type="button" class="delete-btn"
                                                                                    data-delete-btn="">
                                                                                    <i class="bx bxs-edit-alt"></i>
                                                                                </button>
                                                                                <a href="{{ asset($callToActionContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                                                    class="mask"
                                                                                    data-fancybox="gallery">
                                                                                    <img src="{{ asset($callToActionContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                                                        alt="Uploaded Image"
                                                                                        class="imgFluid"
                                                                                        data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                                                                        data-upload-preview="" />
                                                                                </a>
                                                                                <input type="text"
                                                                                    name="content[call_to_action][background_image_alt_text]"
                                                                                    class="field"
                                                                                    placeholder="Enter alt text"
                                                                                    value="{{ $callToActionContent->background_image_alt_text ?? 'Alt Text' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div data-error-message
                                                                            class="text-danger mt-2 d-none text-center">
                                                                            Please upload a valid image file
                                                                        </div>
                                                                        <div class="dimensions text-center mt-3">
                                                                            <strong>Dimensions:</strong> 1116 &times;
                                                                            210
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div x-show="call_to_action_background_type === 'background_color'">
                                                        <div class="row pt-4">
                                                            <div class="col-md-12">
                                                                <div class="form-fields">
                                                                    <div class="title d-flex align-items-center gap-2">
                                                                        <div>
                                                                            Select Background Color:
                                                                        </div>
                                                                        <a class="p-0 nav-link"
                                                                            href="//html-color-codes.info"
                                                                            target="_blank">Get Color
                                                                            Codes</a>
                                                                    </div>
                                                                    <div class="field color-picker"
                                                                        data-color-picker-container>
                                                                        <label for="color-picker"
                                                                            data-color-picker></label>
                                                                        <input id="color-picker" type="text"
                                                                            name="content[call_to_action][background_color]"
                                                                            data-color-picker-input
                                                                            value="{{ $callToActionContent->background_color ?? '' }}"
                                                                            placeholder="#000000"
                                                                            data-error="background Color"
                                                                            inputmode="text" />
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
                            </div>
                            <div x-data="faqRepeater({{ json_encode($faqData) }})" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">FAQ Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0" name="content[faq_section][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="faq_section_enabled" value="1"
                                                name="content[faq_section][is_enabled]" x-model="enabled">
                                            <label class="form-check-label" for="faq_section_enabled">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/faq-section.png') }}" data-fancybox="gallery"
                                        class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title title--sm">FAQs:</label>
                                            <div class="repeater-table">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">
                                                                FAQ Content
                                                                <span
                                                                    class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                                    <span>To add a link:</span>
                                                                    <code class="text-nowrap text-lowercase">
                                                                        &lt;a href="//google.com"
                                                                        target="_blank"&gt;Text&lt;/a&gt;
                                                                    </code>
                                                                    <button class="themeBtn copy-btn py-1 px-2"
                                                                        type="button"
                                                                        text-to-copy='&lt;a href="//google.com" target="_blank"&gt;Text&lt;/a&gt;'>
                                                                        Copy
                                                                    </button>
                                                                </span>
                                                            </th>
                                                            <th class="text-end" scope="col">Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(faq, index) in faqs" :key="index">
                                                            <tr>
                                                                <td>
                                                                    <div class="mb-3">
                                                                        <label class="title">Question <span
                                                                                class="ps-1"
                                                                                x-text="index + 1"></span>:</label>
                                                                        <textarea class="field mb-1" rows="4" x-model="faq.question"
                                                                            :name="`content[faq_section][question][${index}]`"></textarea>
                                                                    </div>
                                                                    <div>
                                                                        <label class="title">Answer <span class="ps-1"
                                                                                x-text="index + 1"></span>:</label>
                                                                        <textarea class="field" rows="4" x-model="faq.answer" :name="`content[faq_section][answer][${index}]`"></textarea>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        @click="removeFaq(index)">
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="themeBtn ms-auto" @click="addFaq">
                                                    Add <i class="bx bx-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-data="{ enabled: {{ isset($newsletterContent->is_enabled) && $newsletterContent->is_enabled == '1' ? 'true' : 'false' }} }" class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Newsletter Section</div>
                                        <div class="form-check form-switch" data-enabled-text="Enabled"
                                            data-disabled-text="Disabled">
                                            <input type="hidden" value="0" name="content[newsletter][is_enabled]">
                                            <input data-toggle-switch class="form-check-input" type="checkbox"
                                                id="newsletter" value="1" name="content[newsletter][is_enabled]"
                                                x-model="enabled">
                                            <label class="form-check-label" for="newsletter">Enabled</label>
                                        </div>
                                    </div>
                                    <a href="{{ asset('admin/assets/images/sections/newsletter.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2"><i class="bx bxs-show"></i></a>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="row">
                                        <div class="col-lg-12 pb-4">
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title">Heading & Text Color:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="newsletter-title-color" data-color-picker></label>
                                                            <input id="newsletter-title-color" type="hidden"
                                                                name="content[newsletter][title_text_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->title_text_color ?? '#1c4d99' }}"
                                                                inputmode="text" />

                                                            <input type="text" name="content[newsletter][title]"
                                                                value="{{ $newsletterContent->title ?? '' }}"
                                                                placeholder="" data-error="Title" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-fields">
                                                        <label class="title">Paragraph & Text Color:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="newsletter-desc-color" data-color-picker></label>
                                                            <input id="newsletter-desc-color" type="hidden"
                                                                name="content[newsletter][description_text_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->description_text_color ?? '#000000' }}"
                                                                inputmode="text" />

                                                            <input type="text" name="content[newsletter][description]"
                                                                value="{{ $newsletterContent->description ?? '' }}"
                                                                placeholder="" data-error="Paragraph" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-lg-12 pt-3 pb-4">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <label class="title title--sm mb-0">Signup Button:</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-fields">
                                                        <div class="title text-dark d-flex align-items-center gap-2">
                                                            <div>
                                                                Button Background Color:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="content[newsletter][btn_background_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->btn_background_color ?? '#1c4d99' }}"
                                                                data-error="background Color" inputmode="text" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-fields">
                                                        <label class="title text-dark">Button Text & Text Color:</label>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="newsletter-btn-text-color"
                                                                data-color-picker></label>

                                                            <input id="newsletter-btn-text-color" type="hidden"
                                                                name="content[newsletter][btn_text_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->btn_text_color ?? '#ffffff' }}"
                                                                inputmode="text" />

                                                            <input type="text" name="content[newsletter][btn_text]"
                                                                value="{{ $newsletterContent->btn_text ?? '' }}"
                                                                placeholder="" data-error="Button Text" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-lg-12 pt-3 pb-4">
                                            <div class="form-fields">
                                                <div class="d-flex mb-2">
                                                    <label class="title title--sm mb-0">Privacy Statement:</label>
                                                    <span
                                                        class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                        <span>To add a link:</span>
                                                        <code class="text-nowrap text-lowercase">&lt;a
                                                            href="//google.com"
                                                            target="_blank"&gt;Text&lt;/a&gt;</code>
                                                        <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                            text-to-copy='&lt;a href="//google.com" target="_blank"&gt;Text&lt;/a&gt;'>
                                                            Copy
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <div class="form-fields">
                                                        <label class="title text-dark">Text:</label>
                                                        <textarea rows="3" name="content[newsletter][privacy_statement]" class="field">{{ $newsletterContent->privacy_statement ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-fields">
                                                        <div class="title d-flex align-items-center gap-2">
                                                            <div>
                                                                Text Color:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="content[newsletter][privacy_statement_text_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->privacy_statement_text_color ?? '#000000' }}"
                                                                inputmode="text" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                        </div>
                                        <div class="col-lg-12 pt-3 pb-4">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <label class="title title--sm mb-0">Background Color/Image:</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-fields">
                                                        <label class="title text-dark">Left Side Image:</label>
                                                        <div class="upload upload--sm mx-0" data-upload>
                                                            <div class="upload-box-wrapper">
                                                                <div class="upload-box {{ empty($newsletterContent->left_image) ? 'show' : '' }}"
                                                                    data-upload-box>
                                                                    <input type="file"
                                                                        name="content[newsletter][left_image]"
                                                                        data-error="Feature Image" id="left_image"
                                                                        class="upload-box__file d-none" accept="image/*"
                                                                        data-file-input>
                                                                    <div class="upload-box__placeholder"><i
                                                                            class='bx bxs-image'></i>
                                                                    </div>
                                                                    <label for="left_image"
                                                                        class="upload-box__btn themeBtn">Upload
                                                                        Image</label>
                                                                </div>
                                                                <div class="upload-box__img {{ !empty($newsletterContent->left_image) ? 'show' : '' }}"
                                                                    data-upload-img>
                                                                    <button type="button" class="delete-btn"
                                                                        data-delete-btn=""><i
                                                                            class='bx bxs-edit-alt'></i></button>
                                                                    <a href="{{ asset(!empty($newsletterContent->left_image) ? $newsletterContent->left_image : 'admin/assets/images/loading.webp') }}"
                                                                        class="mask" data-fancybox="gallery">
                                                                        <img src="{{ asset(!empty($newsletterContent->left_image) ? $newsletterContent->left_image : 'admin/assets/images/loading.webp') }}"
                                                                            alt="Uploaded Image" class="imgFluid"
                                                                            data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                                                            data-upload-preview="">
                                                                    </a>
                                                                    <input type="text"
                                                                        name="content[newsletter][left_image_alt_text]"
                                                                        class="field" placeholder="Enter alt text"
                                                                        value="{{ $newsletterContent->left_image_alt_text ?? 'Alt Text' }}">
                                                                </div>
                                                            </div>
                                                            <div data-error-message
                                                                class="text-danger mt-2 d-none text-center">Please
                                                                upload a
                                                                valid image file
                                                            </div>
                                                            <div class="dimensions text-center mt-3">
                                                                <strong>Dimensions:</strong> 560 &times; 325
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-fields">
                                                        <div class=" text-dark title d-flex align-items-center gap-2">
                                                            <div>Right Background Color:
                                                            </div>
                                                            <a class="p-0 nav-link" href="//html-color-codes.info"
                                                                target="_blank">Get
                                                                Color
                                                                Codes</a>
                                                        </div>
                                                        <div class="field color-picker" data-color-picker-container>
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="text"
                                                                name="content[newsletter][right_background_color]"
                                                                data-color-picker-input
                                                                value="{{ $newsletterContent->right_background_color ?? '#d1f6e2' }}"
                                                                data-error="background Color" inputmode="text">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <x-seo-options :seo="$seo ?? null" :resource="buildCategoryDetailUrl($category, false, false)" :slug="$category->slug" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sticky-save-boxes">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="publish"
                                            value="publish"
                                            {{ old('status', $category->status ?? '') == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft"
                                            {{ old('status', $category->status ?? '') == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Reviews</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        @php
                                            $selectedtoursReviews =
                                                json_decode($category->tour_reviews_ids, true) ?? [];
                                        @endphp
                                        <label class="title">Featured Reviews:</label>
                                        <select name="tour_reviews_ids[]" multiple class="select2-select"
                                            data-max-items="4" placeholder="Select Reviews"
                                            {{ !$toursReviews->isEmpty() ? '' : '' }} data-error="Featured Reviews">
                                            @foreach ($toursReviews as $review)
                                                <option value="{{ $review->id }}"
                                                    {{ in_array($review->id, old('tour_reviews_ids', $selectedtoursReviews)) ? 'selected' : '' }}>
                                                    {{ $review->title }} (Rating: {{ $review->rating }}/5)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tour_reviews_ids')
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
        function faqRepeater(initial = {
            enabled: 0,
            faqs: []
        }) {
            return {
                enabled: Boolean(Number(initial.enabled)),
                faqs: initial.faqs.length ? initial.faqs : [{
                    question: '',
                    answer: ''
                }],
                addFaq() {
                    this.faqs.push({
                        question: '',
                        answer: ''
                    })
                },
                removeFaq(index) {
                    this.faqs.splice(index, 1)
                }
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            document
                .querySelectorAll("[data-color-picker-container]")
                ?.forEach((element) => {
                    InitializeColorPickers(element);
                });
        })

        document.addEventListener('click', e => {
            if (e.target.matches('.copy-btn')) {
                const text = e.target.getAttribute('text-to-copy')
                if (text) navigator.clipboard.writeText(text)
            }
        })
    </script>
@endpush
