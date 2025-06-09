@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.pages.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" id="validation-form">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Page Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title <span class="text-danger">*</span> :</label>
                                        <input type="text" name="title" class="field" value="{{ old('title') }}"
                                            placeholder="" data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Content <span class="text-danger">*</span> :</label>
                                        <textarea class="editor" name="content" data-placeholder="content" data-error="Content">
                                            {{ old('content') }}
                                        </textarea>
                                        @error('content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <x-seo-options :seo="$seo ?? null" :resource="'page'" />
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
                                        <input class="form-check-input" type="radio" name="status" id="publish" checked
                                            value="publish">
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
                                    <div class="title">Banner Image</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <div class="upload" data-upload>
                                            <div class="upload-box-wrapper">
                                                <div class="upload-box show" data-upload-box>
                                                    <input type="file" name="banner_image" data-error="Preview Image"
                                                        id="banner_image" class="upload-box__file d-none" accept="image/*"
                                                        data-file-input>
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
                                                        placeholder="Enter alt text" value="Banner Alt Text">
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
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Header / footer Style </div>
                                </div>
                                @php
                                    $headerStyles = ['normal'];
                                    $footerStyles = ['normal'];
                                @endphp
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Header <span class="text-danger">*</span> :</label>
                                        <select name="header_style" class="field" data-error="Header Style">
                                            <option value="" selected>Select</option>
                                            @foreach ($headerStyles as $headerStyle)
                                                <option value="{{ $headerStyle }}"
                                                    {{ old('header_style') == $headerStyle ? 'selected' : '' }}>
                                                    {{ $headerStyle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('header_style')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Footer <span class="text-danger">*</span> :</label>
                                        <select name="footer_style" class="field" data-error="Footer Style">
                                            <option value="" selected>Select</option>
                                            @foreach ($footerStyles as $footerStyle)
                                                <option value="{{ $footerStyle }}"
                                                    {{ old('footer_style') == $footerStyle ? 'selected' : '' }}>
                                                    {{ $footerStyle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('footer_style')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
