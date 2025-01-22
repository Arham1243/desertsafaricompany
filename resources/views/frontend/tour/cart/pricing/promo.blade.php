<div v-if="tour.is_person_type_enabled && getPromoTourPricing(tour.id)">
    <div v-for="(promo, index) in getPromoTourPricing(tour.id)" :key="index"
        class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            @{{ promo.promo_title }}
            <div class="form-guest-search__items">
                <div class="form-book__title form-guest-search__title">
                    Expires in: @{{ promo.offer_expire_at }}
                    <div class="prices-wrapper">
                        <div class="new-price">
                            {{ env('APP_CURRENCY') }}
                        </div>
                        <div class="del-price cut">
                            @{{ promo.original_price }}</div>
                        <div class="new-price green">@{{ promo.discount_price }}</div>
                    </div>
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
