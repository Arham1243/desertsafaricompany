<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'three-items-slider' : 'row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <div class=card-content>
                <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                    <div class=price-details>
                        <div class=price>
                            <span>
                                Top pick
                            </span>
                        </div>
                        <div class=heart-icon>
                            <div class=service-wishlis>
                                <i class="bx bx-heart"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <div class=tour-activity-card__details>
                    <div class=vertical-activity-card__header>
                        @if ($tour->category)
                            <div><span> {{ $tour->category->name }}</span></div>
                        @endif
                        <a href="{{ route('tours.details', $tour->slug) }}"
                            class="tour-activity-card__details--title">{{ $tour->title }}</a>
                    </div>
                    <div class=card-rating>
                        <x-star-rating :rating="$tour->average_rating" />
                        @if ($tour->reviews->count() > 0)
                            {{ $tour->reviews->count() }}
                            Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                        @else
                            <span>No Reviews Yet</span>
                        @endif
                    </div>
                    <div class="baseline-pricing__value baseline-pricing__value--high">
                        <p class=baseline-pricing__from>
                            <span class="baseline-pricing__from--text receive">Receive voucher
                                instantly</span>
                        </p>
                    </div>
                    <div class="baseline-pricing__value baseline-pricing__value--high">
                        <p class=baseline-pricing__from>
                            @if ($tour->formated_price_type)
                                <span
                                    class="baseline-pricing__from--value green">{{ $tour->formated_price_type }}</span>
                            @else
                                <span class=baseline-pricing__from--text>From </span>
                                <span class="baseline-pricing__from--value green">
                                    {{ formatPrice($tour->regular_price) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
