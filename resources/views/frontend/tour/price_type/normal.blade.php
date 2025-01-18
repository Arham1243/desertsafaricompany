@php
    $isDataValid =
        $tour->normalPrices->isNotEmpty() && $tour->normalPrices[0]->price && $tour->normalPrices[0]->person_type;
@endphp

<div class=tour-content_book_app>

    <div class=form-book>
        @if ($isDataValid)
            <form class="form-book_details" method="POST" action="{{ route('tours.cart.add', $tour->id) }}">
                @csrf
            @else
                <div class=form-book_details>
        @endif
        <div class=form-book_content>

            <div class="tour-content__pra form-book__pra">
                Start Date
            </div>
            <div class="tour-content__title form-book__title">
                <input type="date" class="form-book__date" name="start_date" required id="start_date">
            </div>

        </div>
        @if ($tour->is_person_type_enabled && $tour->normalPrices->isNotEmpty())
            @foreach ($tour->normalPrices as $normalPrice)
                <div class="form-group form-guest-search">
                    <div class="tour-content__pra form-book__pra form-guest-search__details">
                        {{ $normalPrice->person_type }}
                        <div class="form-guest-search__items">
                            <div class="form-book__title form-guest-search__title">
                                {{ $normalPrice->person_description }}
                                <div class="form-guest-search__smallTitle">{{ formatPrice($normalPrice->price) }} per
                                    person</div>
                            </div>
                            <div class="quantity-counter">
                                <button class="quantity-counter__btn" type="button"
                                    @click="updateQuantity('minus', '{{ strtolower(str_replace(' ', '_', $normalPrice->person_type)) }}')">
                                    <i class='bx bx-chevron-down'></i>
                                </button>
                                <input readonly type="number"
                                    class="person-quanity quantity-counter__btn quantity-counter__btn--quantity"
                                    v-model="normalTourData['{{ strtolower(str_replace(' ', '_', $normalPrice->person_type)) }}'].quantity"
                                    name="price[{{ strtolower(str_replace(' ', '_', $normalPrice->person_type)) }}][quantity]">
                                <input type="hidden"
                                    name="price[{{ strtolower(str_replace(' ', '_', $normalPrice->person_type)) }}][price]"
                                    value="{{ $normalPrice->price }}">
                                <button class="quantity-counter__btn" type="button"
                                    @click="updateQuantity('plus', '{{ strtolower(str_replace(' ', '_', $normalPrice->person_type)) }}')">
                                    <i class='bx bx-chevron-up'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @endif

        @include('frontend.tour.price_type.extra_price')
        @include('frontend.tour.price_type.service_fee')
        @include('frontend.tour.price_type.total_price')

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
