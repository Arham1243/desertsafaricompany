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
                $isDataValid =
                    $tour->promoPrices->isNotEmpty() &&
                    $tour->promoPrices[0]->original_price &&
                    $tour->promoPrices[0]->discount_price &&
                    $tour->promoPrices[0]->promo_price;
                break;
            case 'water':
                $isDataValid = $tour->waterPrices->isNotEmpty();
                break;
            default:
                $isDataValid = $tour->regular_price && $tour->sale_price;
                break;
        }

        $promoDetails = $tour->promoPrices->map(function ($promoPrice) {
            $discountPercent = 0;
            if ($promoPrice->original_price > 0) {
                $discountPercent =
                    (($promoPrice->original_price - $promoPrice->promo_price) / $promoPrice->original_price) * 100;
            }
            $isNotExpired = Carbon::now()->lt(Carbon::parse($promoPrice->offer_expire_at));

            return [
                'discount_percent' => $discountPercent,
                'original_price' => $promoPrice->original_price,
                'promo_price' => $promoPrice->promo_price,
                'is_not_expired' => $isNotExpired,
            ];
        });

        $validDeals = $promoDetails->filter(fn($deal) => $deal['is_not_expired']);

        $combinedDiscountPercent = 0;
        if ($validDeals->isNotEmpty()) {
            $totalOriginalPrice = $validDeals->sum('original_price');
            $totalPromoPrice = $validDeals->sum('promo_price');

            if ($totalOriginalPrice > 0) {
                $combinedDiscountPercent = (($totalOriginalPrice - $totalPromoPrice) / $totalOriginalPrice) * 100;
            }
        }

        $combinedDiscountPercent = round($combinedDiscountPercent, 0);
        $hasValidDeals = $validDeals->isNotEmpty();
    @endphp
    <div class=tour-content_book_wrap>
        <div class="tour-content_book_app {{ $tour->price_type === 'promo' ? 'sale' : '' }}">
            @if ($hasValidDeals)
                <div class="sale-box">
                    <div class="ribbon ribbon--red">SAVE {{ $combinedDiscountPercent }}%</div>
                </div>
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
            @if ($tour->price_type === 'private')
                <div class="tour-content_book_pricing">
                    <div class="baseline-pricing__value">
                        Min: {{ $tour->privatePrices->min_person }}
                    </div>
                    <div class="baseline-pricing__value">
                        Max: {{ $tour->privatePrices->max_person }}
                        Persons
                    </div>
                </div>
            @endif
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
