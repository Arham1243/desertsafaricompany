@if ($tour->privatePrices)
    <div class="form-group form-guest-search" v-if="privateTourData">
        <div class="tour-content__pra form-book__pra form-guest-search__details normal">
            <div class="form-book__title form-guest-search__title">
                Private Tour
                <small class="text-muted">
                    (@{{ privateTourData.min_person }} – @{{ privateTourData.max_person }} persons per car)
                </small>
            </div>

            <div class="promo-price-wrapper mt-1">
                <div class="promo-price" :class="{ cut: isFirstOrderCouponApplied }"
                    v-if="privateTourData.promo_discounted_price < privateTourData.original_price">
                    <span v-html="formatPrice(privateTourData.original_price)"></span>
                </div>
                <div class="promo-price purple" v-if="isFirstOrderCouponApplied">
                    <span v-html="formatPrice(privateTourData.promo_discounted_price)"></span>
                </div>
            </div>

            <div class="promo-og-offer purple" v-if="!hasUsedFirstOrderCoupon">
                <span class="offer">
                    <span v-html="formatPrice(privateTourData.promo_discounted_price)"></span> with promo
                </span>
            </div>

            <span v-if="isFirstOrderCouponApplied" class="promo-applied purple d-flex align-items-center mt-1">
                <i style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied
            </span>

            <div class="form-guest-search__items justify-content-between" style="margin-top:0.25rem">
                <div class="already-bought"></div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus')">
                        <i class="bx bx-chevron-down"></i>
                    </button>

                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                        :min="privateTourData.min_person" :max="privateTourData.max_person"
                        v-model="privateTourData.quantity" name="price[private][quantity]" />

                    <input type="hidden" name="price[private][car_price]" :value="privateTourData.original_price" />

                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus')">
                        <i class="bx bx-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
