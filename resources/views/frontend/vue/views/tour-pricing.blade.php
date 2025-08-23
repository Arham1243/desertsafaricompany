<div id="tour-pricing">
    @php
        use Carbon\Carbon;

        $waterPricesTimeSlots = $tour->waterPrices->isNotEmpty() ? $tour->waterPrices->pluck('time') : null;

        switch ($tour->price_type) {
            case 'normal':
                $isDataValid =
                    $tour->normalPrices->isNotEmpty() &&
                    $tour->normalPrices[0]->price &&
                    $tour->normalPrices[0]->person_type;
                break;
            case 'private':
                $isDataValid =
                    $tour->privatePrices &&
                    $tour->privatePrices->car_price &&
                    $tour->privatePrices->min_person &&
                    $tour->privatePrices->max_person;
                break;
            case 'promo':
                $isDataValid = $tour->promoPrices->isNotEmpty() && $tour->promoPrices[0]->original_price;
                break;
            case 'water':
                $isDataValid = $tour->waterPrices->isNotEmpty();
                break;
            default:
                $isDataValid = $tour->regular_price && $tour->sale_price;
                break;
        }

    @endphp
    <div class=tour-content_book_wrap>
        <div class="tour-content_book_app {{ $tour->price_type === 'promo' ? 'sale' : '' }}">
            <div class=form-book>
                @if ($isDataValid)
                    <form method="POST" action="{{ route('cart.add', $tour->id) }}">
                        @csrf
                        @include('frontend.tour.pricing.index')
                    </form>
                @else
                    <div class="form-book_details">
                        @include('frontend.tour.pricing.index')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
