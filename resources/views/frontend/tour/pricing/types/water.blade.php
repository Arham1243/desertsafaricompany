@if ($isDataValid)
    <div class="tour-content__pra m-0">Select Timeslot:</div>
    <select v-model="timeSlot" class="select-field" required>
        <option value="">Select</option>
        <option v-for="(slot, key) in waterTourData" :key="key" :value="key"
            :time-price="slot.original_price">
            @{{ key }} (<span v-html="formatPrice(slot.original_price)"></span>)
        </option>
    </select>

    <div class="form-group form-guest-search" v-if="timeSlot">
        <div class="form-guest-search__details normal">
            <div class="form-book__title form-guest-search__title">
                Selected Timeslot @{{ waterTourData[timeSlot]?.time || 'Water activity' }}
            </div>
            <div class="promo-title">@{{ waterTourData[timeSlot]?.description }}</div>

            <div class="promo-price-wrapper">
                <div class="promo-price" :class="{ cut: isFirstOrderCouponApplied }"
                    v-if="waterTourData[timeSlot]?.original_price">
                    <span v-html="formatPrice(waterTourData[timeSlot].original_price)"></span>
                </div>
                <div class="promo-price purple" v-if="isFirstOrderCouponApplied">
                    <span v-html="formatPrice(waterTourData[timeSlot]?.promo_discounted_price)"></span>
                </div>
            </div>

            <div class="promo-og-offer purple" v-if="!hasUsedFirstOrderCoupon">
                <span class="offer">
                    <span
                        v-html="formatPrice(
                  waterTourData[timeSlot]?.promo_discounted_price ||
                  waterTourData[timeSlot]?.discounted_price ||
                  waterTourData[timeSlot]?.original_price || 0
                )"></span>
                    with promo asd
                </span>
            </div>

            <span v-if="isFirstOrderCouponApplied" class="promo-applied purple d-flex align-items-center mt-1">
                <i style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied
            </span>

            <div class="form-guest-search__items justify-content-between" style="margin-top: 0.25rem">
                <div class="already-bought"></div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus', timeSlot)">
                        <i class="bx bx-chevron-down"></i>
                    </button>

                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                        v-model="waterTourData[timeSlot].quantity" :name="`price[${timeSlot}][quantity]`" />

                    <input type="hidden" :name="`price[${timeSlot}][price]`"
                        :value="isFirstOrderCouponApplied
                            ?
                            waterTourData[timeSlot]?.promo_discounted_price :
                            waterTourData[timeSlot]?.original_price" />

                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus', timeSlot)">
                        <i class="bx bx-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
