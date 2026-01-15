@extends('frontend.layouts.main')
@section('content')
    @php
        $seo = (object) [
            'seo_title' => 'Checkout',
            'is_seo_index' => true,
            'seo_description' => null,
            'canonical' => null,
            'fb_title' => null,
            'fb_description' => null,
            'fb_featured_image' => null,
            'tw_title' => null,
            'tw_description' => null,
            'tw_featured_image' => null,
            'schema' => null,
        ];
        $countries = [
            'afghanistan',
            'albania',
            'algeria',
            'american samoa',
            'andorra',
            'angola',
            'anguilla',
            'antigua and barbuda',
            'argentina',
            'armenia',
            'aruba',
            'australia',
            'austria',
            'azerbaijan',
            'bahamas',
            'bahrain',
            'bangladesh',
            'barbados',
            'belarus',
            'belgium',
            'belize',
            'benin',
            'bermuda',
            'bhutan',
            'bolivia',
            'bosnia and herzegovina',
            'botswana',
            'brazil',
            'british indian ocean territory',
            'brunei',
            'bulgaria',
            'burkina faso',
            'burundi',
            'cambodia',
            'cameroon',
            'canada',
            'cape verde',
            'cayman islands',
            'central african republic',
            'chad',
            'chile',
            'china',
            'colombia',
            'comoros',
            'congo',
            'costa rica',
            'croatia',
            'cuba',
            'cyprus',
            'czech republic',
            'denmark',
            'djibouti',
            'dominica',
            'dominican republic',
            'ecuador',
            'egypt',
            'el salvador',
            'equatorial guinea',
            'eritrea',
            'estonia',
            'eswatini',
            'ethiopia',
            'fiji',
            'finland',
            'france',
            'gabon',
            'gambia',
            'georgia',
            'germany',
            'ghana',
            'greece',
            'grenada',
            'guatemala',
            'guinea',
            'guinea bissau',
            'guyana',
            'haiti',
            'honduras',
            'hungary',
            'iceland',
            'india',
            'indonesia',
            'iran',
            'iraq',
            'ireland',
            'israel',
            'italy',
            'jamaica',
            'japan',
            'jordan',
            'kazakhstan',
            'kenya',
            'kiribati',
            'kuwait',
            'kyrgyzstan',
            'laos',
            'latvia',
            'lebanon',
            'lesotho',
            'liberia',
            'libya',
            'liechtenstein',
            'lithuania',
            'luxembourg',
            'madagascar',
            'malawi',
            'malaysia',
            'maldives',
            'mali',
            'malta',
            'marshall islands',
            'mauritania',
            'mauritius',
            'mexico',
            'micronesia',
            'moldova',
            'monaco',
            'mongolia',
            'montenegro',
            'morocco',
            'mozambique',
            'myanmar',
            'namibia',
            'nauru',
            'nepal',
            'netherlands',
            'new zealand',
            'nicaragua',
            'niger',
            'nigeria',
            'north macedonia',
            'norway',
            'oman',
            'pakistan',
            'palau',
            'palestine',
            'panama',
            'papua new guinea',
            'paraguay',
            'peru',
            'philippines',
            'poland',
            'portugal',
            'qatar',
            'romania',
            'russia',
            'rwanda',
            'saudi arabia',
            'senegal',
            'serbia',
            'seychelles',
            'sierra leone',
            'singapore',
            'slovakia',
            'slovenia',
            'solomon islands',
            'somalia',
            'south africa',
            'spain',
            'sri lanka',
            'sudan',
            'suriname',
            'sweden',
            'switzerland',
            'syria',
            'taiwan',
            'tajikistan',
            'tanzania',
            'thailand',
            'timor leste',
            'togo',
            'tonga',
            'trinidad and tobago',
            'tunisia',
            'turkey',
            'turkmenistan',
            'tuvalu',
            'uganda',
            'ukraine',
            'united arab emirates',
            'united kingdom',
            'united states',
            'uruguay',
            'uzbekistan',
            'vanuatu',
            'venezuela',
            'vietnam',
            'yemen',
            'zambia',
            'zimbabwe',
        ];
        sort($countries);
    @endphp
    <div class="checkout section-padding">
        <div class="container">
            <div class="checkout-Mainheading">
                <div class="checkout-heading">
                    <h4> Checkout</h4>
                </div>
                <div class="checkout__order-list">
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoNum done">

                        </div>
                        <div class="checkout__order-list__infoTitle">
                            Choose Tours
                        </div>
                    </div>
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoNum active">
                            2
                        </div>
                        <div class="checkout__order-list__infoTitle active">
                            Checkout Details
                        </div>
                    </div>
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoNum">
                            3
                        </div>
                        <div class="checkout__order-list__infoTitle">
                            Secure Payment
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-7">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="details-box">
                            <div class="details-box__header">
                                <i class='bx bxs-group'></i>
                                <div class="heading">Lead Passenger Details</div>
                            </div>
                            <div class="details-box__body">
                                <div class="row g-0">
                                    <div class="col-md-4">
                                        <div class="field">
                                            <input id="first_name" type="text" placeholder="First Name *" required
                                                name="order[name]">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="field">
                                            <input id="email" type="email" placeholder="Email *" required
                                                name="order[email]">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="field" data-flag-input-wrapper>
                                            <input type="hidden" name="order[phone_dial_code]" data-flag-input-dial-code
                                                value="971">
                                            <input type="hidden" name="order[phone_country_code]"
                                                data-flag-input-country-code value="ae">
                                            <input id="phone_number" type="text" name="order[phone_number]"
                                                class="field flag-input" data-flag-input value="" placeholder="Phone"
                                                inputmode="numeric" pattern="[0-9]*"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="field">
                                            <select id="country" name="order[country]" required>
                                                <option value="" selected disabled>Select</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country }}"
                                                        {{ strtolower('united arab emirates') == strtolower($country) ? 'selected' : '' }}>
                                                        {{ ucwords($country) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field">
                                            <input id="city" type="text" placeholder="City *" required
                                                name="order[city]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details-box">
                            <div class="details-box__header">
                                <i class='bx bxs-credit-card'></i>
                                <div class="heading">Choose a Payment Method</div>
                            </div>
                            <div class="details-box__body details-box__body--pay">
                                <ul class="payment-options">
                                        @if (isset($settings['stripe_enabled']) && (int) $settings['stripe_enabled'] === 1)
                                        <!-- Card Payments - Stripe -->
                                        <li class="payment-option">
                                            <input checked class="payment-option__input" type="radio" name="payment_type"
                                                value="stripe" id="stripe" />
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
                                                        Visa, Mastercard, American Express, Discover, Diners Club, JCB
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    @endif

                                    @if (isset($settings['tabby_enabled']) && (int) $settings['tabby_enabled'] === 1)
                                        <!-- Buy Now Pay Later - Tabby -->
                                        <li class="payment-option">
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="tabby" id="tabby" />
                                            <label for="tabby" class="payment-option__box">
                                                <div class="title-wrapper">
                                                    <div class="radio"></div>
                                                    <div class="icon">
                                                        <img src="{{ asset('frontend/assets/images/methods/3.png') }}"
                                                            alt="tabby" class="imgFluid">
                                                    </div>
                                                </div>
                                                <div class="content">
                                                    <div class="title">Tabby - Buy Now, Pay Later (4 instalments)</div>
                                                    <div class="note">
                                                        No credit card required. Valid for orders AED 100 or more.
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    @endif

                                    
                                    @if (isset($settings['cash_enabled']) && (int) $settings['cash_enabled'] === 1)
                                        <!-- Cash on Pickup -->
                                        <li class="payment-option">
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="cod"  id="cod" />
                                            <label for="cod" class="payment-option__box">
                                                <div class="title-wrapper">
                                                    <div class="radio"></div>
                                                    <div class="icon">
                                                        <img src="{{ asset('frontend/assets/images/methods/4.png') }}"
                                                            alt="cod" class="imgFluid">
                                                    </div>
                                                </div>
                                                <div class="content">
                                                    <div class="title">Cash on Pickup</div>
                                                    <div class="note">
                                                        Pay the driver when you pick up your order.
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    @endif


                                    @if (isset($settings['tamara_enabled']) && (int) $settings['tamara_enabled'] === 1)
                                        <!-- Buy Now Pay Later - Tamara -->
                                        <li class="payment-option">
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="tamara" id="tamara" />
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
                                                        Split your payment into 2–3 instalments. No interest. Simple &
                                                        secure.
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    @endif


                                    @if (isset($settings['paypal_enabled']) && (int) $settings['paypal_enabled'] === 1)
                                        <!-- Card Payments - PayPal -->
                                        <li class="payment-option">
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="paypal" id="paypal" />
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
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="postpay" id="postpay" />
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
                                            <input class="payment-option__input" type="radio" name="payment_type"
                                                value="pointCheckout" id="pointCheckout" />
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
                    </form>
                </div>
                <div class="col-lg-5">
                    @foreach ($cart['tours'] as $tourId => $item)
                        @php
                            $tour = $tours->where('id', $tourId)->first();
                        @endphp
                        <div class="checkout-details__wapper">
                            <div class="checkout-details {{ $loop->first ? 'open' : '' }}">
                                <div class="checkout-details__header">
                                    <div class="title-content">
                                        <i class='bx bx-calendar-check'></i>
                                        <div class="heading">{{ $tour->title }}</div>
                                    </div>
                                    <div class="up-arrow">
                                        <i class="bx bx-chevron-up"></i>
                                    </div>
                                </div>
                                <div class="checkout-details__optional">
                                    <div class="optional-wrapper">
                                        <div class="optional-wrapper-padding">
                                            <!-- Tour Image -->
                                            @if ($tour->featured_image)
                                                <div class="tour-image mb-3">
                                                    <img src="{{ getImageUrl($tour->featured_image) }}"
                                                        alt="{{ $tour->featured_image_alt_text ?? $tour->title }}"
                                                        class="img-fluid rounded"
                                                        style="width: 100%; height: 120px; object-fit: cover;">
                                                </div>
                                            @endif

                                            <!-- Tour Details -->
                                            <div class="sub-total">
                                                <div class="title">Type</div>
                                                <div class="price">{{ $tour->formated_price_type ?? 'Standard' }}</div>
                                            </div>

                                            @if ($tour->duration)
                                                <div class="sub-total">
                                                    <div class="title">Duration</div>
                                                    <div class="price">{{ $tour->duration }}</div>
                                                </div>
                                            @endif

                                            <div class="sub-total">
                                                <div class="title">Date</div>
                                                <div class="price">
                                                    {{ formatDate($cart['tours'][$tour->id]['start_date']) }}
                                                </div>
                                            </div>

                                            @if (isset($cart['tours'][$tour->id]['tourData']) && is_array($cart['tours'][$tour->id]['tourData']))
                                                @foreach ($cart['tours'][$tour->id]['tourData'] as $tourItem)
                                                    @if (isset($tourItem['quantity']) && $tourItem['quantity'] > 0)
                                                        <div class="sub-total">
                                                            <div class="title">{!! $tourItem['title'] ?? 'Package' !!}</div>
                                                            <div class="price">{{ $tourItem['quantity'] }} x
                                                                {{ formatPrice($tourItem['is_first_order_coupon_applied'] ? $tourItem['promo_discounted_price'] : $tourItem['discounted_price']) }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif

                                            <!-- Extra Services -->
                                            @if (isset($cart['tours'][$tour->id]['extra_prices']) && is_array($cart['tours'][$tour->id]['extra_prices']))
                                                @foreach ($cart['tours'][$tour->id]['extra_prices'] as $extra)
                                                    <div class="sub-total">
                                                        <div class="title">{{ $extra['name'] }}</div>
                                                        <div class="price">{{ formatPrice($extra['price']) }}</div>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <hr class="my-3">

                                            <!-- Pricing Breakdown -->
                                            <div class="sub-total">
                                                <div class="title">Subtotal</div>
                                                <div class="price">
                                                    {{ formatPrice($cart['tours'][$tour->id]['subtotal']) }}
                                                </div>
                                            </div>
                                            <div class="sub-total">
                                                <div class="title">Service Fee</div>
                                                <div class="price">
                                                    {{ formatPrice($cart['tours'][$tour->id]['service_fee'] ?? 0) }}
                                                </div>
                                            </div>
                                            <div class="sub-total">
                                                <input type="hidden" name="tour[title][]" value="{{ $tour->title }}">
                                                <input type="hidden" name="tour[total_price][]"
                                                    value="{{ $cart['tours'][$tour->id]['total_price'] }}">
                                                <div class="title"><strong>Total</strong></div>
                                                <div class="price"><strong>
                                                        {{ formatPrice($cart['tours'][$tour->id]['total_price']) }}
                                                    </strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="checkout-details__wapper">
                        <div class="checkout-details open">
                            <div class="checkout-details__header">
                                <div class="title-content">
                                    <i class="bx bx-box"></i>
                                    <div class="heading">Total</div>
                                </div>
                            </div>
                            <div class="checkout-details__optional ">
                                <div class="optional-wrapper">
                                    <div class="optional-wrapper-padding">
                                        <div class="sub-total">
                                            <div class="title">Subtotal</div>
                                            <div class="price">{{ formatPrice($cart['total_price']) }}</div>
                                        </div>
                                        <div class="cart-coupon">
                                            <form action="{{ route('checkout.applyCode') }}" method="POST">
                                                @csrf
                                                <input autocomplete="off" type="text" id="coupon" name="code"
                                                    placeholder="Enter code" class="coupon-input">
                                                <button type="submit" class="apply-btn couponcode">Apply</button>
                                            </form>

                                        </div>
                                        @error('code')
                                            <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span>
                                        @enderror
                                        <div class="sub-total total all-total mt-4">
                                            <div class="title">Total Payable</div>
                                            <div class="price">{{ formatPrice($cart['total_price']) }}</div>
                                        </div>

                                        <button id="checkout-btn" type="button" class="primary-btn w-100 mt-4">Pay
                                            now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script>
        if (document.querySelectorAll('.checkout-details').length > 0 && document.querySelectorAll(
                '.checkout-details__header').length > 0) {
            const checkoutDetails = document.querySelectorAll('.checkout-details');
            const upArrows = document.querySelectorAll('.checkout-details__header');

            upArrows.forEach((upArrow, index) => {
                upArrow.addEventListener('click', () => {
                    checkoutDetails[index].classList.toggle('open');
                });
            });
        }

        function initializeFlagInputs() {
            $("[data-flag-input-wrapper]").each(function() {
                var $wrapper = $(this);
                var input = $wrapper.find("[data-flag-input]");

                if (input.length > 0) {
                    input.intlTelInput({
                        initialCountry: "ae",
                        separateDialCode: true,
                    });

                    function updateCountryCode() {
                        var countryData = input.intlTelInput("getSelectedCountryData");
                        if (countryData && countryData.dialCode) {
                            $wrapper
                                .find("[data-flag-input-country-code]")
                                .val(countryData.iso2);
                            $wrapper
                                .find("[data-flag-input-dial-code]")
                                .val(countryData.dialCode);
                        }
                    }

                    input.on("countrychange", function(e) {
                        updateCountryCode();
                    });

                    var countryCode = $wrapper
                        .find("[data-flag-input-country-code]")
                        .val();
                    if (countryCode) {
                        input.intlTelInput("setCountry", countryCode);
                    }
                }
            });
        }
        $(document).ready(function() {
            initializeFlagInputs();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('checkout-btn');
            const form = document.getElementById('checkout-form');

            if (btn && form) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const email = document.getElementById('email').value;
                    const firstName = document.getElementById('first_name').value;
                    const phone = document.getElementById('phone_number').value;
                    const country = document.getElementById('country').value;
                    const city = document.getElementById('city').value;

                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    if (!email) {
                        showToast('error', 'Email is required');
                        return;
                    }
                    if (!emailPattern.test(email)) {
                        showToast('error', 'Please enter a valid email');
                        return;
                    }
                    if (!firstName) {
                        showToast('error', 'First name is required');
                        return;
                    }
                    if (!phone) {
                        showToast('error', 'Phone number is required');
                        return;
                    }
                    if (!country) {
                        showToast('error', 'Country is required');
                        return;
                    }
                    if (!city) {
                        showToast('error', 'City is required');
                        return;
                    }

                    form.submit();
                });
            }
        });
    </script>
@endpush
