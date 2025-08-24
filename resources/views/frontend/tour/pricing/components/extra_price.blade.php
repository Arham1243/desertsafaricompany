@if ($tour->is_extra_price_enabled === 1 && $tour->extra_prices)
    <div class="form-group form-guest-search extra-price-block">
        <div class="tour-content__title form-book__title form-guest-search__title">
            Extra prices:
            @foreach (json_decode($tour->extra_prices) as $index => $extra_price)
                <div class="form-guest-search__items form-guest-search__details">
                    <div class="form-book__title form-guest-search__title">
                        <label class="form-guest-search__item-clean">
                            <input type="hidden" name="extra_prices[{{ $index }}][name]"
                                value="{{ $extra_price->name }}">
                            <input type="hidden" name="extra_prices[{{ $index }}][price]"
                                value="{{ $extra_price->price }}">
                            {{ $extra_price->name }}
                        </label>
                    </div>
                    <div class="tour-content__pra form-book__pra">
                        {{ formatPrice($extra_price->price) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
