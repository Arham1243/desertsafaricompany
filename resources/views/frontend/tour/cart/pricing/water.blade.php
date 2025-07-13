<template v-if="getWaterTourPricing(tour.id)">
    <div class="tour-content__pra m-0">Select Timeslot:</div>
    <select @change="handleTimeSlotChange($event, tour.id)" name="time_slot"
        v-model="cart['tours'][tour.id]['data']['time_slot']" class="select-field dirham" required>
        <option v-for="water in getWaterTourTimeSlots(tour.id)" :value="water.time" :time-price="water.water_price">
            @{{ water.time }} (<span v-html="formatPrice(water.water_price)"></span>)
        </option>
    </select>
    <div class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            Seleted Timeslot
            <div class="form-guest-search__items">
                <div class="form-book__title form-guest-search__title">
                    @{{ cart['tours'][tour.id]['data']['time_slot'] || 'Water activity' }}
                    <div class="form-guest-search__smallTitle">
                        <span v-html="formatPrice(cart['tours'][tour.id]['data']['time_slot_price'] || 0)"></span>
                    </div>
                </div>
                <div class="cart__productQuantity-btns">
                    <button type="button" @click="updateQuantity('minus','car',tour)"><i
                            class="bx bx-minus"></i></button>
                    <input readonly type="text" min="0"
                        v-model="cart['tours'][tour.id]['data']['time_slot_quantity']">
                    <button type="button" @click="updateQuantity('plus','car',tour)"><i
                            class="bx bx-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>
