@php
    $isDataValid = $tour->regular_price && $tour->sale_price;
@endphp
<div class=tour-content_book_app>

    <div class=form-book>
        @if ($isDataValid)
            <form class="form-book_details" method="POST" action="{{ route('tours.cart.add', $tour->id) }}">
                @csrf
            @else
                <div class=form-book_details>
        @endif
        @if ($tour->regular_price && $tour->sale_price)
            <input type="hidden" name="simeple_tour_price" value="{{ $tour->sale_price }}">
            <div class="tour-content_book_pricing">
                <span class="tour-content__pra">From</span>
                <div class="baseline-pricing__value baseline-pricing__value--low">
                    {{ formatPrice($tour->regular_price) }}

                </div>
                <div class="baseline-pricing__value green">
                    {{ formatPrice($tour->sale_price) }}
                </div>
            </div>
        @endif

        <div class=form-book_content>

            <div class="tour-content__pra form-book__pra">
                Start Date
            </div>
            <div class="tour-content__title form-book__title">
                <input type="date" class="form-book__date" name="start_date" required id="start_date">
            </div>

        </div>
        @include('frontend.tour.pricing.extra_price')
        @include('frontend.tour.pricing.service_fee')
        @include('frontend.tour.pricing.total_price')

        <div class=form-guest__btn>

            @if (Auth::check())
                @if (!$isTourInCart)
                    <button class="app-btn themeBtn w-100" :disabled="!isSubmitEnabled"
                        @if (!$isDataValid) disabled @endif>Book
                        Now</button>
                @else
                    <a href="{{ route('tours.cart.index') }}" class="app-btn themeBtn w-100">View Cart</a>
                @endif
            @else
                <button class="app-btn themeBtn w-100" disabled>Login to Continue</button>
            @endif
        </div>
        @if ($isDataValid)
            </form>
        @else
    </div>
    @endif
</div>
</div>
