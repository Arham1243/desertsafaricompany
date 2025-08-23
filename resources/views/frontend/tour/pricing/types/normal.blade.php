@if ($tour->is_person_type_enabled && $tour->normalPrices->isNotEmpty())
    <div v-for="(item, key) in normalTourData" :key="key" class="form-group form-guest-search">
        <div class="form-guest-search__details normal">
            <div class="form-book__title form-guest-search__title">@{{ item.person_type }}</div>
            <div class="promo-title">@{{ item.person_description }}</div>

            <div class="promo-price-wrapper">
                <div class="promo-price cut" v-if="item.original_price && item.discounted_price">
                    <span v-html="formatPrice(item.original_price)"></span>
                </div>
                <div class="promo-price green">
                    <span v-html="formatPrice(item.discounted_price || item.original_price)"></span>
                </div>
            </div>

            <div class="promo-og-offer purple">
                <span class="offer">
                    <span
                        v-html="formatPrice(item.promo_discounted_price || item.discounted_price || item.original_price)"></span>
                    with promo
                </span>
            </div>

            <span v-if="isFirstOrderCouponrApplied" class="promo-applied purple d-flex align-items-center mt-1">
                <i style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied
            </span>

            <div class="form-guest-search__items justify-content-between" style="margin-top: 0.25rem">
                <div class="already-bought"></div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus', key)">
                        <i class="bx bx-chevron-down"></i>
                    </button>

                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                        v-model="item.quantity" :name="`price[${key}][quantity]`" />

                    <input type="hidden" :name="`price[${key}][price]`" :value="item.original_price" />

                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus', key)">
                        <i class="bx bx-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
