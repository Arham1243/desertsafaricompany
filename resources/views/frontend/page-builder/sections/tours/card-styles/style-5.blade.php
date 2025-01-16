<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'four-items-slider' : 'row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <div class="card-content normal-card__content">
                <a href="{{ route('tours.details', $tour->slug) }} " class="card_img normal-card__img">
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                    <div class="price-details">
                        <div class="heart-icon">
                            <div class="service-wishlis">
                                <i class="bx bx-heart"></i>
                            </div>
                        </div>
                        <div class="sale_info">
                            38%
                        </div>
                    </div>
                </a>
                <div class="tour-activity-card__details normal-card__details">
                    <div class="vertical-activity-card__header">
                        @if ($tour->cities->isNotEmpty())
                            <div class="normal-card__location" data-tooltip="tooltip"
                                title="{{ $tour->cities->pluck('name')->implode(', ') }}">
                                <i class="bx bxs-paper-plane"></i>{{ $tour->cities[0]->name }}
                            </div>
                        @endif
                        <div class="tour-activity-card__details--title"> {{ $tour->title }}</div>
                    </div>
                    <div class="tour-listing__info normal-card__info">
                        @if (json_decode($tour->features) && json_decode($tour->features)[0]->title && json_decode($tour->features)[0]->icon)
                            <div class="duration">
                                <i class="{{ json_decode($tour->features)[0]->icon }}"></i>
                                {{ json_decode($tour->features)[0]->title }}
                            </div>
                        @endif
                        <div class="baseline-pricing__value baseline-pricing__value--high">
                            <p class="baseline-pricing__from">
                                <span class="baseline-pricing__from--value">
                                    {{ $tour->Formated_price_type ?? 'From ' . formatPrice($tour->regular_price) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
