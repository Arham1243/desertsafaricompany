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
                                </div>
                                <hr class="my-5">

                                <div class="row" x-data="{ paypalEnabled: {{ $settings->get('paypal_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="paypal_enabled" :value="paypalEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">PayPal:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="paypal_enabled_switch" value="1" name="paypal_enabled"
                                                        x-model="paypalEnabled">
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
                                </div> --}}
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
