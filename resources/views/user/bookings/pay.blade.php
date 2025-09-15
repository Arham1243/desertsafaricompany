@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.bookings.pay', $booking) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Order ID: #{{ $booking->id }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('user.bookings.paymentProcess', $booking->id) }}" method="POST"
                enctype="multipart/form-data" id="validation-form">
                @csrf
                @method('POST')
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Choose a Payment Method</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="details-box">
                                        <div class="details-box__body details-box__body--pay">
                                            <ul class="payment-options">

                                                @if (isset($settings['tabby_enabled']) && (int) $settings['tabby_enabled'] === 1)
                                                    <!-- Buy Now Pay Later - Tabby -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="tabby" id="tabby" />
                                                        <label for="tabby" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/3.png') }}"
                                                                        alt="tabby" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">Tabby - Buy Now, Pay Later (4
                                                                    instalments)</div>
                                                                <div class="note">
                                                                    No credit card required. Valid for orders AED 100 or
                                                                    more.
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif

                                                @if (isset($settings['tamara_enabled']) && (int) $settings['tamara_enabled'] === 1)
                                                    <!-- Buy Now Pay Later - Tamara -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="tamara" id="tamara" />
                                                        <label for="tamara" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/6.png') }}"
                                                                        alt="tamara" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">Tamara - Pay Later</div>
                                                                <div class="note">
                                                                    Split your payment into 2–3 instalments. No interest.
                                                                    Simple &
                                                                    secure.
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif

                                                @if (isset($settings['stripe_enabled']) && (int) $settings['stripe_enabled'] === 1)
                                                    <!-- Card Payments - Stripe -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="stripe" id="stripe" />
                                                        <label for="stripe" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/1.png') }}"
                                                                        alt="stripe" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">Credit/Debit Card (Stripe)</div>
                                                                <div class="note">
                                                                    Visa, Mastercard, American Express, Discover, Diners
                                                                    Club, JCB
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif

                                                @if (isset($settings['paypal_enabled']) && (int) $settings['paypal_enabled'] === 1)
                                                    <!-- Card Payments - PayPal -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="paypal" id="paypal" />
                                                        <label for="paypal" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/5.png') }}"
                                                                        alt="paypal" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">PayPal</div>
                                                                <div class="note">
                                                                    Secure payments via PayPal wallet or linked cards.
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif

                                                @if (isset($settings['postpay_enabled']) && (int) $settings['postpay_enabled'] === 1)
                                                    <!-- Postpay (optional BNPL) -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="postpay" id="postpay" />
                                                        <label for="postpay" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/2.png') }}"
                                                                        alt="postpay" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">Postpay</div>
                                                                <div class="note">
                                                                    Pay later at checkout. Available for eligible orders.
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif

                                                @if (isset($settings['pointcheckout_enabled']) && (int) $settings['pointcheckout_enabled'] === 1)
                                                    <!-- Card Payments - pointCheckout -->
                                                    <li class="payment-option">
                                                        <input class="payment-option__input" type="radio"
                                                            name="payment_type" value="pointCheckout"
                                                            id="pointCheckout" />
                                                        <label for="pointCheckout" class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ asset('frontend/assets/images/methods/7.svg') }}"
                                                                        alt="pointCheckout" class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">Loyalty Points or Card</div>
                                                                <div class="note">
                                                                    Use reward points or pay by card
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="title">Pay Now</div>
                                </div>
                                <div class="form-box__body">
                                    <button class="themeBtn ms-auto mt-4">Pay Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
