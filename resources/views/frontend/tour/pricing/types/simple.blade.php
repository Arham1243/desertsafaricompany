@if (!$tour->price_type && $tour->regular_price && $tour->sale_price)
    <input type="hidden" name="price[sale_price]" value="{{ $tour->sale_price }}">
    <input type="hidden" name="price[regular_price]" value="{{ $tour->regular_price }}">
    <input type="hidden" name="price[quantity]" value="1">
    <div class="form-group form-guest-search">
        <div class="form-guest-search__details simple pb-4">
            <div class="form-book__title form-guest-search__title">
                Simple Tour
            </div>
            <div class="promo-title">Single pricing</div>

            <div class="promo-price-wrapper">
                <div class="promo-price cut" v-if="simpleTourData.regular_price">
                    <span v-html="formatPrice(simpleTourData.regular_price)"></span>
                </div>
                <div class="promo-price green" :class="{ cut: isFirstOrderCouponApplied }"
                    v-if="simpleTourData.sale_price">
                    <span v-html="formatPrice(simpleTourData.sale_price)"></span>
                </div>
                <div class="promo-price purple" v-if="isFirstOrderCouponApplied">
                    <span v-html="formatPrice(simpleTourData.promo_discounted_price)"></span>
                </div>
            </div>

            <div class="promo-og-offer purple">
                <span class="offer">
                    <span
                        v-html="formatPrice(simpleTourData.promo_discounted_price || simpleTourData.sale_price || simpleTourData.original_price)">
                    </span>
                    with promo
                </span>
            </div>

            <span v-if="isFirstOrderCouponApplied" class="promo-applied purple d-flex align-items-center mt-1">
                <i style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied
            </span>
        </div>
    </div>
@endif
