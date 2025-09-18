@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.blogs.edit', $blog) }}
            <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Blog: {{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildBlogDetailUrl($blog, false, true) }}/</div>
                                    <input value="{{ $blog->slug ?? '#' }}" type="button" class="link permalink-input"
                                        data-field-id="slug">
                                    <input type="hidden" id="slug" value="{{ $blog->slug ?? '#' }}" name="slug">
                                </div>
                            </div>
                        </div>
                        <a href="{{ buildBlogDetailUrl($blog) }}" target="_blank" class="themeBtn">View
                            Blog</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Blog Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $blog->title) }}" placeholder="New Blog"
                                            data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <x-admin.city-filter-by-country :isCountryRequired="true" :isCityRequired="true" :countries="$countries"
                                        :cities="$cities" wrapperClass="col-md-12 row pe-0"
                                        selectedCountryId="{{ old('country_id', $blog->country_id) }}"
                                        selectedCityId="{{ old('city_id', $blog->city_id) }}"
                                        countryColClass="col-md-6 mb-4 pe-0" cityColClass="col-md-6 mb-4"
                                        countryName="country_id" cityName="city_id" />

                                    @php
                                        $jsonContent = json_decode($blog->json_content, true) ?? [];
                                    @endphp

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="form-fields">
                                                <label class="title">Select Top Featured Tours:</label>
                                                <select name="json_content[top_featured_tour_ids][]" class="select2-select"
                                                    multiple placeholder="Select">
                                                    @foreach ($tours as $topTour)
                                                        <option value="{{ $topTour->id }}"
                                                            {{ in_array($topTour->id, $jsonContent['top_featured_tour_ids'] ?? []) ? 'selected' : '' }}>
                                                            {{ $topTour->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-fields">
                                                <label class="title">Select Bottom Featured Tours:</label>
                                                <select name="json_content[bottom_featured_tour_ids][]"
                                                    class="select2-select" multiple placeholder="Select">
                                                    @foreach ($tours as $bottomTour)
                                                        <option value="{{ $bottomTour->id }}"
                                                            {{ in_array($bottomTour->id, $jsonContent['bottom_featured_tour_ids'] ?? []) ? 'selected' : '' }}>
                                                            {{ $bottomTour->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-fields">
                                        <label class="title d-flex align-items-center gap-2 lh-1">
                                            Short Description Content
                                            <button data-bs-placement="top" title="Used for card description" type="button"
                                                data-tooltip="tooltip" class="tooltip-lg">
                                                <i class='bx bxs-info-circle'></i>
                                            </button>
                                        </label>
                                        <textarea class="field" name="short_description" data-placeholder="content" data-error="Content" rows="6"> {{ old('short_description', $blog->short_description) }} </textarea>
                                        @error('short_description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {!! old('content', $blog->content) !!}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Lines to Display Before "Read More" </label>
                                        <input oninput="this.value = Math.abs(this.value)" type="number" min="0"
                                            name="content_line_limit" class="field"
                                            value="{{ old('content_line_limit', $blog->content_line_limit) }}"
                                            data-error="content_line_limit">
                                    </div>
                                </div>
                            </div>

                            @php
                                $mayAlsoLike = json_decode($blog->may_also_like, true) ?? [];
                                $enabled = isset($mayAlsoLike['enabled']) ? (int) $mayAlsoLike['enabled'] : 1;
                            @endphp
                            <div class="form-box" x-data="{ enabled: {{ $enabled === 1 ? 'true' : 'false' }} }">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">You may also like</div>
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="may_also_like[enabled]" :value="enabled ? 1 : 0">
                                            <input class="form-check-input" type="checkbox" x-model="enabled"
                                                id="may_also_like_enabled">
                                            <label class="form-check-label" for="may_also_like_enabled"
                                                x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-box__body" x-show="enabled" x-transition>
                                    <div class="form-fields">
                                        <div x-data="{ type: '{{ $mayAlsoLike['type'] ?? 'category_based' }}' }">
                                            <div class="d-flex align-items-center gap-5 px-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        id="may_also_like_category" x-model="type"
                                                        name="may_also_like[type]" value="category_based">
                                                    <label class="form-check-label" for="may_also_like_category">Category
                                                        Based</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        id="may_also_like_latest" x-model="type"
                                                        name="may_also_like[type]" value="latest">
                                                    <label class="form-check-label"
                                                        for="may_also_like_latest">Latest</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        id="may_also_like_custom" x-model="type"
                                                        name="may_also_like[type]" value="custom">
                                                    <label class="form-check-label"
                                                        for="may_also_like_custom">Custom</label>
                                                </div>
                                            </div>

                                            <div x-show="type === 'custom'" class="mt-3" x-transition>
                                                <label class="title">Select Blogs:</label>
                                                <select name="may_also_like[custom_ids][]" class="select2-select"
                                                    multiple>
                                                    @foreach ($dropdownBlogs as $dropdownBlog)
                                                        <option value="{{ $dropdownBlog->id }}"
                                                            {{ in_array($dropdownBlog->id, $mayAlsoLike['custom_ids'] ?? []) ? 'selected' : '' }}>
                                                            {{ $dropdownBlog->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <x-seo-options :seo="$seo ?? null" :resource="buildBlogDetailUrl($blog, true, false)" :slug="''" />
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
                                            {{ old('status', $blog->status ?? '') == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft"
                                            {{ old('status', $blog->status ?? '') == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Options</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Categories <span class="text-danger">*</span> :</label>
                                        <select name="category_id" class="select2-select" data-error="Category"
                                            data-required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-fields">
                                        <label class="title">Tags <span class="text-danger">*</span> :</label>

                                        <select name="tags_ids[]" class="select2-select" multiple
                                            placeholder="Select tags">
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}"
                                                    {{ in_array($tag->id, old('tags_ids', $blog->tags->pluck('id')->toArray()) ?? []) ? 'selected' : '' }}>
                                                    {{ $tag->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('tags_ids')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

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
                                                <div class="upload-box {{ empty($blog->featured_image) ? 'show' : '' }}"
                                                    data-upload-box>
                                                    <input type="file" name="featured_image"
                                                        {{ empty($blog->featured_image) ? '' : '' }}
                                                        data-error="Feature Image" id="featured_image"
                                                        class="upload-box__file d-none" accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="featured_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img {{ !empty($blog->featured_image) ? 'show' : '' }}"
                                                    data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="{{ asset($blog->featured_image) }}" class="mask"
                                                        data-fancybox="gallery">
                                                        <img src="{{ asset($blog->featured_image) }}"
                                                            alt="{{ $blog->feature_image_alt_text }}" class="imgFluid"
                                                            data-upload-preview>
                                                    </a>
                                                    <input type="text" name="feature_image_alt_text" class="field"
                                                        placeholder="Enter alt text"
                                                        value="{{ $blog->feature_image_alt_text }}">
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
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
