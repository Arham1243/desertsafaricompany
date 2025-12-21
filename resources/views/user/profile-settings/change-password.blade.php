@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.profile.changePassword') }}
            <form action="{{ route('user.profile.updatePassword', $user->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Change Password</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Password</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Current Password<span
                                                        class="text-danger">*</span></label>
                                                <div class="passwood-icon-wrapper position-relative">
                                                    <input type="password" id="current_password" name="current_password"
                                                        class="field" required="">
                                                    <span class="icon" data-target="current_password"
                                                        class="toggle-password" onclick="togglePassword(event)"><i
                                                            class='bx bxs-show'></i></span>
                                                </div>
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">New Password<span class="text-danger">*</span>
                                                </label>
                                                <div class="passwood-icon-wrapper position-relative">
                                                    <input type="password" id="new_password" name="new_password"
                                                        class="field" required="">
                                                    <span class="icon" data-target="new_password" class="toggle-password"
                                                        onclick="togglePassword(event)"><i class='bx bxs-show'></i></span>
                                                </div>
                                                @error('new_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <button class="themeBtn ms-auto mt-4">UpdatePassword</button>
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
@push('css')
    <style>
        .passwood-icon-wrapper .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 1rem;
            font-size: 1.5rem;
            color: var(--color-primary);
            cursor: pointer;
        }
    </style>
@endpush
@push('js')
    <script>
        function togglePassword(event) {
            const toggleButton = event.currentTarget;
            const passwordFieldId = toggleButton.getAttribute("data-target");
            const passwordField = document.getElementById(passwordFieldId);
            const passwordIcon = toggleButton.querySelector("i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.className = "bx bxs-hide";
            } else {
                passwordField.type = "password";
                passwordIcon.className = "bx bxs-show";
            }
        }
    </script>
@endpush
