<div v-if="tour.is_person_type_enabled">
    <div v-for="(promo, index) in getPromoTourPricing(tour.id)" :key="index"
        class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            <div>@{{ promo.promo_title }}: Expires in: @{{ promo.offer_expire_at }}</div>
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

                <div class="cart__productQuantity-btns">
                    <button type="button"
                        @click="updateQuantity('minus', formatNameForInput(promo.promo_title),tour,index)"><i
                            class="bx bx-minus"></i></button>
                    <input readonly type="text" v-model="promo.quantity">
                    <button type="button"
                        @click="updateQuantity('plus', formatNameForInput(promo.promo_title),tour,index)"><i
                            class="bx bx-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
