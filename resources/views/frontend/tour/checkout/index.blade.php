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
        $cashDiscountApplicable = $settings->get('cash_discount_applicable');
        $advancePaymentPercentage = $settings->get('advance_payment_percentage');
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
                        @if (!Auth::check())
                            <div class="details-box">
                                <div class="details-box__header">
                                    <i class='bx bxs-user'></i>
                                    <div class="heading">How would you like to proceed?</div>
                                </div>
                                <div class="details-box__body">

                                    <!-- Modern Segmented Toggle -->
                                    <div class="segmented-control mb-4">
                                        <!-- Radio 1 -->
                                        <input type="radio" name="auth-toggle" id="tab-guest-input" checked
                                            data-bs-target="#guest-form">
                                        <label for="tab-guest-input" class="control-item">
                                            <i class='bx bx-user-circle'></i>
                                            <span>Continue as Guest</span>
                                        </label>

                                        <!-- Radio 2 -->
                                        <input type="radio" name="auth-toggle" id="tab-login-input"
                                            data-bs-target="#login-prompt">
                                        <label for="tab-login-input" class="control-item">
                                            <i class='bx bx-log-in-circle'></i>
                                            <span>Login / Signup</span>
                                        </label>

                                        <div class="selection-slider"></div>
                                    </div>

                                    <div class="tab-content" id="authTabContent">
                                        <!-- Guest Form Pane -->
                                        <div class="tab-pane fade show active" id="guest-form" role="tabpanel">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <div class="field">
                                                        <input id="first_name" type="text" placeholder="First Name *"
                                                            required name="order[name]">
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
                                                        <input type="hidden" name="order[phone_dial_code]"
                                                            data-flag-input-dial-code value="971">
                                                        <input type="hidden" name="order[phone_country_code]"
                                                            data-flag-input-country-code value="ae">
                                                        <input id="phone_number" type="text" name="order[phone_number]"
                                                            class="field flag-input" data-flag-input placeholder="Phone"
                                                            inputmode="numeric" pattern="[0-9]*"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                            maxlength="15">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="field">
                                                        <select id="country" name="order[country]" required>
                                                            <option value="" selected disabled>Select Country</option>
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

                                        <!-- Login Prompt Pane -->
                                        <div class="tab-pane fade" id="login-prompt" role="tabpanel">
                                            <div class="login-card-inner text-center pb-4">
                                                <div class="icon-circle mb-3">
                                                    <i class='bx bxs-lock-open-alt'></i>
                                                </div>
                                                <h5 class="fw-bold">Login / Signup</h5>
                                                <p class="text-muted small">Access your saved addresses and enjoy faster
                                                    checkout by logging in or creating a new account.</p>
                                                <button type="button" class="primary-btn mt-2 mx-auto"
                                                    open-vue-login-popup>
                                                    Continue to My Account
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const radios = document.querySelectorAll('input[name="auth-toggle"]');

                                    radios.forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            const targetSelector = this.getAttribute('data-bs-target');

                                            // Hide all tab panes
                                            document.querySelectorAll('.tab-pane').forEach(pane => {
                                                pane.classList.remove('show', 'active');
                                            });

                                            // Show the selected tab pane
                                            const targetPane = document.querySelector(targetSelector);
                                            if (targetPane) {
                                                targetPane.classList.add('show', 'active');
                                            }
                                        });
                                    });
                                });
                            </script>

                            <style>
                                .segmented-control {
                                    position: relative;
                                    display: flex;
                                    background: #f1f3f5;
                                    border-radius: 12px;
                                    padding: 4px;
                                    margin: 0 auto;
                                    border: 1px solid #e9ecef;
                                    overflow: hidden;
                                }

                                .segmented-control input[type="radio"] {
                                    display: none;
                                }

                                .control-item {
                                    flex: 1;
                                    text-align: center;
                                    padding: 12px 10px;
                                    cursor: pointer;
                                    z-index: 2;
                                    transition: color 0.3s ease;
                                    font-weight: 600;
                                    font-size: 14px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 8px;
                                    color: #6c757d;
                                    margin-bottom: 0;
                                }

                                .selection-slider {
                                    position: absolute;
                                    top: 4px;
                                    left: 4px;
                                    bottom: 4px;
                                    width: calc(50% - 4px);
                                    background: #ffffff;
                                    border-radius: 10px;
                                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                                    transition: transform 0.3s cubic-bezier(0.645, 0.045, 0.355, 1);
                                    z-index: 1;
                                }

                                /* Move slider based on checked input */
                                #tab-login-input:checked~.selection-slider {
                                    transform: translateX(100%);
                                }

                                /* Active Text Color */
                                #tab-guest-input:checked+label,
                                #tab-login-input:checked+label {
                                    color: #000;
                                }

                                .login-card-inner {
                                    margin: 0 auto;
                                }

                                .icon-circle {
                                    width: 60px;
                                    height: 60px;
                                    border: 1px dashed #dee2e6;
                                    background: #fff;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin: 0 auto;
                                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                                    color: #2b3e50;
                                    font-size: 1.5rem;
                                }
                            </style>
                        @endif
                        <div class="details-box">
                            <div class="details-box__header">
                                <i class='bx bxs-credit-card'></i>
                                <div class="heading">Choose a Payment Method</div>
                            </div>
                            <div class="details-box__body details-box__body--pay">
                                <ul class="payment-options">
                                    @php
                                        $paymentMethods = [];

                                        // Stripe
                                        if (
                                            !empty($settings['stripe_enabled']) &&
                                            (int) $settings['stripe_enabled'] === 1
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'stripe',
                                                'order' => (int) ($settings['stripe_order'] ?? 999),
                                            ];
                                        }

                                        // Advance Payment
                                        if (
                                            !empty($settings['advance_payment_enabled']) &&
                                            (int) $settings['advance_payment_enabled'] === 1
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'advance_payment',
                                                'order' => (int) ($settings['advance_payment_order'] ?? 999),
                                            ];
                                        }

                                        // Tabby
                                        if (
                                            !empty($settings['tabby_enabled']) &&
                                            (int) $settings['tabby_enabled'] === 1
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'tabby',
                                                'order' => (int) ($settings['tabby_order'] ?? 999),
                                            ];
                                        }

                                        // Tamara
                                        if (
                                            !empty($settings['tamara_enabled']) &&
                                            (int) $settings['tamara_enabled'] === 1
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'tamara',
                                                'order' => (int) ($settings['tamara_order'] ?? 999),
                                            ];
                                        }

                                        // PayPal
                                        if (
                                            !empty($settings['paypal_enabled']) &&
                                            (int) $settings['paypal_enabled'] === 1
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'paypal',
                                                'order' => (int) ($settings['paypal_order'] ?? 999),
                                            ];
                                        }

                                        // Cash
                                        if (
                                            !empty($settings['cash_enabled']) &&
                                            (int) $settings['cash_enabled'] === 1 &&
                                            !$hideCashOnPickup
                                        ) {
                                            $paymentMethods[] = [
                                                'key' => 'cash',
                                                'order' => (int) ($settings['cash_order'] ?? 999),
                                            ];
                                        }

                                        // Sort by order
                                        usort($paymentMethods, fn($a, $b) => $a['order'] <=> $b['order']);
                                    @endphp


                                    @foreach ($paymentMethods as $method)
                                        @switch($method['key'])
                                            @case('stripe')
                                                <!-- Card Payments - Stripe -->
                                                <li class="payment-option">
                                                    <input checked class="payment-option__input" type="radio"
                                                        name="payment_type" value="stripe" id="stripe" />
                                                    <label for="stripe" class="payment-option__box">
                                                        <div class="title-wrapper">
                                                            <div class="radio"></div>
                                                            <div class="icon">
                                                                <img src="{{ isset($settings['stripe_logo']) ? asset($settings['stripe_logo']) : asset('frontend/assets/images/methods/1.png') }}"
                                                                    alt="{{ isset($settings['stripe_logo_alt_text']) ? $settings['stripe_logo_alt_text'] : 'stripe' }}"
                                                                    class="imgFluid">
                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <div class="title">
                                                                {{ $settings['stripe_title'] ?? 'Credit/Debit Card (Full Payment)' }}
                                                            </div>
                                                            <div class="note">
                                                                {{ $settings['stripe_description'] ?? 'Visa, Mastercard, American Express, Discover, Diners Club, JCB' }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @break

                                            @case('advance_payment')
                                                <!-- Card Payments - Advance -->
                                                <li class="payment-option">
                                                    <input class="payment-option__input" type="radio" name="payment_type"
                                                        value="advance_payment" id="advance_payment" />
                                                    <label for="advance_payment" class="payment-option__box">
                                                        <div class="title-wrapper">
                                                            <div class="radio"></div>
                                                            <div class="icon">
                                                                <img src="{{ isset($settings['advance_payment_logo']) ? asset($settings['advance_payment_logo']) : asset('frontend/assets/images/methods/8.png') }}"
                                                                    alt="{{ isset($settings['advance_payment_logo_alt_text']) ? $settings['advance_payment_logo_alt_text'] : 'stripe' }}"
                                                                    class="imgFluid">

                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <div class="title d-flex align-items-start  gap-2">
                                                                {{ $settings['advance_payment_title'] ?? 'Advance Payment' }}
                                                                @php
                                                                    $advanceAmount = $advancePaymentPercentage
                                                                        ? ($advancePaymentPercentage / 100) *
                                                                            $cart['total_price']
                                                                        : null;

                                                                    $remainingAmount = $advanceAmount
                                                                        ? $cart['total_price'] - $advanceAmount
                                                                        : null;
                                                                @endphp

                                                                @if ($advanceAmount)
                                                                    <div class="advance-payment-info" data-tooltip="tooltip"
                                                                        title="Book now just with a booking deposit of AED {{ number_format($advanceAmount, 2) }}  <br> The balance of AED {{ number_format($remainingAmount, 2) }} will be paid on the day of the activity. <br> Total AED {{ number_format($cart['total_price'], 2) }} per selected travelers/options">
                                                                        <i class='bx bxs-info-circle'></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="note">
                                                                {{ $settings['advance_payment_description'] ?? 'A percentage of the order total to be paid upfront before processing.' }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @break

                                            @case('tabby')
                                                <!-- Buy Now Pay Later - Tabby -->
                                                <li class="payment-option">
                                                    <input class="payment-option__input" type="radio" name="payment_type"
                                                        value="tabby" id="tabby" />
                                                    <label for="tabby" class="payment-option__box d-block px-0">
                                                        <div class="payment-option__box">
                                                            <div class="title-wrapper">
                                                                <div class="radio"></div>
                                                                <div class="icon">
                                                                    <img src="{{ isset($settings['tabby_logo']) ? asset($settings['tabby_logo']) : asset('frontend/assets/images/methods/3.png') }}"
                                                                        alt="{{ isset($settings['tabby_logo_alt_text']) ? $settings['tabby_logo_alt_text'] : 'tabby' }}"
                                                                        class="imgFluid">
                                                                </div>
                                                            </div>
                                                            <div class="content">
                                                                <div class="title">
                                                                    {{ $settings['tabby_title'] ?? 'Tabby - Buy Now, Pay Later (4 instalments)' }}
                                                                </div>
                                                                <div class="note">
                                                                    {{ $settings['tabby_description'] ?? 'No credit card required. Valid for orders AED 100 or more.' }}
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div style="width: 91%;" class="ms-auto" id="tabbyPromo"></div>
                                                    </label>
                                                </li>
                                                <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
                                                <script>
                                                    new TabbyPromo({
                                                        selector: '#tabbyPromo',
                                                        currency: 'AED',
                                                        price: {{ $cart['total_price'] }},
                                                        installmentsCount: 4,
                                                        lang: 'en',
                                                        source: 'product',
                                                        publicKey: 'pk_test_68ae0214-7dd1-42dc-90e2-f541006cde58',
                                                        merchantCode: 'HDS'
                                                    });
                                                </script>
                                            @break

                                            @case('tamara')
                                                <!-- Buy Now Pay Later - Tamara -->
                                                <li class="payment-option">
                                                    <input class="payment-option__input" type="radio" name="payment_type"
                                                        value="tamara" id="tamara" />
                                                    <label for="tamara" class="payment-option__box">
                                                        <div class="title-wrapper">
                                                            <div class="radio"></div>
                                                            <div class="icon">
                                                                <img src="{{ isset($settings['tamara_logo']) ? asset($settings['tamara_logo']) : asset('frontend/assets/images/methods/6.png') }}"
                                                                    alt="{{ isset($settings['tamara_logo_alt_text']) ? $settings['tamara_logo_alt_text'] : 'tamara' }}"
                                                                    class="imgFluid">
                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <div class="title">
                                                                {{ $settings['tamara_title'] ?? 'Tamara - Pay Later' }}
                                                            </div>
                                                            <div class="note">
                                                                {{ $settings['tamara_description'] ?? 'Split your payment into 2–3 instalments. No interest. Simple & secure.' }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @break

                                            @case('paypal')
                                                <!-- Card Payments - PayPal -->
                                                <li class="payment-option">
                                                    <input class="payment-option__input" type="radio" name="payment_type"
                                                        value="paypal" id="paypal" />
                                                    <label for="paypal" class="payment-option__box">
                                                        <div class="title-wrapper">
                                                            <div class="radio"></div>
                                                            <div class="icon">
                                                                <img src="{{ isset($settings['paypal_logo']) ? asset($settings['paypal_logo']) : asset('frontend/assets/images/methods/5.png') }}"
                                                                    alt="{{ isset($settings['paypal_logo_alt_text']) ? $settings['paypal_logo_alt_text'] : 'paypal' }}"
                                                                    class="imgFluid">
                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <div class="title">
                                                                {{ $settings['paypal_title'] ?? 'PayPal' }}
                                                            </div>
                                                            <div class="note">
                                                                {{ $settings['paypal_description'] ?? 'Secure payments via PayPal wallet or linked cards.' }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @break

                                            @case('cash')
                                                <!-- Cash on Pickup -->
                                                <li class="payment-option">
                                                    <input class="payment-option__input" type="radio" name="payment_type"
                                                        value="cod" id="cod" />
                                                    <label for="cod" class="payment-option__box">
                                                        <div class="title-wrapper">
                                                            <div class="radio"></div>
                                                            <div class="icon">
                                                                <img src="{{ isset($settings['cash_logo']) ? asset($settings['cash_logo']) : asset('frontend/assets/images/methods/4.png') }}"
                                                                    alt="{{ isset($settings['cash_logo_alt_text']) ? $settings['cash_logo_alt_text'] : 'cod' }}"
                                                                    class="imgFluid">
                                                            </div>
                                                        </div>
                                                        <div class="content">
                                                            <div class="title">
                                                                {{ $settings['cash_title'] ?? 'Cash on Pickup' }}
                                                            </div>
                                                            <div class="note">
                                                                {{ $settings['cash_description'] ?? 'Pay the driver when you pick up your order.' }}
                                                            </div>
                                                        </div>
                                                    </label>
                                                </li>
                                            @break
                                        @endswitch
                                    @endforeach
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


                                            @if ($tour->duration)
                                                <div class="sub-total">
                                                    <div class="title">Duration</div>
                                                    <div class="price">{{ $tour->duration }}</div>
                                                </div>
                                            @endif

                                            <div class="sub-total">
                                                <div class="title">Start Date</div>
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
    <style>
        .tooltip-inner {
            max-width: 450px !important;
        }
    </style>
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
        const cashDiscountApplicable = @json($cashDiscountApplicable);
        const hasCouponApplied = @json($hasCouponApplied);
        const makeFieldsRequired = @json(!Auth::check());

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('checkout-form');
            const btn = document.getElementById('checkout-btn');
            const paymentInputs = document.querySelectorAll('input[name="payment_type"]');

            const getSelectedPaymentMethod = () => {
                const selected = document.querySelector('input[name="payment_type"]:checked');
                return selected ? selected.value : null;
            };

            if (btn && form) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();


                    if (makeFieldsRequired) {
                        const email = document.getElementById('email').value;
                        const firstName = document.getElementById('first_name').value;
                        const phone = document.getElementById('phone_number').value;
                        const country = document.getElementById('country').value;
                        const city = document.getElementById('city').value;
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        // Validation
                        if (!email) return showToast('error', 'Email is required');
                        if (!emailPattern.test(email)) return showToast('error',
                            'Please enter a valid email');
                        if (!firstName) return showToast('error', 'First name is required');
                        if (!phone) return showToast('error', 'Phone number is required');
                        if (!country) return showToast('error', 'Country is required');
                        if (!city) return showToast('error', 'City is required');
                    }

                    const selectedPayment = getSelectedPaymentMethod();

                    // COD + discount check
                    if (hasCouponApplied) {
                        if (selectedPayment === 'cod' && cashDiscountApplicable == 0) {
                            const proceed = confirm(
                                "Cash on Pickup is not eligible for your current discount. " +
                                "If you continue with this payment method, all discounts will be removed. " +
                                "Do you want to proceed?"
                            );
                            if (!proceed) return; // stop if user cancels
                        }
                    }

                    // All good, submit form
                    form.submit();
                });
            }
        });
    </script>
@endpush
