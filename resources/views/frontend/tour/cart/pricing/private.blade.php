<template v-if="getPrivateTourPricing(tour.id)">
    <div class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            Car Price
            <div class=form-guest-search__items>
                <div class="form-book__title form-guest-search__title">

                    Min: @{{ getPrivateTourPricing(tour.id)['persons']['min_person'] }}
                    Max: @{{ getPrivateTourPricing(tour.id)['persons']['max_person'] }}
                    Persons
                    <div clFass=form-guest-search__smallTitle>
                        <span v-html="formatPrice(getPrivateTourPricing(tour.id)['persons']['car_price'])"></span> per
                        person
                    </div>
                </div>
                <div class="cart__productQuantity-btns">
                    <button type="button" @click="updateQuantity('minus','persons',tour)"><i
                            class="bx bx-minus"></i></button>
                    <input readonly type="text" min="0" name="price[persons][quantity]"
                        v-model="getPrivateTourPricing(tour.id)['persons']['quantity']">
                    <button type="button" @click="updateQuantity('plus','persons',tour)"><i
                            class="bx bx-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>
