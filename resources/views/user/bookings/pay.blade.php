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
                                                            'order' =>
                                                                (int) ($settings['advance_payment_order'] ?? 999),
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
                                                                <input class="payment-option__input" type="radio"
                                                                    name="payment_type" value="advance_payment"
                                                                    id="advance_payment" />
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
                                                                                    ? ($advancePaymentPercentage /
                                                                                            100) *
                                                                                        $cart['total_price']
                                                                                    : null;

                                                                                $remainingAmount = $advanceAmount
                                                                                    ? $cart['total_price'] -
                                                                                        $advanceAmount
                                                                                    : null;
                                                                            @endphp

                                                                            @if ($advanceAmount)
                                                                                <div class="advance-payment-info"
                                                                                    data-tooltip="tooltip"
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
                                                                <input class="payment-option__input" type="radio"
                                                                    name="payment_type" value="tabby" id="tabby" />
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
                                                                <input class="payment-option__input" type="radio"
                                                                    name="payment_type" value="tamara" id="tamara" />
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
                                                                <input class="payment-option__input" type="radio"
                                                                    name="payment_type" value="paypal" id="paypal" />
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
                                                                <input class="payment-option__input" type="radio"
                                                                    name="payment_type" value="cod" id="cod" />
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
