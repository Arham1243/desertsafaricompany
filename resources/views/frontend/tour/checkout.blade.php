@extends('frontend.layouts.main')
@section('content')
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
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-7">
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
                                            <select name="order[country]" required id="country-select">
                                                <option value="" disabled selected>Select Country *</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                            value="stripe" checked id="stripe" />
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
                                                <div class="title">Buy Now Pay Later - 3 Installements, No Credit Card
                                                    Required</div>
                                                <div class="note">
                                                    Only Valid on basket value of AED 100 or more
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
                            </div>
                        </div>
                        <button type="submit" class="primary-btn w-100 mt-4">Pay now</button>
                    </div>
                    <div class="col-lg-5">
                        @foreach ($cart as $tourId => $item)
                            @php
                                $tour = $tours->where('id', $tourId)->first();
                            @endphp
                            <div class="checkout-details__wapper">
                                <div class="checkout-details open">
                                    <div class="checkout-details__header">
                                        <i class="bx bx-purchase-tag-alt"></i>
                                        <div class="heading">{{ $tour->title }}</div>
                                    </div>
                                    <div class="checkout-details__optional">
                                        <div class="optional-wrapper">
                                            <div class="optional-wrapper-padding">
                                                @php
                                                    $combined = [
                                                        'subtotal' => 0,
                                                        'service_fee' => 0,
                                                        'total_price' => 0,
                                                    ];

                                                    foreach ($cart as $item) {
                                                        $data = $item['data'];
                                                        $combined['subtotal'] += $data['subtotal'];
                                                        $combined['service_fee'] += $data['service_fee'];
                                                        $combined['total_price'] += $data['total_price'];
                                                    }
                                                @endphp
                                                <div class="sub-total">
                                                    <div class="title">Type</div>
                                                    <div class="price">{{ $tour->formated_price_type ?? 'Standard' }}
                                                    </div>
                                                </div>
                                                <div class="sub-total">
                                                    <div class="title">Date</div>
                                                    <div class="price">
                                                        {{ formatDate($cart[$tour->id]['data']['start_date']) }}</div>
                                                </div>
                                                <div class="sub-total">
                                                    <div class="title">Subtotal</div>
                                                    <div class="price">
                                                        {{ formatPrice($cart[$tour->id]['data']['subtotal']) }}</div>
                                                </div>
                                                <div class="sub-total">
                                                    <div class="title">Service Fee</div>
                                                    <div class="price">
                                                        {{ formatPrice($cart[$tour->id]['data']['service_fee']) }}</div>
                                                </div>
                                                <div class="sub-total">
                                                    <input type="hidden" name="tour[title][]"
                                                        value="{{ $tour->title }}">
                                                    <input type="hidden" name="tour[total_price][]"
                                                        value="{{ $cart[$tour->id]['data']['total_price'] }}">
                                                    <div class="title">Total</div>
                                                    <div class="price">
                                                        {{ formatPrice($cart[$tour->id]['data']['total_price']) }}</div>
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
                                    <i class="bx bx-box"></i>
                                    <div class="heading">Total</div>
                                </div>
                                <div class="checkout-details__optional ">
                                    <div class="optional-wrapper">
                                        <div class="optional-wrapper-padding">
                                            @php
                                                $combined = [
                                                    'subtotal' => 0,
                                                    'service_fee' => 0,
                                                    'total_price' => 0,
                                                ];

                                                foreach ($cart as $item) {
                                                    $data = $item['data'];
                                                    $combined['subtotal'] += $data['subtotal'];
                                                    $combined['service_fee'] += $data['service_fee'];
                                                    $combined['total_price'] += $data['total_price'];
                                                }
                                            @endphp
                                            <div class="sub-total">
                                                <div class="title">Subtotal</div>
                                                <div class="price">{{ formatPrice($combined['subtotal']) }}</div>
                                            </div>
                                            <div class="sub-total">
                                                <div class="title">Service Fee</div>
                                                <div class="price">{{ formatPrice($combined['service_fee']) }}</div>
                                            </div>
                                            <hr>
                                            <input type="hidden" name="total_amount"
                                                value="{{ $combined['total_price'] }}">
                                            <div class="sub-total total all-total">
                                                <div class="title">Total Payable</div>
                                                <div class="price">{{ formatPrice($combined['total_price']) }}</div>
                                            </div>
                                        </div>
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
@push('js')
    <script>
        fetch('https://restcountries.com/v3.1/all')
            .then(response => response.json())
            .then(countries => {
                const select = document.getElementById('country-select');
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching countries:', error));
    </script>
@endpush
