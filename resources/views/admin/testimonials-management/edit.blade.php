@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.testimonials.edit', $item) }}
            <form action="{{ route('admin.testimonials.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Testimonial: {{ isset($title) ? $title : '' }}</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Testimonial Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $item->title) }}" placeholder="New Blog"
                                            data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {!! old('content', $item->content) !!}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Rating <span class="text-danger">*</span> :</label>
                                        <div class="rating">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="star-{{ $i }}" name="rating"
                                                    value="{{ $i }}"
                                                    {{ $item->rating == $i ? 'checked' : '' }} />
                                                <label for="star-{{ $i }}"
                                                    title="Rating {{ $i }}"></label>
                                            @endfor
                                        </div>

                                        @error('rating')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields mt-3">
                                        <div class="multiple-upload" data-upload-multiple>
                                            <input type="file" class="gallery-input d-none" multiple
                                                data-upload-multiple-input accept="image/*" id="banners" name="gallery[]">
                                            <label class="multiple-upload__btn themeBtn" for="banners">
                                                <i class='bx bx-plus'></i>
                                                Other images
                                            </label>
                                            <div class="dimensions mt-3">
                                                <strong>Dimensions:</strong> 265 &times; 155
                                            </div>
                                            <ul class="multiple-upload__imgs" data-upload-multiple-images>
                                            </ul>
                                            <div class="text-danger error-message d-none" data-upload-multiple-error>
                                            </div>
                                        </div>
                                    </div>

                                    @if (!$item->media->isEmpty())
                                        <div class="form-fields mt-3">
                                            <label class="title">Current Other images:</label>
                                            <ul class="multiple-upload__imgs">
                                                @foreach ($item->media as $media)
                                                    <li class="single-image">
                                                        <a href="{{ route('admin.media.destroy', $media->id) }}"
                                                            onclick="return confirm('Are you sure you want to delete this media?')"
                                                            class="delete-btn">
                                                            <i class='bx bxs-trash-alt'></i>
                                                        </a>
                                                        <a class="mask" href="{{ asset($media->file_path) }}"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($media->file_path) }}" class="imgFluid"
                                                                alt="{{ $media->alt_text }}" />
                                                        </a>
                                                        <input type="text" value="{{ $media->alt_text }}" class="field"
                                                            readonly>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    @endif
                                </div>
                            </div>
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
                                        <input class="form-check-input" type="radio" name="status" id="active"
                                            value="active"
                                            {{ old('status', $item->status ?? '') == 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive"
                                            {{ old('status', $item->status ?? '') == 'inactive' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactive">
                                            inactive
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
                                            <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                upload a
                                                valid image file
                                            </div>
                                            @error('featured_image_alt_text')
                                                <div class="text-danger mt-2 text-center">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            @error('featured_image')
                                                <div class="text-danger mt-2 text-center">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="dimensions text-center mt-3">
                                            <strong>Dimensions:</strong> 265 &times; 155
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
