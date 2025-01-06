@extends('admin.dash_layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.testimonials.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
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
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active" checked
                                            value="active">
                                        <label class="form-check-label" for="active">
                                            active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive">
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
                                                <div class="upload-box show" data-upload-box>
                                                    <input type="file" name="featured_image" data-error="Feature Image"
                                                        id="featured_image" class="upload-box__file d-none" accept="image/*"
                                                        data-file-input>
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
                                            @error('featured_image_alt_text')
                                                <div class="text-danger mt-2 text-center">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            @error('featured_image')
                                                <div class="text-danger mt-2 text-center">
                                                    {{ $message }}
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
