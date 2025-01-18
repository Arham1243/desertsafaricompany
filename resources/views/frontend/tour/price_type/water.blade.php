@php
    $isDataValid = $tour->waterPrices->isNotEmpty();
    $waterPricesTimeSlots = $tour->waterPrices->isNotEmpty() ? $tour->waterPrices->pluck('time') : null;
@endphp
<div class=tour-content_book_app>

    <div class=form-book>
        @if ($isDataValid)
            <form class="form-book_details" method="POST" action="{{ route('tours.cart.add', $tour->id) }}">
                @csrf
            @else
                <div class=form-book_details>
        @endif


        <div class=form-book_content>

            <div class="tour-content__pra form-book__pra">
                Start Date
            </div>
            <div class="tour-content__title form-book__title">
                <input type="date" class="form-book__date" name="start_date" required id="start_date">
            </div>

        </div>

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
                                class="person-quanity quantity-counter__btn quantity-counter__btn--quantity"
                                min="0" v-model="timeSlotQuantity" name="time_slot_quantity">
                            <button class="quantity-counter__btn" type="button" @click="updateQuantity('plus')">
                                <i class='bx bx-chevron-up'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @include('frontend.tour.price_type.extra_price')
        @include('frontend.tour.price_type.service_fee')
        @include('frontend.tour.price_type.total_price')

        <div class=form-guest__btn>
            @if (Auth::check())
                @if (!$isTourInCart)
                    <button class="app-btn themeBtn w-100" :disabled="!isSubmitEnabled"
                        @if (!$isDataValid) disabled @endif>Book
                        Now</button>
                @else
                    <a href="{{ route('tours.cart.index') }}" class="app-btn themeBtn w-100">View Cart</a>
                @endif
            @else
                <button class="app-btn themeBtn w-100" disabled>Login to Continue</button>
            @endif
        </div>
        @if ($isDataValid)
            </form>
        @else
    </div>
    @endif
</div>
</div>
