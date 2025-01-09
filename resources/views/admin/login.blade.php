@extends('admin.layouts.main')
@php
    $is_login = true;
@endphp
@section('content')
    <div class="login-wrapper">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-md-5">
                    <div class="login-content">
                        <div class="login-content__title">Admin Login</div>
                        <p>Welcome Back, please login to your account.</p>
                        <form class="login-content__form" method="POST" action="{{ route('admin.performLogin') }}">
                            @csrf
                            <div class="fields">
                                <label for="email" class="title">Email Address <span
                                        class="text-danger">*</span></label>
                                <input class="@if ($errors->has('email')) is-invalid @endif" type="email"
                                    name="email" id="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fields">
                                <label for="password" class="title">Password <span class="text-danger">*</span></label>
                                <input class="@if ($errors->has('password')) is-invalid @endif" type="password"
                                    name="password" id="password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fields mt-4">
                                <button class="themeBtn">
                                    <i class='btn-loader bx-spin d-none'></i>
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="login-image">
                        <img src="{{ asset('admin/assets/images/login.jpg') }}" alt="Login" class="imgFluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.querySelector('.login-content__form').addEventListener('submit', function() {
            document.querySelector('.themeBtn').disabled = true;
            document.querySelector('.btn-loader').classList.remove('d-none');
        });
    </script>
@endpush
