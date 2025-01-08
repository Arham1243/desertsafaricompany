@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            <div class="custom-sec">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.saveLogo') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="placeholder-user">
                                <label for="profile-img" class="placeholder-user__img">
                                    <img src="{{ asset($logo->img_path ?? 'admin/assets/images/placeholder-logo.png') }}"
                                        alt="image" class="imgFluid" id="profile-preview" loading="lazy">
                                </label>
                                <input type="file" name="logo" id="profile-img"
                                    onchange="showImage(this, 'profile-preview', 'filename-preview');" class="d-none"
                                    accept="image/*">
                                <div class="placeholder-user__name" id="filename-preview">Current Logo</div>
                                @error('record')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-fields">
                                <button class="themeBtn themeBtn--center">Save Changes</button>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <style type="text/css">
        .placeholder-user__img img {
            object-fit: contain
        }
    </style>
@endsection
