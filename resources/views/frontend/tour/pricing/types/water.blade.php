@if ($isDataValid)
    <div class="tour-content__pra m-0">Select Timeslot:</div>
    <select name="time_slot" v-model="timeSlot" class="select-field" required>
        <option value=""selected>Select</option>
        @foreach ($tour->waterPrices as $i => $waterPrice)
            @php
                $timeSlot = $waterPricesTimeSlots[$i];
            @endphp
            <option value="{{ $timeSlot }}" time-price="{{ $waterPrice->water_price }}">
                {{ $timeSlot }} ({{ formatPrice($waterPrice->water_price) }})
            </option>
        @endforeach
    </select>
    <div class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            Seleted Timeslot
            <div class="form-guest-search__items">
                <div class="form-book__title form-guest-search__title">
                    @{{ waterPrices[waterPricesTimeSlots.indexOf(timeSlot)]?.time || 'Water activity' }}
                    <div class="form-guest-search__smallTitle">
                        <span v-if="timeSlot">
                            @{{ formatPrice(waterPrices[waterPricesTimeSlots.indexOf(timeSlot)]?.water_price || 0) }}
                        </span>
                        <span v-else>Select a timeslot</span>
                    </div>
                </div>
                <div class="quantity-counter">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('minus')">
                        <i class='bx bx-chevron-down'></i>
                    </button>
                    <input readonly type="number"
                        class="person-quanity quantity-counter__btn quantity-counter__btn--quantity" min="0"
                        v-model="timeSlotQuantity" name="time_slot_quantity">
                    <input type="hidden" name="time_slot_price"
                        :value="waterPrices[waterPricesTimeSlots.indexOf(timeSlot)]?.water_price">
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus')">
                        <i class='bx bx-chevron-up'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
