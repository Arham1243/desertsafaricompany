<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'three-items-slider' : 'row-cols-1 row-cols-lg-2 row-cols-xl-3' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <div class=card-content>
                <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                    <div class=price-details>
                        <div class=heart-icon>
                            <div class=service-wishlis>
                                <i class="bx bx-heart"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <div class=tour-activity-card__details>
                    <div class=vertical-activity-card__header>
                        <div><span>
                                {{ $tour->Formated_price_type ?? 'From ' . formatPrice($tour->regular_price) }}</span>
                        </div>
                        <div class="tour-activity-card__details--title">
                            {{ $tour->title }}
                        </div>
                    </div>
                    <div class=tour-activity__RL>
                        <div class=card-rating>
                            <i class="bx bxs-star"></i>
                            <span>5.0 1 Rating</span>
                        </div>
                        <div @if ($tour->cities->isNotEmpty()) data-tooltip="tooltip" title="{{ $tour->cities->pluck('name')->implode(', ') }}" @endif
                            class=card-location>
                            <i class="bx bx-location-plus"></i>
                            @if ($tour->cities->isNotEmpty())
                                {{ $tour->cities[0]->name }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
