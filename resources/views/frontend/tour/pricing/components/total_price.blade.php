<div class="form-group form-guest-search">
    <div class=form-guest-search__title>

        <div class="form-guest-search__items Service-fee__content mb-2">
            <div class=form-guest-search__title>
                Total Price:
            </div>
            <div class="tour-content__pra form-book__pra total-price">
                <input type="hidden"
                    :value="totalPrice -
                        {{ $tour->enabled_custom_service_fee === 1 && $tour->service_fee_price ? $tour->service_fee_price : 0 }}"
                    name="subtotal" />
                <input type="hidden" :value="totalPrice" name="total_price" />
                <span class="green" style="font-weight:500;">@{{ formatPrice(totalPrice) }}</span>
            </div>
        </div>
    </div>
</div>
