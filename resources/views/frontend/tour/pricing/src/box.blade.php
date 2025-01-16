<div id="pricing-box">
    <div class=tour-content_book_wrap>
        @php
            $total_price = 0;

            if ($tour->enabled_custom_service_fee === 1) {
                $total_price += $tour->service_fee_price;
            }

            if ($tour->is_extra_price_enabled === 1 && $tour->extra_prices) {
                foreach (json_decode($tour->extra_prices) as $extra_price) {
                    $total_price += $extra_price->price;
                }
            }
        @endphp

        @switch($tour->price_type)
            @case('normal')
                @include('frontend.tour.pricing.normal')
            @break

            @case('water')
                @include('frontend.tour.pricing.water')
            @break

            @case('promo')
                @include('frontend.tour.pricing.promo')
            @break

            @case('private')
                @include('frontend.tour.pricing.private')
            @break

            @default
                @include('frontend.tour.pricing.simple')
        @endswitch
    </div>
</div>
@push('js')
    @include('frontend.tour.pricing.src.js.box')
@endpush
