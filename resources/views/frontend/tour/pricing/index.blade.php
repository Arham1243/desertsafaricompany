    <div class=form-book_content>
        <div class="tour-content__pra form-book__pra px-0">
            Start Date
        </div>
        <div class="tour-content__title form-book__title">
            <input type="date" class="form-book__date" name="start_date" required id="start_date">
            <input type="hidden" name="price_type" value="{{ $tour->price_type }}">
        </div>
    </div>

    @switch($tour->price_type)
        @case('normal')
            @include('frontend.tour.pricing.types.normal')
        @break

        @case('water')
            @include('frontend.tour.pricing.types.water')
        @break

        @case('promo')
            @include('frontend.tour.pricing.types.promo')
        @break

        @case('private')
            @include('frontend.tour.pricing.types.private')
        @break
    @endswitch

    @include('frontend.tour.pricing.components.extra_price')
    @include('frontend.tour.pricing.components.service_fee')
    @include('frontend.tour.pricing.components.total_price')

    <div class="tour-views">
        <div class="tour-views__icon"><i class="bx bx-show"></i></div>
        <div class="tour-views__count">Over 80 views today, so act now!</div>
    </div>

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
