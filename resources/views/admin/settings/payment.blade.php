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
                    <form action="{{ route('admin.settings.update', ['resource' => 'payment']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $stripeSecretKey = $settings->get('stripe_secret_key');
                            $tabbySecretKey = $settings->get('tabby_secret_key');
                        @endphp

                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Payment Secret Keys</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row" x-data="{ stripeEnabled: {{ $settings->get('stripe_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="stripe_enabled" :value="stripeEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Stripe:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="stripe_enabled_switch" x-model="stripeEnabled">
                                                    <label class="form-check-label"
                                                        for="stripe_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="stripeEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Stripe Publishable Key</label>
                                            <input type="text" name="stripe_publishable_key"
                                                value="{{ $settings->get('stripe_publishable_key') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="stripeEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Stripe Secret Key</label>
                                            <input type="text" name="stripe_secret_key"
                                                value="{{ $settings->get('stripe_secret_key') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Title Field -->
                                    <div class="col-12 mt-3" x-show="stripeEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Stripe Title</label>
                                            <input type="text" name="stripe_title"
                                                value="{{ $settings->get('stripe_title') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Description Field -->
                                    <div class="col-12 mt-3" x-show="stripeEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Stripe Description</label>
                                            <input type="text" name="stripe_description"
                                                value="{{ $settings->get('stripe_description') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3" x-show="stripeEnabled" x-transition>
                                        @php
                                            $stripeLogo = $settings->get('stripe_logo');
                                            $stripeLogoAltText = $settings->get('stripe_logo_alt_text');
                                        @endphp
                                        <div class="form-fields">
                                            <label class="title">Logo:</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($stripeLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="stripe_logo" data-error="Feature Image"
                                                            id="stripe_logo" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="stripe_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($stripeLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($stripeLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($stripeLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="stripe_logo_alt_text" class="field"
                                                            placeholder="Enter alt text" value="{{ $stripeLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 70 &times; 30
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">

                                <div class="row" x-data="{ tabbyEnabled: {{ $settings->get('tabby_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="tabby_enabled" :value="tabbyEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Tabby:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="tabby_enabled_switch" x-model="tabbyEnabled">
                                                    <label class="form-check-label"
                                                        for="tabby_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-3" x-show="tabbyEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tabby Public Key</label>
                                            <input type="text" name="tabby_public_key"
                                                value="{{ $settings->get('tabby_public_key') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-3" x-show="tabbyEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tabby Secret Key</label>
                                            <input type="text" name="tabby_secret_key"
                                                value="{{ $settings->get('tabby_secret_key') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Title Field -->
                                    <div class="col-12 mt-3" x-show="tabbyEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tabby Title</label>
                                            <input type="text" name="tabby_title"
                                                value="{{ $settings->get('tabby_title') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Description Field -->
                                    <div class="col-12 mt-3" x-show="tabbyEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tabby Description</label>
                                            <input type="text" name="tabby_description"
                                                value="{{ $settings->get('tabby_description') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3" x-show="tabbyEnabled" x-transition>
                                        @php
                                            $tabbyLogo = $settings->get('tabby_logo');
                                            $tabbyLogoAltText = $settings->get('tabby_logo_alt_text');
                                        @endphp
                                        <div class="form-fields">
                                            <label class="title">Logo:</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($tabbyLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="tabby_logo"
                                                            data-error="Feature Image" id="tabby_logo"
                                                            class="upload-box__file d-none" accept="image/*"
                                                            data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="tabby_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($tabbyLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($tabbyLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($tabbyLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="tabby_logo_alt_text" class="field"
                                                            placeholder="Enter alt text" value="{{ $tabbyLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 70 &times; 30
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">

                                <div class="row" x-data="{ paypalEnabled: {{ $settings->get('paypal_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="paypal_enabled"
                                                    :value="paypalEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">PayPal:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="paypal_enabled_switch" x-model="paypalEnabled">
                                                    <label class="form-check-label"
                                                        for="paypal_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="paypalEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PayPal Client ID</label>
                                            <input type="text" name="paypal_client_id"
                                                value="{{ $settings->get('paypal_client_id') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="paypalEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PayPal Secret Key</label>
                                            <input type="text" name="paypal_secret_key"
                                                value="{{ $settings->get('paypal_secret_key') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Title Field -->
                                    <div class="col-12 mt-3" x-show="paypalEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PayPal Title</label>
                                            <input type="text" name="paypal_title"
                                                value="{{ $settings->get('paypal_title') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Description Field -->
                                    <div class="col-12 mt-3" x-show="paypalEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PayPal Description</label>
                                            <input type="text" name="paypal_description"
                                                value="{{ $settings->get('paypal_description') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-4 mt-3" x-show="paypalEnabled" x-transition>
                                        @php
                                            $paypalLogo = $settings->get('paypal_logo');
                                            $paypalLogoAltText = $settings->get('paypal_logo_alt_text');
                                        @endphp
                                        <div class="form-fields">
                                            <label class="title">Logo:</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($paypalLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="paypal_logo"
                                                            data-error="Feature Image" id="paypal_logo"
                                                            class="upload-box__file d-none" accept="image/*"
                                                            data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="paypal_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($paypalLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($paypalLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($paypalLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="paypal_logo_alt_text" class="field"
                                                            placeholder="Enter alt text"
                                                            value="{{ $paypalLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 70 &times; 30
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">
                                <div class="row" x-data="{ tamaraEnabled: {{ $settings->get('tamara_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="tamara_enabled"
                                                    :value="tamaraEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Tamara:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="tamara_enabled_switch" x-model="tamaraEnabled">
                                                    <label class="form-check-label"
                                                        for="tamara_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-3" x-show="tamaraEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tamara Public Key</label>
                                            <input type="text" name="tamara_public_key"
                                                value="{{ $settings->get('tamara_public_key') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-3" x-show="tamaraEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tamara Secret Key</label>
                                            <input type="text" name="tamara_secret_key"
                                                value="{{ $settings->get('tamara_secret_key') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Title Field -->
                                    <div class="col-12 mt-3" x-show="tamaraEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tamara Title</label>
                                            <input type="text" name="tamara_title"
                                                value="{{ $settings->get('tamara_title') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Description Field -->
                                    <div class="col-12 mt-3" x-show="tamaraEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Tamara Description</label>
                                            <input type="text" name="tamara_description"
                                                value="{{ $settings->get('tamara_description') }}" class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3" x-show="tamaraEnabled" x-transition>
                                        @php
                                            $tamaraLogo = $settings->get('tamara_logo');
                                            $tamaraLogoAltText = $settings->get('tamara_logo_alt_text');
                                        @endphp
                                        <div class="form-fields">
                                            <label class="title">Logo:</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($tamaraLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="tamara_logo"
                                                            data-error="Feature Image" id="tamara_logo"
                                                            class="upload-box__file d-none" accept="image/*"
                                                            data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="tamara_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($tamaraLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($tamaraLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($tamaraLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="tamara_logo_alt_text" class="field"
                                                            placeholder="Enter alt text"
                                                            value="{{ $tamaraLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 70 &times; 30
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--    <hr class="my-5">
                                <div class="row" x-data="{ pointCheckoutEnabled: {{ $settings->get('pointcheckout_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="pointcheckout_enabled"
                                                    :value="pointCheckoutEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Point Checkout:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="pointcheckout_enabled_switch" value="1"
                                                        name="pointcheckout_enabled" x-model="pointCheckoutEnabled">
                                                    <label class="form-check-label"
                                                        for="pointcheckout_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="pointCheckoutEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PointCheckout API Key</label>
                                            <input type="text" name="pointcheckout_api_key"
                                                value="{{ $settings->get('pointcheckout_api_key') }}" class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="pointCheckoutEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PointCheckout API Secret</label>
                                            <input type="text" name="pointcheckout_api_secret"
                                                value="{{ $settings->get('pointcheckout_api_secret') }}" class="field">
                                        </div>
                                    </div>
                                </div>

                                --}}
                                {{-- <hr class="my-5">
                                <div class="row" x-data="{ postpayEnabled: {{ $settings->get('postpay_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="postpay_enabled"
                                                    :value="postpayEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">PostPay:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="postpay_enabled_switch" x-model="postpayEnabled">
                                                    <label class="form-check-label"
                                                        for="postpay_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                     <div class="col-md-6 col-12 mb-3" x-show="postpayEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">PostPay Public Key</label>
                                            <input type="text" name="postpay_public_key"
                                                value="{{ $settings->get('postpay_public_key') }}" class="field">
                                        </div>
                                    </div> 
                                <div class="col-md-12 col-12 mb-3" x-show="postpayEnabled" x-transition>
                                    <div class="form-fields">
                                        <label class="title text-dark">PostPay Auth Key</label>
                                        <input type="text" name="postpay_secret_key"
                                            value="{{ $settings->get('postpay_secret_key') }}" class="field">
                                    </div>
                                </div>
                            </div> --}}

                                <hr class="my-5">
                                <div class="row" x-data="{ cashEnabled: {{ $settings->get('cash_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="cash_enabled" :value="cashEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Cash on Pickup:</div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="cash_enabled_switch" x-model="cashEnabled">
                                                    <label class="form-check-label"
                                                        for="cash_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="cashEnabled" x-transition>
                                        <div class="form-fields">
                                            <div class="form-check">
                                                <input type="hidden" name="cash_discount_applicable" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="cash_discount_applicable" id="cash_discount_applicable"
                                                    value="1"
                                                    {{ $settings->get('cash_discount_applicable') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="cash_discount_applicable">Discount
                                                    Applicable</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Title Field -->
                                    <div class="col-12 mt-3" x-show="cashEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Cash on Pickup Title</label>
                                            <input type="text" name="cash_title"
                                                value="{{ $settings->get('cash_title') }}" class="field">
                                        </div>
                                    </div>

                                    <!-- New Description Field -->
                                    <div class="col-12 mt-3" x-show="cashEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Cash on Pickup Description</label>
                                            <input type="text" name="cash_description"
                                                value="{{ $settings->get('cash_description') }}" class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3" x-show="cashEnabled" x-transition>
                                        @php
                                            $cashLogo = $settings->get('cash_logo');
                                            $cashLogoAltText = $settings->get('cash_logo_alt_text');
                                        @endphp
                                        <div class="form-fields">
                                            <label class="title">Logo:</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($cashLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="cash_logo"
                                                            data-error="Feature Image" id="cash_logo"
                                                            class="upload-box__file d-none" accept="image/*"
                                                            data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="cash_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($cashLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($cashLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($cashLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="cash_logo_alt_text" class="field"
                                                            placeholder="Enter alt text"
                                                            value="{{ $cashLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 70 &times; 30
                                            </div>
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
