@php
    $isDataValid =
        $tour->promoPrices->isNotEmpty() &&
        $tour->promoPrices[0]->original_price &&
        $tour->promoPrices[0]->discount_price &&
        $tour->promoPrices[0]->promo_price;

    use Carbon\Carbon;

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
<div class=tour-content_book_app>


    @if ($hasValidDeals)
        <div class="sale-box">
            <div class="ribbon ribbon--red">SAVE {{ $combinedDiscountPercent }}%</div>
        </div>
    @endif


    <div class=form-book>
        @if ($isDataValid)
            <form class=form-book_details>
            @else
                <div class=form-book_details>
        @endif
        <div class=form-book_content>

            <div class="tour-content__pra form-book__pra">
                Start Date
            </div>
            <div class="tour-content__title form-book__title">
                <input type="date" class="form-book__date" name="start_date" required>
            </div>

        </div>
        @if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
            <div v-for="(promo, index) in promoTourData" :key="index" class="form-group form-guest-search">
                <div class="tour-content__pra form-book__pra form-guest-search__details">
                    <div>@{{ promo.promo_title }}: Expires in: @{{ getTimeLeft(promo.offer_expire_at) }}</div>
                    <div class="form-guest-search__items">
                        <div class="prices-wrapper">
                            <div class="del-price"
                                :class="{ 'cut': promo.is_not_expired, 'green': !promo.is_not_expired }">
                                @{{ promo.original_price }}</div>
                            <div class="new-price"
                                :class="{ 'green': promo.is_not_expired, 'cut': !promo.is_not_expired }"
                                v-if="promo.is_not_expired">@{{ promo.discount_price }}</div>
                        </div>
                        <div class="quantity-counter">
                            <button class="quantity-counter__btn" type="button"
                                @click="updateQuantity('minus', promo.promo_title)">
                                <i class="bx bx-chevron-down"></i>
                            </button>
                            <input type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                                min="0" v-model="promo.quantity">
                            <button class="quantity-counter__btn" type="button"
                                @click="updateQuantity('plus', promo.promo_title)">
                                <i class="bx bx-chevron-up"></i>
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
            <button class="app-btn themeBtn" @if (!$isDataValid) disabled @endif>Book Now</button>
        </div>

        @if ($isDataValid)
            </form>
        @else
    </div>
    @endif
</div>
</div>
