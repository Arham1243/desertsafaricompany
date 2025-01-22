<template v-if="getPrivateTourPricing(tour.id)">
    <div class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            Total Persons
            <div class=form-guest-search__items>
                <div class="form-book__title form-guest-search__title">
                    Car Price
                    <div clFass=form-guest-search__smallTitle>
                        @{{ formatPrice(getPrivateTourPricing(tour.id)['persons']['car_price']) }} </div>
                </div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus',tour)">
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <input readonly type="number"
                        class="person-quanity quantity-counter__btn quantity-counter__btn--quantity" min="0"
                        name="price[persons][quantity]" v-model="getPrivateTourPricing(tour.id)['persons']['quantity']">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus',tour)">
                        <i class='bx bx-chevron-up'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
