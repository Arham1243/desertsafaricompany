@if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
    <div v-for="(promo, index) in promoTourData" :key="index" class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            <div class="d-flex gap-2">
                <span>@{{ promo.promo_title }} </span>
                <span>@{{ promo.discount_percent }}% off</span>
            </div>
            <div class="form-guest-search__items">
                <div class="prices-wrapper">
                    <div class="del-price cut">@{{ formatPrice(promo.original_price) }}</div>
                    <div class="new-price green">@{{ formatPrice(promo.discounted_price) }}</div>
                </div>

                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button"
                        @click="updateQuantity('minus', formatNameForInput(promo.promo_title))">
                        <i class="bx bx-chevron-down"></i>
                    </button>
                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                        min="0" v-model="promo.quantity"
                        :name="`price[${formatNameForInput(promo.promo_title)}][quantity]`">

                    <button class="quantity-counter__btn" type="button"
                        @click="updateQuantity('plus', formatNameForInput(promo.promo_title))">
                        <i class="bx bx-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
