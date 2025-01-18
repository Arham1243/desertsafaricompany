@if ($isDataValid)
    <div class="tour-content__pra m-0">Select Timeslot:</div>
    <select name="time_slot" v-model="timeSlot" class="select-field" required>
        <option value=""selected>Select</option>
        @foreach ($tour->waterPrices as $i => $waterPrice)
            @php
                $timeSlot = $waterPricesTimeSlots[$i];
            @endphp
            <option value="{{ $timeSlot }}" time-price="{{ $waterPrice->water_price }}">
                {{ $timeSlot }} (AED {{ number_format($waterPrice->water_price, 2) }})
            </option>
        @endforeach
    </select>
    <div class="form-group form-guest-search">
        <div class="tour-content__pra form-book__pra form-guest-search__details">
            Water activity
            <div class="form-guest-search__items">
                <div class="form-book__title form-guest-search__title">
                    Selected Time Slot
                    <div class="form-guest-search__smallTitle">
                        <span v-if="timeSlot">
                            AED @{{ parseFloat(waterPrices[waterPricesTimeSlots.indexOf(timeSlot)]?.water_price || 0).toFixed(2) }}
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
                    <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus')">
                        <i class='bx bx-chevron-up'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
