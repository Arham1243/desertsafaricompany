@extends('frontend.layouts.main')
@section('content')
    @php
        $seo = (object) [
            'seo_title' => 'Cart',
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
    <div class="cart section-padding">
        <div class="container">
            @if (isset($cart['tours']) && count($cart['tours']) > 0)
                @include('frontend.vue.main', [
                    'appId' => 'cart-items',
                    'appComponent' => 'cart-items',
                    'appJs' => 'cart-items',
                ])
            @else
                <div class="text-center">
                    <div class="section-content">
                        <div class="heading">
                            Your cart is currently empty
                        </div>
                    </div>
                    <p>Don't worry! Explore our exciting <strong><a class="link-primary"
                                href="{{ route('locations.country', 'ae') }}">tours</a></strong> and add some to your cart.
                    </p>
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
