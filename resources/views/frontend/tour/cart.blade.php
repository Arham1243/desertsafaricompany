@extends('frontend.layouts.main')
@section('content')
    <div class="cart section-padding">
        <div class="container">
            @if (isset($cart) && count($cart) > 0)
                <div class="row">
                    <div class="section-content">
                        <div class="heading">
                            You have {{ count($cart) }} item{{ count($cart) > 1 ? 's' : '' }} in your cart
                        </div>
                    </div>
                    <div class="col-md-8">
                        @foreach ($cart as $tourId => $item)
                            @php
                                $tour = $tours->where('id', $tourId)->first();
                            @endphp
                            <div class="cart__product">
                                <a href="{{ route('tours.details', $tour->slug) }}" class="cart__productImg">
                                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                        loading="lazy">
                                </a>
                                <div class="cart__productContent">
                                    <div>
                                        <div class="cart__productDescription">
                                            <div class="tour-type mb-1">
                                                <strong>{{ $tour->formated_price_type ?? formatPrice($tour->regular_price) . ' From' }}</strong>
                                            </div>
                                            <h4>{{ $tour->title }}</h4>
                                        </div>
                                        <a href="{{ route('cart.remove', $tour->id) }}"
                                            onclick="return confirm('Are you sure you want to remove this item from your cart?')"
                                            class="primary-btn p-2 align-self-start bg-danger">
                                            <i class='bx bxs-trash'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('tours.index') }}" class="primary-btn mt-4"> Continue Shopping </a>
                    </div>
                    <div class="col-md-4">
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
                                            <div class="sub-total total all-total">
                                                <div class="title">Total Payable</div>
                                                <div class="price">{{ formatPrice($combined['total_price']) }}</div>
                                            </div>
                                            <a href="{{ route('checkout.index') }}" class="primary-btn w-100 mt-4">Proceed
                                                to Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="section-content">
                        <div class="heading">
                            Your cart is currently empty
                        </div>
                    </div>
                    <p>Don't worry! Explore our exciting <strong><a class="link-primary"
                                href="{{ route('tours.index') }}">tours</a></strong> and add some to your cart.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="loader-mask" id="loader">
        <div class="loader"></div>
    </div>
@endsection
@push('css')
    <style type="text/css">
        .loader-mask {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1000000000000;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 4px solid var(--color-primary);
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@push('js')
    <script>
        window.addEventListener("load", function() {
            const loader = document.getElementById("loader");
            loader.style.display = "none";
        });
    </script>
@endpush
