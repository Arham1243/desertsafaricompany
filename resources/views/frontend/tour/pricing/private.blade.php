@php
    $isDataValid =
        $tour->privatePrices &&
        $tour->privatePrices->car_price &&
        $tour->privatePrices->min_person &&
        $tour->privatePrices->max_person;
@endphp
<div class=tour-content_book_app>

    <div class=form-book>
        @if ($isDataValid)
            <form class="form-book_details" method="POST" action="{{ route('tours.cart.add', $tour->id) }}">
                @csrf
            @else
                <div class=form-book_details>
        @endif

        @if ($tour->privatePrices)
            <input type="hidden" name="simeple_tour_price" value="{{ $tour->privatePrices->car_price }}">
            <div class="tour-content_book_pricing d-block">
                <span class="tour-content__pra m-0">Min: {{ $tour->privatePrices->min_person }}</span>
                <span class="tour-content__pra m-0 ms-2">Max: {{ $tour->privatePrices->max_person }}</span>
                <span class="tour-content__pra m-0 ms-2">Persons</span>
                <div class="tour-content__title tour-content_book__realPrice mt-0">
                    Car Price {{ formatPrice($tour->privatePrices->car_price) }}
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
        @if ($tour->privatePrices)
            <div class="form-group form-guest-search">
                <div class="tour-content__pra form-book__pra form-guest-search__details">
                    Total Persons
                    <div class=form-guest-search__items>
                        <div class="form-book__title form-guest-search__title">
                            Car Price
                            <div clFass=form-guest-search__smallTitle>
                                {{ formatPrice($tour->privatePrices->car_price) }} </div>
                        </div>
                        <div class="quantity-counter">
                            <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus')">
                                <i class='bx bx-chevron-down'></i>
                            </button>
                            <input readonly type="number"
                                class="person-quanity quantity-counter__btn quantity-counter__btn--quantity"
                                min="0" name="price[persons][quantity]" v-model="carQuantity">
                            <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus')">
                                <i class='bx bx-chevron-up'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
