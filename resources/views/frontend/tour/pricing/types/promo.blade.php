@if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
    <div v-for="(promo, index) in promoTourData" :key="index" class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            <div>@{{ promo.promo_title }}: Expires in: @{{ getTimeLeft(promo.offer_expire_at) }}</div>
            <div class="form-guest-search__items">
                <div class="prices-wrapper">
                    <div class="new-price">
                        {{ env('APP_CURRENCY') }}
                    </div>
                    <div class="del-price" :class="{ 'cut': promo.is_not_expired, 'green': !promo.is_not_expired }">
                        @{{ promo.original_price }}</div>
                    <div class="new-price" :class="{ 'green': promo.is_not_expired, 'cut': !promo.is_not_expired }"
                        v-if="promo.is_not_expired">@{{ promo.discount_price }}</div>
                </div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button"
                        @click="updateQuantity('minus', promo.promo_title)">
                        <i class="bx bx-chevron-down"></i>
                    </button>
                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
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
