    <div class=form-book_content>
        <div class="tour-content__pra form-book__pra">
            Start Date
        </div>
        <div class="tour-content__title form-book__title">
            <input type="date" class="form-book__date" name="start_date" required id="start_date">
        </div>
    </div>

    @switch($tour->price_type)
        @case('normal')
            @include('frontend.tour.price_type.normal')
        @break

        @case('water')
            @include('frontend.tour.price_type.water')
        @break

        @case('promo')
            @include('frontend.tour.price_type.promo')
        @break

        @case('private')
            @include('frontend.tour.price_type.private')
        @break
    @endswitch

    @include('frontend.tour.price_type.extra_price')
    @include('frontend.tour.price_type.service_fee')
    @include('frontend.tour.price_type.total_price')

    <div class=form-guest__btn>
        @if (Auth::check())
            @if (isset($isTourInCart) && !$isTourInCart)
                <button class="app-btn themeBtn w-100"
                    @if ($tour->price_type && $tour->price_type !== 'private') :disabled="!isSubmitEnabled"
                    @elseif ($tour->price_type === 'private')
                :disabled="!carQuantity>0" 
                @else @endif
                    @if (!$isDataValid) disabled @endif>Book
                    Now</button>
            @else
                <a href="{{ route('cart.index') }}" class="app-btn themeBtn w-100">View
                    Cart</a>
            @endif
        @else
            <button class="app-btn themeBtn w-100" disabled>Login to Continue</button>
        @endif
    </div>
