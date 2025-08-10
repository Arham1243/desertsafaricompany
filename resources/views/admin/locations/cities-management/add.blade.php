@extends('admin.layouts.main')
@section('content')
    @php
        $jsonContent = null;
        $guideContent = null;
    @endphp
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.cities.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.cities.store') }}" method="POST" enctype="multipart/form-data" id="validation-form">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">City Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Name <span class="text-danger">*</span> :</label>
                                        <input type="text" name="name" class="field" value="{{ old('name') }}"
                                            placeholder="Name" data-error="Name" data-required>
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Country <span class="text-danger">*</span> :</label>
                                        <select name="country_id" class="select2-select" data-error="Country" data-required>
                                            <option value="" selected disabled>Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content:</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {{ old('content') }}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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
                                        <label class="title text-dark">Heading:</label>
                                        <input name="json_content[first_tour_block][heading]" type="text" class="field"
                                            value="{{ $jsonContent ? $jsonContent['first_tour_block']['heading'] : '' }}">
                                    </div>
                                    <div class="form-fields">
                                        <label class="title text-dark">Select Tours:</label>
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
                                        <label class="title text-dark">Heading:</label>
                                        <input name="json_content[second_tour_block][heading]" type="text"
                                            class="field"
                                            value="{{ $jsonContent ? $jsonContent['second_tour_block']['heading'] : '' }}">
                                    </div>
                                    <div class="form-fields">
                                        <label class="title text-dark">Select Tours:</label>
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
                            <div x-data="{
                                enabled: {{ isset($guideContent->is_enabled) && $guideContent->is_enabled == '1' ? 'true' : 'false' }},
                                btnEnabled: {{ isset($guideContent->is_button_enabled) && $guideContent->is_button_enabled == '1' ? 'true' : 'false' }}
                            }" class="form-box">
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
                                                            <label for="color-picker" data-color-picker></label>
                                                            <input id="color-picker" type="hidden"
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
                                            <hr />
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
                                                <div class="row" x-show="btnEnabled" x-transition>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title">
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
                                                                <label for="guide-btn-bg-color" data-color-picker></label>
                                                                <input id="guide-btn-bg-color" type="hidden"
                                                                    name="section_content[guide][btn_background_color]"
                                                                    data-color-picker-input
                                                                    value="{{ $guideContent->btn_background_color ?? '#1c4d99' }}"
                                                                    data-error="Background Color" inputmode="text" />
                                                                <input type="text"
                                                                    name="section_content[guide][btn_link]"
                                                                    value="{{ $guideContent->btn_link ?? '' }}"
                                                                    placeholder="" data-error="Button Link" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields">
                                                            <label class="title">Button Text & Text Color:</label>
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
                                            <hr />
                                        </div>
                                        <div class="col-12">
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
                            <x-seo-options :seo="$seo ?? null" :resource="'city'" />
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
                                            checked value="publish">
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft">
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
                                                <div class="upload-box show" data-upload-box>
                                                    <input type="file" name="featured_image"
                                                        data-error="Feature Image" id="featured_image"
                                                        class="upload-box__file d-none" accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="featured_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img" data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="#" class="mask" data-fancybox="gallery">
                                                        <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                            alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                    </a>
                                                    <input type="text" name="featured_image_alt_text" class="field"
                                                        placeholder="Enter alt text" value="Alt Text">
                                                </div>
                                            </div>
                                            <div data-error-message class="text-danger mt-2 d-none text-center">Please
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
                                                <div class="upload-box show" data-upload-box>
                                                    <input type="file" name="banner_image" data-error="Feature Image"
                                                        id="banner_image" class="upload-box__file d-none"
                                                        accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="banner_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img" data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="#" class="mask" data-fancybox="gallery">
                                                        <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                            alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                    </a>
                                                    <input type="text" name="banner_image_alt_text" class="field"
                                                        placeholder="Enter alt text" value="Alt Text">
                                                </div>
                                            </div>
                                            <div data-error-message class="text-danger mt-2 d-none text-center">Please
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
