@switch($style)
    @case('style1')
        <div class=card-content>
            <a href={{ $detailUrl }} class=card_img>
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class=price-details>
                    <div class=price>
                        <span>
                            <b>{!! $tour->formated_price_type ?? formatPrice($tour->sale_price) . ' From' !!}</b>
                        </span>
                    </div>
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <form class="service-wishlist" action="{{ route('tours.favorites.index') }}" method="get">
                                    <button type="submit"> <i class="bx bxs-heart"></i></button>
                                </form>
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
                <a href={{ $detailUrl }} data-tooltip="tooltip" class=card-title
                    title="{{ $tour->title }}">{{ $tour->title }}</a>
                @if ($tour->city)
                    <div class="location-details" data-tooltip="tooltip" title="{{ $tour->city->name }}">
                        <i class="bx bx-location-plus"></i>
                        {{ $tour->city->name }}
                    </div>
                @endif
                <div class=card-rating>
                    <x-star-rating :rating="$tour->average_rating" />
                    @if ($tour->reviews->count() > 0)
                        {{ $tour->reviews->count() }}
                        Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                    @else
                        <span>No reviews yet</span>
                    @endif
                </div>
            </div>
        </div>
    @break

    @case('style2')
        <div class=card-content>
            <div class="card_img_wrapper">
                <div class="price-details justify-content-end">
                    <div class="heart-icon">
                        @php
                            $isFavorited = Auth::check() && Auth::user()->favoriteTours->contains($tour->id);
                        @endphp

                        @if (!Auth::check())
                            <button type="button" open-vue-login-popup
                                onclick="showMessage('Please log in to add this tour to favorites.', 'error','top-right')">
                                <i class="bx bx-heart"></i>
                            </button>
                        @elseif ($isFavorited)
                            <form class="service-wishlist" action="{{ route('tours.favorites.index') }}" method="get">
                                <button type="submit"><i class="bx bxs-heart"></i></button>
                            </form>
                        @else
                            <form class="service-wishlist" action="{{ route('tours.favorites.add', $tour->id) }}"
                                method="post">
                                @csrf
                                <button type="submit"><i class="bx bx-heart"></i></button>
                            </form>
                        @endif
                    </div>
                </div>
                <a href={{ $detailUrl }} class=card_img>
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                </a>
            </div>
            <div class=tour-activity-card__details>
                <div class=vertical-activity-card__header>
                    <div class="lowest-price">
                        @if ($tour->tour_lowest_price)
                            <span>From<div class="flex">{!! formatPrice($tour->tour_lowest_price) !!}</div></span>
                        @endif
                    </div>
                    <a title="{{ $tour->title }}" @if (strlen($tour->title) > 20) data-tooltip="tooltip" @endif
                        href="{{ $detailUrl }}" class="tour-activity-card__details--title line-clamp-1">
                        {{ $tour->title }}
                    </a>
                </div>
                <div class=tour-activity__RL>
                    <div class=card-rating>
                        <x-star-rating :rating="$tour->average_rating" />
                        @if ($tour->reviews->count() > 0)
                            <span>{{ $tour->reviews->count() }} Review{{ $tour->reviews->count() > 1 ? 's' : '' }}</span>
                        @else
                            <span>No reviews yet</span>
                        @endif
                    </div>
                    @if ($tour->city)
                        <div class="card-location">
                            <i class="bx bx-location-plus"></i>
                            {{ $tour->city->name }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @break

    @case('style3')
        {{-- <div class=card-content>
                <a href={{ $detailUrl }} class=card_img>
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                    <div class=price-details>
                        @if ($tour->orders()->count() > 5)
                            <div class=price>
                                <span>
                                    Top pick
                                </span>
                            </div>
                        @endif
                        @if (Auth::check())
                            <div class="heart-icon">
                                @php
                                    $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                                @endphp
                                @if ($isFavorited)
                                    <form class="service-wishlist" action="{{ route('tours.favorites.index') }}" method="get">
                                        <button type="submit"> <i class="bx bxs-heart"></i></button>
                                    </form>
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
                        <a href="{{ $detailUrl }}"
                            class="tour-activity-card__details--title">{{ $tour->title }}</a>
                    </div>
                    <div class=card-rating>
                        <x-star-rating :rating="$tour->average_rating" />
                        @if ($tour->reviews->count() > 0)
                            {{ $tour->reviews->count() }}
                            Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                        @else
                            <span>No reviews yet</span>
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
                                    {!! formatPrice($tour->sale_price) !!}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div> --}}

        <div class="tour-card tour-card--style3 img-zoom-wrapper">
            @php
                $certifiedTagContent = json_decode($tour->certified_tag) ?? null;
                $badgeTagContent = json_decode($tour->badge_tag) ?? null;
                $bookedTextContent = json_decode($tour->booked_text);

                $labels = $bookedTextContent->label ?? [];
                $bgColors = $bookedTextContent->background_color ?? [];
                $textColors = $bookedTextContent->text_color ?? [];

                if (!is_array($labels)) {
                    $labels = [$labels];
                }
                if (!is_array($bgColors)) {
                    $bgColors = [$bgColors];
                }
                if (!is_array($textColors)) {
                    $textColors = [$textColors];
                }

                $labels = array_filter($labels, fn($label) => !empty(trim($label)));
            @endphp
            <div class="tour-card__img">
                <div class="tour-actions">
                    <div>
                        @if ($badgeTagContent && $badgeTagContent->enabled === '1')
                            <div class="top-badge"
                                @if ($badgeTagContent->background_color || $badgeTagContent->text_color) style="
                                    {{ $badgeTagContent->background_color ? 'background:' . $badgeTagContent->background_color . ';' : '' }}
                                    {{ $badgeTagContent->text_color ? 'color:' . $badgeTagContent->text_color . ';' : '' }}" @endif>
                                {{ $badgeTagContent->label }}
                            </div>
                        @endif

                    </div>
                    @php
                        $isFavorited = Auth::check() ? Auth::user()->favoriteTours->contains($tour->id) : null;
                    @endphp
                    @if (!Auth::check())
                        <button open-vue-login-popup
                            onclick="showMessage('Please log in to add this tour to favorites.', 'error','top-right')"
                            type="button" class="heart-icon">
                            <i class="bx bx-heart"></i>
                        </button>
                    @elseif ($isFavorited)
                        <a href="{{ route('tours.favorites.index') }}">
                            <button class="heart-icon">
                                <i class="bx bxs-heart red-heart"></i>
                            </button>
                        </a>
                    @else
                        <form action="{{ route('tours.favorites.add', $tour->id) }}" method="post">
                            @csrf
                            <button class="heart-icon" type="submit"> <i class="bx bx-heart"></i></button>
                        </form>
                    @endif
                </div>
                <a href={{ $detailUrl }} class="img-wrapper img-zoom">
                    <img data-src="{{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                </a>
            </div>
            <div class="tour-card__content">
                <a href="{{ $detailUrl }}" class="title line-clamp-3"
                    @if (strlen($tour->title) > 90) data-tooltip="tooltip" @endif
                    title="{{ $tour->title }}">{{ $tour->title }}</a>
                <div class="certified-tag-wrapper">
                    @if ($certifiedTagContent && $certifiedTagContent->enabled === '1')
                        <div class="certified-tag line-clamp-2"><i class='{{ $certifiedTagContent->icon }}'></i>
                            {{ $certifiedTagContent->label }}</div>
                    @endif
                </div>
                <div class="booked-details-wrapper" data-tour-card-badge-container>
                    @if ($bookedTextContent && $bookedTextContent->enabled === '1' && !empty($labels))
                        @foreach ($labels as $index => $label)
                            <div class="booked-details line-clamp-1"
                                style="
                    {{ !empty($bgColors[$index] ?? null) ? 'background:' . $bgColors[$index] . ';' : '' }}
                    {{ !empty($textColors[$index] ?? null) ? 'color:' . $textColors[$index] . ';' : '' }}
                ">
                                {{ $label }}
                            </div>
                        @endforeach
                    @endif
                </div>
                @php
                    $bookingRestrictionsBadge = $tour->booking_restrictions_badge
                        ? json_decode($tour->booking_restrictions_badge, true)
                        : [];
                @endphp
                <div class="booked-details-wrapper advance-booking-tag" style="height: 21px">
                    @if ($tour->is_advance_booking && $tour->advance_booking_badge)
                        <div class="booked-details">
                            You can book for {{ $tour->advance_booking_badge }}
                        </div>
                    @endif
                </div>
                <div class="card-rating">
                    <x-star-rating :rating="$tour->average_rating" />
                    @if ($tour->reviews->count() > 0)
                        <span>{{ $tour->reviews->count() }} Review{{ $tour->reviews->count() > 1 ? 's' : '' }}</span>
                    @else
                        <span>No reviews yet</span>
                    @endif
                </div>
                @if ($tour->lowest_promo_price)
                    <div class="pricing-details-wrapper">
                        <del class="pricing-details pricing-details--del">From
                            {{ formatPrice($tour->lowest_promo_price['original'], false) }}</del>
                        <ins class="pricing-details pricing-details--ins"><span
                                class="new-price">{{ formatPrice($tour->lowest_promo_price['discounted'], false) }}</span> per
                            person</ins>
                    </div>
                @endif

            </div>
        </div>
    @break

    @case('style4')
        <div class="card-content trending-products__content">
            <a href="{{ $detailUrl }} " class="card_img trending-products__img" tabindex="0">
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class="product-rank">
                    TOP 1
                </div>
                <div class="price-details">
                    @if ($tour->city)
                        <div class="price-location" data-tooltip="tooltip" title="{{ $tour->city->name }}">
                            <i class="bx bxs-location-plus"></i>
                            {{ $tour->city->name }}
                        </div>
                    @endif
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <form class="service-wishlist" action="{{ route('tours.favorites.index') }}" method="get">
                                    <button type="submit"> <i class="bx bxs-heart"></i></button>
                                </form>
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
                    <a href="{{ $detailUrl }}" class="tour-activity-card__details--title">
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
                                <span>No reviews yet</span>
                            @endif
                            @if ($tour->orders()->count() > 0)
                                | {{ formatBigNumber($tour->orders()->count()) }} Booked
                            @endif
                        </span>
                    </div>
                </div>
                <div class="top10-trending-products__price">
                    {!! $tour->formated_price_type ?? 'From ' . formatPrice($tour->sale_price) !!}
                </div>
            </div>
        </div>
    @break

    @case('style5')
        <div class="card-content normal-card__content">
            <a href="{{ $detailUrl }} " class="card_img normal-card__img">
                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                <div class="price-details">
                    @if (Auth::check())
                        <div class="heart-icon">
                            @php
                                $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                            @endphp
                            @if ($isFavorited)
                                <form class="service-wishlist" action="{{ route('tours.favorites.index') }}" method="get">
                                    <button type="submit"> <i class="bx bxs-heart"></i></button>
                                </form>
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
                    @if ($tour->city)
                        <div class="normal-card__location" data-tooltip="tooltip" title="{{ $tour->city->name }}">
                            <i class="bx bxs-paper-plane"></i>{{ $tour->city->name }}
                        </div>
                    @endif
                    <a href="{{ $detailUrl }}" class="tour-activity-card__details--title">
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
                                {{ $tour->formated_price_type ?? 'From ' . formatPrice($tour->sale_price) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @break

@endswitch
