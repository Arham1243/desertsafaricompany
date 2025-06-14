<template v-if="tour.is_person_type_enabled && getPromoTourPricing(tour.id)">
    <div v-for="(promo, index) in getPromoTourPricing(tour.id)" :key="index"
        class="form-group form-guest-search">
        <div class="form-guest-search__details promo">
            <div class="promo-title">@{{ promo.promo_title }}</div>
            <div class="promo-price-wrapper">
                <div class="promo-price cut">@{{ formatPrice(promo.original_price) }}</div>
                <div class="promo-price green">@{{ formatPrice(promo.discounted_price) }}</div>
                <span class="percent-off-tag">@{{ promo.discount_percent }}% Off</span>
            </div>
            <div class="promo-og-offer">
                <span class="offer">@{{ formatPrice(promo.discounted_price) }} with promo</span>
                <span :class="['time-left', promo.hours_left <= 2 ? 'blink-red' : '']">
                    @{{ promo.hours_left }} hour@{{ promo.hours_left === 1 ? '' : 's' }} left
                </span>
            </div>
            <div class="form-guest-search__items justify-content-between">
                <div class="already-bought"></div>
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
    @push('css')
        <style>
            .promo-title {
                font-size: 17px;
                font-weight: 700;
            }

            .promo-og-offer .offer {
                font-weight: 600;
            }

            .promo-price {
                font-size: 18px;
                font-weight: 600;
            }
        </style>
    @endpush
</template>
