@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-authors.edit', $author) }}
            <form action="{{ route('admin.tour-authors.update', $author->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit Author: {{ isset($title) ? $title : '' }}</h3>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Author Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Name <span class="text-danger">*</span> :</label>
                                        <input type="text" name="name" class="field"
                                            value="{{ old('name', $author->name) }}" placeholder="Name" data-error="Name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Publish</div>
                            </div>
                            <div class="form-box__body">
                                @if (!$author->system)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active" checked
                                            value="active">
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive">
                                        <label class="form-check-label" for="inactive">
                                            Inactive
                                        </label>
                                    </div>
                                @endif
                                <button class="themeBtn ms-auto mt-4">Save Changes</button>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Profile Image</div>
                            </div>
                            <div class="form-box__body">
                                <div class="form-fields">
                                    <div class="upload" data-upload>
                                        <div class="upload-box-wrapper">
                                            <div class="upload-box {{ empty($author->profile_image) ? 'show' : '' }}"
                                                data-upload-box>
                                                <input type="file" name="profile_image"
                                                    {{ empty($author->profile_image) ? '' : '' }} data-error="Profile Image"
                                                    id="profile_image" class="upload-box__file d-none" accept="image/*"
                                                    data-file-input>
                                                <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                </div>
                                                <label for="profile_image" class="upload-box__btn themeBtn">Upload
                                                    Image</label>
                                            </div>
                                            <div class="upload-box__img {{ !empty($author->profile_image) ? 'show' : '' }}"
                                                data-upload-img>
                                                <button type="button" class="delete-btn" data-delete-btn><i
                                                        class='bx bxs-trash-alt'></i></button>
                                                <a href="{{ asset($author->profile_image) }}" class="mask"
                                                    data-fancybox="gallery">
                                                    <img src="{{ asset($author->profile_image) }}"
                                                        alt="{{ $author->profile_image_alt_text }}" class="imgFluid"
                                                        data-upload-preview>
                                                </a>
                                                <input type="text" name="profile_image_alt_text" class="field"
                                                    placeholder="Enter alt text"
                                                    value="{{ $author->profile_image_alt_text }}">
                                            </div>
                                        </div>
                                        <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                            upload a
                                            valid image file
                                        </div>
                                        @error('profile_image')
                                            <div class="text-danger mt-2 text-center">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="dimensions text-center mt-3">
                                        <strong>Dimensions:</strong> 60 &times; 60
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
