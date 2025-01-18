<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'four-items-slider' : 'row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <div class="card-content trending-products__content">
                <a href="{{ route('tours.details', $tour->slug) }} " class="card_img trending-products__img"
                    tabindex="0">
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                    <div class="product-rank">
                        TOP 1
                    </div>
                    <div class="price-details">
                        @if ($tour->cities->isNotEmpty())
                            <div class="price-location" data-tooltip="tooltip"
                                title="{{ $tour->cities->pluck('name')->implode(', ') }}">
                                <i class="bx bxs-location-plus"></i>
                                {{ $tour->cities[0]->name }}
                            </div>
                        @endif
                        <div class="heart-icon">
                            <div class="service-wishlis">
                                <i class="bx bx-heart"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="tour-activity-card__details">
                    <div class="vertical-activity-card__header">
                        <a href="{{ route('tours.details', $tour->slug) }}" class="tour-activity-card__details--title">
                            {{ $tour->title }}
                        </a>
                        <div class="product-card__tag"><span title="Receive voucher instantly" class="tag">Receive
                                voucher instantly</span></div>
                    </div>
                    <div class="tour-activity__RL">
                        <div class="card-rating">
                            <i class="bx bxs-star green-star"></i>
                            <span>
                                @if ($tour->reviews->count() > 0)
                                    {{ $tour->reviews->count() }}
                                    Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                                @else
                                    <span>No Reviews Yet</span>
                                @endif | 200K+ booked
                            </span>
                        </div>
                    </div>
                    <div class="top10-trending-products__price">
                        {{ $tour->formated_price_type ?? 'From ' . formatPrice($tour->regular_price) }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
