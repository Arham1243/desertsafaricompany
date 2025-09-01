@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.settings.index') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Settings</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'email']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="title">Email Settings</label>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Admin Email:</label>
                                            <input type="email" name="admin_email"
                                                value="{{ $settings->get('admin_email') ?? '' }}" class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fields mb-3">
                                            <label class="title">Email From Name:</label>
                                            <input type="text" name="email_from_name"
                                                value="{{ $settings->get('email_from_name') ?? env('MAIL_FROM_NAME') }}"
                                                class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fields mb-3">
                                            <label class="title">Email From Address:</label>
                                            <input type="email" name="email_from_address"
                                                value="{{ $settings->get('email_from_address') ?? env('MAIL_FROM_ADDRESS') }}"
                                                class="field">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="title">Email Template for Password Reset </label>
                                    <a href="{{ asset('admin/assets/images/emails/password-reset.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span>Greeting Text</span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for user name:</span>
                                                    <code class="text-nowrap text-lowercase">{name}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{name}">
                                                        Copy
                                                    </button>
                                                </span></label>
                                            <input type="text" name="password_reset_greeting"
                                                value="{{ $settings->get('password_reset_greeting') ?? 'Hi {name}' }}"
                                                class="field">

                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title">Body Text:</label>
                                            <textarea name="password_reset_body" rows="6" class="field">{{ $settings->get('password_reset_body') ?? 'Looks like you forgot your password. No worries We\'ll help you reset it just click the button below' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span> Footer Text
                                                </span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for year:</span>
                                                    <code class="text-nowrap text-lowercase">{year}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{year}">
                                                        Copy
                                                    </button>
                                                </span>
                                            </label>
                                            <input type="text" name="password_reset_footer"
                                                value="{!! $settings->get('password_reset_footer') ?? '&copy; Desert Safari Company {year} - All rights reserved.' !!}" class="field">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="title">Email Template for Email Verification</label>
                                    <a href="{{ asset('admin/assets/images/emails/email-verification.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span>Greeting Text</span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for user name:</span>
                                                    <code class="text-nowrap text-lowercase">{name}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{name}">
                                                        Copy
                                                    </button>
                                                </span></label>
                                            <input type="text" name="email_verification_greeting"
                                                value="{{ $settings->get('email_verification_greeting') ?? 'Hi {name}' }}"
                                                class="field">

                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title">Body Text:</label>
                                            <textarea name="email_verification_body" rows="6" class="field">{{ $settings->get('email_verification_body') ?? 'We are excited to have you join our community of travel enthusiasts! To start exploring and booking amazing tours, please confirm your email address.' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span> Footer Text
                                                </span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for year:</span>
                                                    <code class="text-nowrap text-lowercase">{year}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{year}">
                                                        Copy
                                                    </button>
                                                </span>
                                            </label>
                                            <input type="text" name="email_verification_footer"
                                                value="{!! $settings->get('email_verification_footer') ?? '&copy; Desert Safari Company {year} - All rights reserved.' !!}" class="field">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="title">Email Template for Customer Order Confirmed</label>
                                    <a href="{{ asset('admin/assets/images/emails/customer-order-confirmed.png') }}"
                                        data-fancybox="gallery" class="themeBtn p-2">
                                        <i class='bx bxs-show'></i>
                                    </a>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span>Greeting Text</span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for user name:</span>
                                                    <code class="text-nowrap text-lowercase">{name}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{name}">
                                                        Copy
                                                    </button>
                                                </span></label>
                                            <input type="text" name="customer_order_confirmed_greeting"
                                                value="{{ $settings->get('customer_order_confirmed_greeting') ?? 'Hi {name}' }}"
                                                class="field">

                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title">Body Text:</label>
                                            <textarea name="customer_order_confirmed_body" rows="6" class="field">{{ $settings->get('customer_order_confirmed_body') ?? 'Thank you for your order! Your order has teen confirmed' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-fields">
                                            <label class="title d-flex align-items-center">
                                                <span> Footer Text
                                                </span>
                                                <span class="small text-muted ms-2 d-inline-flex align-items-center gap-2">
                                                    <span>Use variable for year:</span>
                                                    <code class="text-nowrap text-lowercase">{year}</code>
                                                    <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                        text-to-copy="{year}">
                                                        Copy
                                                    </button>
                                                </span>
                                            </label>
                                            <input type="text" name="customer_order_confirmed_footer"
                                                value="{!! $settings->get('customer_order_confirmed_footer') ??
                                                    '&copy; Desert Safari Company {year} - All rights reserved.' !!}" class="field">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button style=" position: sticky; bottom: 1rem; " class="themeBtn ms-auto ">Save Changes <i
                                class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('click', e => {
            if (e.target.matches('.copy-btn')) {
                const text = e.target.getAttribute('text-to-copy')
                if (text) navigator.clipboard.writeText(text)
            }
        })
    </script>
@endpush
