@switch($style)
    @case('style1')
        <div class=card-content>
            <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class=price-details>
                    <div class=price>
                        <span>
                            <b>{{ $tour->formated_price_type ?? formatPrice($tour->regular_price) . ' From' }}</b>
                        </span>
                    </div>
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <div class="service-wishlist">
                                    <i class="bx bxs-heart"></i>
                                </div>
                            @else
                                <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"> <i class="bx bx-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
            <div class=card-details>
                <a href={{ route('tours.details', $tour->slug) }} data-tooltip="tooltip" class=card-title
                    title="{{ $tour->title }}">{{ $tour->title }}</a>
                @if ($tour->cities->isNotEmpty())
                    <div @if ($tour->cities->isNotEmpty()) data-tooltip="tooltip" title="{{ $tour->cities->pluck('name')->implode(', ') }}" @endif
                        class=location-details><i class="bx bx-location-plus"></i>
                        @if ($tour->cities->isNotEmpty())
                            {{ $tour->cities[0]->name }}
                        @endif
                    </div>
                @endif
                <div class=card-rating>
                    <x-star-rating :rating="$tour->average_rating" />
                    @if ($tour->reviews->count() > 0)
                        {{ $tour->reviews->count() }}
                        Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                    @else
                        <span>No Reviews Yet</span>
                    @endif
                </div>
            </div>
        </div>
    @break

    @case('style2')
        <div class=card-content>
            <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class=price-details>
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <div class="service-wishlist">
                                    <i class="bx bxs-heart"></i>
                                </div>
                            @else
                                <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"> <i class="bx bx-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
            <div class=tour-activity-card__details>
                <div class=vertical-activity-card__header>
                    <div><span>
                            {{ $tour->formated_price_type ?? 'From ' . formatPrice($tour->regular_price) }}</span>
                    </div>
                    <a href="{{ route('tours.details', $tour->slug) }}" class="tour-activity-card__details--title">
                        {{ $tour->title }}
                    </a>
                </div>
                <div class=tour-activity__RL>
                    <div class=card-rating>
                        <x-star-rating :rating="$tour->average_rating" />
                        @if ($tour->reviews->count() > 0)
                            {{ $tour->reviews->count() }}
                            Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                        @else
                            <span>No Reviews Yet</span>
                        @endif
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
    @break

    @case('style3')
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
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <div class="service-wishlist">
                                    <i class="bx bxs-heart"></i>
                                </div>
                            @else
                                <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"> <i class="bx bx-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
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
                            <span class="baseline-pricing__from--value green">{{ $tour->formated_price_type }}</span>
                        @else
                            <span class=baseline-pricing__from--text>From </span>
                            <span class="baseline-pricing__from--value green">
                                {{ formatPrice($tour->regular_price) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @break

    @case('style4')
        <div class="card-content trending-products__content">
            <a href="{{ route('tours.details', $tour->slug) }} " class="card_img trending-products__img" tabindex="0">
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
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <div class="service-wishlist">
                                    <i class="bx bxs-heart"></i>
                                </div>
                            @else
                                <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"> <i class="bx bx-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
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
    @break

    @case('style5')
        <div class="card-content normal-card__content">
            <a href="{{ route('tours.details', $tour->slug) }} " class="card_img normal-card__img">
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class="price-details">
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <div class="service-wishlist">
                                    <i class="bx bxs-heart"></i>
                                </div>
                            @else
                                <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit"> <i class="bx bx-heart"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
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
                    <a href="{{ route('tours.details', $tour->slug) }}" class="tour-activity-card__details--title">
                        {{ $tour->title }}</a>
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
                                {{ $tour->formated_price_type ?? 'From ' . formatPrice($tour->regular_price) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @break

@endswitch
