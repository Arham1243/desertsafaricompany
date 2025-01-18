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
                @include('frontend.tour.price_type.normal')
            @break

            @case('water')
                @include('frontend.tour.price_type.water')
            @break

            @case('promo')
                @include('frontend.tour.price_type.promo')
            @break

            @case('private')
                @include('frontend.tour.price_type.private')
            @break

            @default
                @php
                    $total_price += $tour->sale_price;
                @endphp
                @include('frontend.tour.price_type.simple')
        @endswitch
    </div>
</div>
