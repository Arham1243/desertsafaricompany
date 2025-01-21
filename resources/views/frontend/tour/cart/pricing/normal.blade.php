<div v-if="tour.is_person_type_enabled">
    <div class="form-group form-guest-search" v-for="(normalPrice, index) in getNormalTourPricing(tour.id)"
        :key="index">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            @{{ normalPrice.person_type }}
            <div class="form-guest-search__items">
                <div class="form-book__title form-guest-search__title">
                    @{{ normalPrice.person_description }}
                    <div class="form-guest-search__smallTitle">@{{ formatPrice(normalPrice.price) }} per
                        person</div>
                </div>
                <div class="cart__productQuantity-btns">
                    <button type="button"
                        @click="updateQuantity('minus', formatNameForInput(normalPrice.person_type),tour,index)"><i
                            class="bx bx-minus"></i></button>
                    <input readonly type="text"
                        v-model="toursNormalPrices[tour.id][formatNameForInput(normalPrice.person_type)].quantity"
                        name="price[formatNameForInput(normalPrice.person_type)][quantity]">
                    <button type="button"
                        @click="updateQuantity('plus', formatNameForInput(normalPrice.person_type),tour,index)"><i
                            class="bx bx-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
