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
    @endphp
    @php
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
                            Complete Payment
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-7">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="details-box">
                            <div class="details-box__header">
                                <i class='bx bxs-group'></i>
                                <div class="heading">Lead Passenger Details</div>
                            </div>
                            <div class="details-box__body">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <div class="field">
                                            <input type="text" placeholder="First Name *" required
                                                name="order[first_name]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <input type="text" placeholder="Last Name *" required
                                                name="order[last_name]">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <input type="email" placeholder="Email *" required name="order[email]">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field">
                                            <input type="text" name="order[phone]" placeholder="Phone *"
                                                inputmode="numeric" pattern="[0-9]*"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15"
                                                required>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="field">
                                            <select name="order[country]" required>
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
                                            <input type="text" placeholder="City *" required name="order[city]">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field">
                                            <input type="text" placeholder="Address *" required name="order[address]">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field">
                                            <textarea name="order[speical_request]" rows="4" required placeholder="Special Request"></textarea>
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
                                    <li class="payment-option">
                                        <input class="payment-option__input" type="radio" name="payment_type"
                                            value="cod" checked id="cod" />
                                        <label for="cod" class="payment-option__box">
                                            <div class="title-wrapper">
                                                <div class="radio"></div>
                                                <div class="icon">
                                                    <img src="{{ asset('frontend/assets/images/methods/4.png') }}"
                                                        alt="cod" class="imgFluid">
                                                </div>
                                            </div>
                                            <div class="content">
                                                <div class="title">Cash on delivery</div>
                                                <div class="note">
                                                    Pay directly in cash at the time of delivery.
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                    <li class="payment-option">
                                        <input class="payment-option__input" type="radio" name="payment_type"
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
                                                <div class="title">Stripe</div>
                                                <div class="note">
                                                    Visa,
                                                    Mastercard,
                                                    American Express,
                                                    Discover,
                                                    Diners Club,
                                                    JCB
                                                </div>
                                            </div>
                                        </label>
                                    </li>
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
                                                <div class="title">Buy Now Pay Later - 4 Installements, No Credit Card
                                                    Required</div>
                                                <div class="note">
                                                    Only Valid on basket value of AED 100 or more
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                </ul>
                                <button type="submit" class="primary-btn w-100 mt-4">Pay now</button>
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
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <hr>
                                        <div class="sub-total total all-total">
                                            <div class="title">Total Payable</div>
                                            <div class="price">{{ formatPrice($cart['total_price']) }}</div>
                                        </div>

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
@push('js')
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
    </script>
@endpush
