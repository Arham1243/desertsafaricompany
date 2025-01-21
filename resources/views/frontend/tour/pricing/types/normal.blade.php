@if ($tour->is_person_type_enabled && $tour->normalPrices->isNotEmpty())
    @foreach ($tour->normalPrices as $normalPrice)
        <div class="form-group form-guest-search">
            <div class="tour-content__pra form-book__pra form-guest-search__details">
                {{ $normalPrice->person_type }}
                <div class="form-guest-search__items">
                    <div class="form-book__title form-guest-search__title">
                        {{ $normalPrice->person_description }}
                        <div class="form-guest-search__smallTitle">{{ formatPrice($normalPrice->price) }} per
                            person</div>
                    </div>
                    <div class="quantity-counter">
                        <button class="quantity-counter__btn" type="button"
                            @click="updateQuantity('minus', '{{ formatNameForInput($normalPrice->person_type) }}')">
                            <i class='bx bx-chevron-down'></i>
                        </button>
                        <input readonly type="number"
                            class="person-quanity quantity-counter__btn quantity-counter__btn--quantity"
                            v-model="normalTourData['{{ formatNameForInput($normalPrice->person_type) }}'].quantity"
                            name="price[{{ formatNameForInput($normalPrice->person_type) }}][quantity]">
                        <input type="hidden" name="price[{{ formatNameForInput($normalPrice->person_type) }}][price]"
                            value="{{ $normalPrice->price }}">
                        <button class="quantity-counter__btn" type="button"
                            @click="updateQuantity('plus', '{{ formatNameForInput($normalPrice->person_type) }}')">
                            <i class='bx bx-chevron-up'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
