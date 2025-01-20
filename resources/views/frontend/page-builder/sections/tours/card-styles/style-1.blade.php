<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'five-items-slider' : 'row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-5' }}">
    @foreach ($tours as $tour)
        <div class="col">
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
        </div>
    @endforeach
</div>
