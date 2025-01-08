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
                <div class="custom-form">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="placeholder-user">
                                <a data-fancybox="gallery"
                                    href="{{ $user->avatar ?? asset('admin/assets/images/placeholder-user.png') }}"
                                    class="placeholder-user__img">
                                    <img src="{{ $user->avatar ?? asset('admin/assets/images/placeholder-user.png') }}"
                                        alt="image" class="imgFluid" id="profile-preview" loading="lazy">
                                </a>
                                {{-- <input type="file" name="profile_img" 
                                id="profile-img"
                                    onchange="showImage(this, 'profile-preview', 'filename-preview');" class="d-none"
                                    accept="image/*"> 
                                <div class="placeholder-user__name" id="filename-preview">Profile Image</div>
                                --}}
                            </div>
                        </div>
                        @php
                            $columns_to_ignore = [
                                'avatar',
                                'social_id',
                                'social_token',
                                'email_verified',
                                'is_active',
                                'email_verification_token',
                            ];
                            $files_columns = [];
                        @endphp
                        @foreach ($fields as $field)
                            @if (!in_array($field, $columns_to_ignore) && $user->$field != null)
                                <div class="col-md-6">
                                    <div class="form-fields">
                                        <label class="title">
                                            {{ ucwords(str_replace('_', ' ', $field)) }}:
                                        </label>
                                        <input type="text" class="field" name="{{ $field }}"
                                            value="{{ old($field, $user->$field) }}" readonly>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @if ($user->signup_method == 'email')
                            <div class="col-md-6">
                                <div class="form-fields">
                                    <label class="title">Email Verified:</label>
                                    <input type="text" class="field"
                                        value="{{ $user->email_verified == 1 ? 'Yes' : 'No' }}" readonly>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-fields">
                            <a href="{{ route('admin.usersListing') }}" class="themeBtn themeBtn--center">Go back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
