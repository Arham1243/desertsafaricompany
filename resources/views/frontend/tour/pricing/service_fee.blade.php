@if ($tour->enabled_custom_service_fee === 1 && $tour->service_fee_price)
    <div class="form-group form-guest-search">
        <div class=form-guest-search__title>
            <input type="hidden" name="service_fee" value="{{ $tour->service_fee_price }}">
            <div class="form-guest-search__items Service-fee__content">
                <div class=form-guest-search__title>
                    Service Fee:
                </div>
                <div class="tour-content__pra form-book__pra">
                    {{ formatPrice($tour->service_fee_price) }}
                </div>
            </div>
        </div>
    </div>
@endif
