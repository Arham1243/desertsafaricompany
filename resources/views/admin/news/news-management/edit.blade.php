@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.news.edit', $news) }}
            <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit News: {{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildNewsDetailUrl($news, false) }}/</div>
                                    <input value="{{ $news->slug ?? '#' }}" type="button" class="link permalink-input"
                                        data-field-id="slug">
                                    <input type="hidden" id="slug" value="{{ $news->slug ?? '#' }}" name="slug">
                                </div>
                            </div>
                        </div>
                        <a href="{{ buildNewsDetailUrl($news) }}" target="_blank" class="themeBtn">View
                            News</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">News Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $news->title) }}" placeholder="New Blog"
                                            data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title d-flex align-items-center gap-2 lh-1">
                                            Short Description Content
                                            <button data-bs-placement="top" title="Used for card description" type="button"
                                                data-tooltip="tooltip" class="tooltip-lg">
                                                <i class='bx bxs-info-circle'></i>
                                            </button>
                                        </label>
                                        <textarea class="field" name="short_description" data-placeholder="content" data-error="Content" rows="6"> {{ old('short_description', $news->short_description) }} </textarea>
                                        @error('short_description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {!! old('content', $news->content) !!}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <x-seo-options :seo="$seo ?? null" :resource="buildNewsDetailUrl($news, true, false)" :slug="''" />
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
                                            {{ old('status', $news->status ?? '') == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="draft"
                                            value="draft"
                                            {{ old('status', $news->status ?? '') == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                            {{-- <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Author Settings</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Author <span class="text-danger">*</span> :</label>
                                        <select class="select2-select" name="user_id" data-error="Author">
                                            <option value="" selected>Select</option>
                                            @foreach ($users as $users)
                                                <option value="{{ $users->id }}"
                                                    {{ old('user_id', $news->user_id) == $users->id ? 'selected' : '' }}>
                                                    {{ $users->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Options</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Categories <span class="text-danger">*</span> :</label>
                                        <select data-required name="category_id" class="select2-select"
                                            data-error="Category">
                                            <option value="" selected>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $news->category_id ?? '') == $category->id ? 'selected' : '' }}>
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
                                                    {{ in_array($tag->id, old('tags_ids', $news->tags->pluck('id')->toArray()) ?? []) ? 'selected' : '' }}>
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
                                                <div class="upload-box {{ empty($news->featured_image) ? 'show' : '' }}"
                                                    data-upload-box>
                                                    <input type="file" name="featured_image"
                                                        {{ empty($news->featured_image) ? '' : '' }}
                                                        data-error="Feature Image" id="featured_image"
                                                        class="upload-box__file d-none" accept="image/*" data-file-input>
                                                    <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                    </div>
                                                    <label for="featured_image" class="upload-box__btn themeBtn">Upload
                                                        Image</label>
                                                </div>
                                                <div class="upload-box__img {{ !empty($news->featured_image) ? 'show' : '' }}"
                                                    data-upload-img>
                                                    <button type="button" class="delete-btn" data-delete-btn><i
                                                            class='bx bxs-trash-alt'></i></button>
                                                    <a href="{{ asset($news->featured_image) }}" class="mask"
                                                        data-fancybox="gallery">
                                                        <img src="{{ asset($news->featured_image) }}"
                                                            alt="{{ $news->feature_image_alt_text }}" class="imgFluid"
                                                            data-upload-preview>
                                                    </a>
                                                    <input type="text" name="feature_image_alt_text" class="field"
                                                        placeholder="Enter alt text"
                                                        value="{{ $news->feature_image_alt_text }}">
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
