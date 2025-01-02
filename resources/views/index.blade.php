@extends('layouts.main')
@section('content')
    <div class=banner>
        <div class=container>
            <div class=row>
                <div class=col-md-6>
                    <div class=banner-content>
                        <div class=banner-heading>
                            <h1>
                                Discover Your
                                <div class=bannerMain-title>Next Adventure</div>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class=col-md-6>
                    <div class=banner-img>
                        <img data-src="{{ asset('frontend/assets/images/baloon.webp') }}" alt=image class="imgFluid lazy"
                            width="345.89" height="186">
                    </div>
                </div>
                <form action="" class=banner-form>
                    <div class=search>
                        <input name=location placeholder="Where are you going?">
                        <i class="bx bx-search"></i>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class=destinations>
        <div class=container>
            <div class="row justify-content-between">
                <div class=col-md-4>
                    <div class=destinations-content>
                        <div class=dst1>TOP DESTINATIONS</div>
                        <div class=dst2>
                            Meet With Carefully Selected Destinations!
                            <div class=darrow>
                                <img data-src="{{ asset('frontend/assets/images/darrow.webp') }}" alt=image
                                    class="imgFluid lazy" width=100 height=20.36>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=col-md-8>
                    <div class="row destinations-slider">
                        @foreach ($cities as $city)
                            <div class=col-md>
                                <a href="{{ route('city.details', $city->slug) }}" class=dst-card>
                                    <div class=destinations-img>

                                        <img data-src="{{ asset($city->img_path ?? 'assets/images/placeholder.png') }}"
                                            alt={{ $city->name }} class="imgFluid lazy">
                                    </div>
                                    <div class=dst-location>
                                        {{ $city->name }}
                                    </div>
                                    @if ($city->tours->count() > 0)
                                        <div class=dst-num>{{ $city->tours->count() }}</div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!$tours->isEmpty())
        <div class=tours>
            <div class=container>
                <div class=tours-content>
                    <div class=section-content>
                        <div class=heading>Top Tours</div>
                    </div>
                    <div class=more-link>
                        <a href={{ route('tours.index') }}>More<i class="bx bx-right-arrow-alt"></i></a>
                    </div>
                </div>
                <div class="row pt-3">
                    @foreach ($tours->slice(0, 5) as $i => $tour)
                        <div class=col-md>
                            <div class=card-content>
                                <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                                    <img data-src="{{ asset($tour->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt='{{ $tour->title }}' class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>
                                                    @if ($tour->price_type == 'per_person')
                                                        {{ $tour->for_adult_price }}
                                                    @elseif($tour->price_type == 'per_car')
                                                        {{ $tour->for_car_price }}
                                                    @endif
                                                </b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href={{ route('tours.details', $tour->slug) }}
                                        class=card-title>{{ $tour->title }}</a>
                                    <div class=location-details><i
                                            class="bx bx-location-plus"></i>{{ $tour->cities[0]->name }}<span> $ -
                                            $$</span>
                                    </div>
                                    <div class="card-rating">
                                        <x-star-rating :rating="$tour->average_rating" />
                                        <span class="average-rating">
                                            {{ $tour->average_rating }}
                                        </span>
                                        <span class="total">
                                            @if ($tour->reviews->count() > 0)
                                                ({{ $tour->reviews->count() }}
                                                Review{{ $tour->reviews->count() > 1 ? 's' : '' }})
                                            @else
                                                (No Reviews Yet)
                                            @endif
                                        </span>
                                    </div>


                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <x-section :section="$sections->where('section_name', 'offers')->first()" />

    @if (!$tours->isEmpty())
        <div class="tour-activity__cards2">
            <div class="container">
                <div class="tours-content">
                    <div class="section-content">
                        <div class="heading">Top Tours</div>
                    </div>
                    <div class="more-link">
                        <a href="{{ route('tours.index') }}">More<i class="bx bx-right-arrow-alt"></i></a>
                    </div>
                </div>
                <div class="row pt-3">
                    @foreach ($tours->slice(0, 3) as $tour)
                        <div class="col-md-4">
                            <div class="card-content">
                                <a href="{{ route('tours.details', $tour->slug) }}" class="card_img">
                                    <img data-src="{{ asset($tour->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt="{{ $tour->title }}" class="imgFluid lazy" loading="lazy">
                                    <div class="price-details">
                                        <div class="price">
                                            <span>
                                                <b>
                                                    @if ($tour->price_type == 'per_person')
                                                        {{ $tour->for_adult_price }}
                                                    @elseif($tour->price_type == 'per_car')
                                                        {{ $tour->for_car_price }}
                                                    @endif
                                                </b>
                                                From
                                            </span>
                                        </div>
                                        <div class="heart-icon">
                                            <div class="service-wishlis">
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="tour-activity-card__details">
                                    <div class="vertical-activity-card__header">
                                        <div><span> From
                                                {{ $tour->price_type == 'per_person' ? $tour->for_adult_price : $tour->for_car_price }}</span>
                                        </div>
                                        <div class="tour-activity-card__details--title">
                                            {{ $tour->title }}
                                        </div>
                                    </div>
                                    <div class="tour-activity__RL">
                                        <div class="card-rating">
                                            <x-star-rating :rating="$tour->average_rating" />
                                            <span class="average-rating">
                                                {{ $tour->average_rating }}
                                            </span>
                                            <span class="total">
                                                @if ($tour->reviews->count() > 0)
                                                    ({{ $tour->reviews->count() }}
                                                    Review{{ $tour->reviews->count() > 1 ? 's' : '' }})
                                                @else
                                                    (No Reviews Yet)
                                                @endif
                                            </span>
                                        </div>
                                        <div class="card-location">
                                            <i class="bx bx-location-plus"></i>
                                            {{ $tour->cities->first()->name ?? 'Location Not Available' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (!$tours->isEmpty())
        <div class=tour-activity__cards>
            <div class=container>
                <div class=tours-content>
                    <div class=section-content>
                        <div class=heading>Top Tours</div>
                    </div>
                    <div class=more-link>
                        <a href={{ route('tours.index') }}>More<i class="bx bx-right-arrow-alt"></i></a>
                    </div>
                </div>
                <div class="row pt-3">
                    @foreach ($tours->slice(0, 4) as $i => $tour)
                        <div class="col-md-3">
                            <div class="card-content">
                                <a href="{{ route('tours.details', $tour->slug) }}" class="card_img">
                                    <img src="{{ asset($tour->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt='image' class='imgFluid' loading='lazy'>
                                    <div class="price-details">
                                        <div class="price">
                                            <span>
                                                Top pick
                                            </span>
                                        </div>
                                        <div class="heart-icon">
                                            <div class="service-wishlis">
                                                <i class='bx bx-heart'></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="tour-activity-card__details">
                                    <div class="vertical-activity-card__header">
                                        @if ($tour->categories && !$tour->categories->isEmpty())
                                            @php
                                                $categories = '';
                                                foreach ($tour->categories as $category) {
                                                    $categories .= $category->name . '<br>';
                                                }
                                            @endphp
                                            <div class="tour-categories"><span>{{ $tour->categories[0]->name }}</span>
                                                <button type="button" data-tooltip="tooltip" data-bs-placement="top"
                                                    title="{!! $categories !!}">
                                                    <i class='bx bxs-info-circle'></i>
                                                </button>
                                            </div>
                                        @endif
                                        <a href="{{ route('tours.details', $tour->slug) }}"
                                            class="tour-activity-card__details--title">{{ $tour->title }}</a>
                                    </div>



                                    <div class="card-rating">
                                        <x-star-rating :rating="$tour->average_rating" />
                                        @if ($tour->reviews && $tour->reviews->count() > 0)
                                            <span>{{ $tour->reviews->count() }}
                                                Review{{ $tour->reviews->count() > 1 ? 's' : '' }}</span>
                                        @else
                                            <span>No Reviews Yet</span>
                                        @endif
                                    </div>


                                    <!--<div class="baseline-pricing__value baseline-pricing__value--high">-->
                                    <!--    <p class="baseline-pricing__from">-->
                                    <!--        <span class="baseline-pricing__from--text">From</span>-->
                                    <!--        <span class="baseline-pricing__from--value">$50.77</span>-->
                                    <!--    </p>-->
                                    <!--</div>-->
                                    @if ($tour->price_type == 'per_person')
                                        <div class="baseline-pricing__value baseline-pricing__value--low">
                                            <p class="baseline-pricing__from">From</p>
                                            <p class="baseline-pricing__from">{{ $tour->for_adult_price }}</p>

                                            <p class="baseline-pricing__category">Per Person</p>
                                        </div>
                                    @elseif($tour->price_type == 'per_car')
                                        <div class="baseline-pricing__value baseline-pricing__value--low">
                                            <p class="baseline-pricing__from">From</p>
                                            <p class="baseline-pricing__from">{{ $tour->for_car_price }}</p>

                                            <p class="baseline-pricing__category">Per Car</p>
                                        </div>
                                    @endif

                                </div>



                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (!$promotions->isEmpty())
        <div class=event-promotions>
            <div class=container>
                <div class=section-content>
                    <h2 class=heading>
                        Latest travel promotions
                    </h2>
                </div>
                <div class="row pt-3 promotions-slider">
                    @foreach ($promotions as $promotion)
                        <div class=col-md-4>
                            <a href="{{ $promotion->link }}" target="_blank" class=card-event-item>
                                <div class=card-event-item__img>
                                    <img data-src="{{ asset($promotion->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt={{ $promotion->title }} class="imgFluid lazy" loading="lazy">
                                </div>
                                <div class=event-detail>
                                    <p>
                                        {{ $promotion->title }}
                                    </p>
                                    <span>Learn more<i class="bx bx-chevron-right"></i></span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (!$tours->isEmpty())
        <div class=top10-trending-products>
            <div class=container>
                <div class=section-content>
                    <h2 class=heading>
                        Top 10 Trending Products
                    </h2>
                </div>
                <div class="row pt-2 trending-products-slider">
                    @foreach ($tours as $tour)
                        <div class=col-md-3>
                            <div class="card-content trending-products__content">
                                <a href=# class="card_img trending-products__img">
                                    <img data-src="{{ asset($tour->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt=image class="imgFluid lazy" loading="lazy">
                                    <div class=product-rank>
                                        TOP 1
                                    </div>
                                    <div class=price-details>
                                        <div class=price-location>
                                            <i class="bx bxs-location-plus"></i>
                                            {{ $tour->cities[0]->name }}
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
                                        <div class="tour-activity-card__details--title">
                                            {{ $tour->title }}
                                        </div>
                                        <div class=product-card__tag><span title="Receive voucher instantly"
                                                class=tag>Receive
                                                voucher instantly</span></div>
                                    </div>
                                    <div class=tour-activity__RL>
                                        <div class=card-rating>
                                            <i class="bx bxs-star green-star"></i>

                                            @if ($tour->reviews && $tour->reviews->count() > 0)
                                                <span>
                                                    {{ $tour->average_rating }}

                                                    ({{ $tour->reviews->count() }}
                                                    Review{{ $tour->reviews->count() > 1 ? 's' : '' }})
                                                </span>
                                            @else
                                                <span>(No Reviews Yet)</span>
                                            @endif
                                            <span>|</span>
                                            <span> 200K+ booked</span>
                                        </div>
                                    </div>
                                    <div class=top10-trending-products__price>
                                        From
                                        <span>
                                            @if ($tour->price_type == 'per_person')
                                                {{ $tour->for_adult_price }}
                                            @elseif($tour->price_type == 'per_car')
                                                {{ $tour->for_car_price }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <x-section :section="$sections->where('section_name', 'activities')->first()" />

    @if (!$waterActivityTours->isEmpty())
        <div class="water-list-tour normal-card">
            <div class=container>
                <div class=section-content>
                    <h2 class=heading>
                        Top Water Activities
                    </h2>
                </div>
                <div class="row pt-3">
                    @foreach ($waterActivityTours->slice(0, 4) as $tour)
                        <div class=col-md-3>
                            <div class="card-content normal-card__content">
                                <a href={{ route('tours.details', $tour->slug) }} class="card_img normal-card__img">
                                    <img data-src="{{ asset($tour->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt='{{ $tour->title }}' class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                        <div class=sale_info>
                                            38%
                                        </div>
                                    </div>
                                </a>
                                <div class="tour-activity-card__details normal-card__details">
                                    <div class=vertical-activity-card__header>
                                        <div class=normal-card__location>
                                            <i class="bx bxs-paper-plane"></i>
                                            {{ $tour->cities[0]->name }}
                                        </div>
                                        <div class="tour-activity-card__details--title"> {{ $tour->title }}</div>
                                    </div>
                                    <div class="tour-listing__info normal-card__info">
                                        <div class=duration>
                                            @if (!$tour->tour_attributes->isEmpty())
                                                <i class="{{ $tour->tour_attributes[0]->icon_class }}"></i>
                                                {{ $tour->tour_attributes[0]->title }}
                                            @endif
                                        </div>


                                        <div class="baseline-pricing__value baseline-pricing__value--high">
                                            @if ($tour->price_type == 'per_person')
                                                <p class=baseline-pricing__from>
                                                    <span class=baseline-pricing__from--value>{{ $tour->for_adult_price }}
                                                        per person</span>
                                                </p>
                                            @elseif($tour->price_type == 'per_car')
                                                <p class=baseline-pricing__from>
                                                    <span class=baseline-pricing__from--value>
                                                        {{ $tour->for_car_price }}
                                                        per car</span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    {{--
    <div class=local-guide>
        <div class=container>
            <div class="section-content text-center">
                <h2 class=subHeading>
                    Local Guide
                </h2>
                <p>Explore Uncharted Places
                </p>
            </div>
            <nav>
                <div class="nav local-guide__btns" id=nav-tab role=tablist>
                    <button class="btns active" id=nav-home-tab data-bs-toggle=tab data-bs-target=#nav-home type=button
                        role=tab aria-controls=nav-home aria-selected=true>Restaurant</button>
                    <button class=btns id=nav-profile-tab data-bs-toggle=tab data-bs-target=#nav-profile type=button
                        role=tab aria-controls=nav-profile aria-selected=false>Tattoos</button>
                    <button class=btns id=nav-contact-tab data-bs-toggle=tab data-bs-target=#nav-contact type=button
                        role=tab aria-controls=nav-contact aria-selected=false>Bar</button>
                </div>
            </nav>
            <div class=tab-content id=nav-tabContent>
                <div class="tab-pane fade show active" id=nav-home role=tabpanel aria-labelledby=nav-home-tab>
                    <div class="row pt-3">
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/oscar-restaurant-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/little-mermaid-600.webp')}}" alt=image class="imgFluid lazy"
                                        loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/elite-restaurant-alanya-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/photo-2023-04-18-12-10-20-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id=nav-profile role=tabpanel aria-labelledby=nav-profile-tab>
                    <div class="row pt-3">
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id=nav-contact role=tabpanel aria-labelledby=nav-contact-tab>
                    <div class="row pt-3">
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src="{{ asset('frontend/assets/images/mezze-grill-restaurant-ocakbasi-600.webp')}}" alt=image
                                        class="imgFluid lazy" loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>Mezze Grill Restaurant</a>
                                    <div class=location-details><i class="bx bx-location-plus"></i>Alanya<span> $ -
                                            $$</span>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="more-link text-center pt-5">
                <a href=#>View All <i class="bx bx-right-arrow-alt"></i></a>
            </div>
        </div>
    </div>
    --}}




    <x-section :section="$sections->where('section_name', 'sale')->first()" />

    @if (!$stories->isEmpty())
        <div class=latest-stories>
            <div class=container>
                <div class=latest-stories__title>TRAVEL STORIES AND NEWS</div>
                <div class=latest-stories__details>
                    <div class=section-content>
                        <h2 class=subHeading>
                            Explore our latest stories
                        </h2>
                    </div>
                    <div class=latest-stories__btns>
                        <button class=themeBtn-round>Read more news</button>
                        <button class=themeBtn-round>Read more articles</button>
                    </div>
                </div>
                <div class="row pt-3">
                    @if ($stories->count() > 0)
                        @php
                            // Get the first story for the main section
                            $mainStory = $stories->shift();
                        @endphp

                        <div class="col-md-7">
                            <div class="Desti-Pract__details">
                                <div class="Desti-Pract__img">
                                    <img data-src="{{ asset($mainStory->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt="{{ $mainStory->title }}" class="imgFluid lazy" loading="lazy">
                                </div>
                                <div class="Desti-Pract__content">
                                    <div class="sub-title">{{ $mainStory->city->name ?? '' }}</div>
                                    <a href="{{ route('stories.details', $mainStory->slug) }}"
                                        class="Desti-Pract__title">{{ $mainStory->title }}</a>
                                    <div class="date">{{ $mainStory->created_at->format('M d, Y') }} •
                                        {{ $mainStory->estimated_read_time }} min read</div>
                                    <p>{{ $mainStory->short_desc }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            @if ($stories->count() > 0)
                                @foreach ($stories as $story)
                                    <div class="Desti-Pract__activities">
                                        <div class="activities-details">
                                            <a href="{{ route('stories.details', $story->slug) }}"
                                                class="activities-img">
                                                <img data-src="{{ asset($story->img_path ?? 'assets/images/placeholder.png') }}"
                                                    alt="{{ $story->title }}" class="imgFluid lazy" loading="lazy">
                                            </a>
                                            <div class="activities-content">
                                                <p><b>{{ $story->city->name ?? '' }}</b></p>
                                                <a
                                                    href="{{ route('stories.details', $story->slug) }}">{{ $story->title }}</a>
                                                <p>{{ $story->created_at->format('M d, Y') }} •
                                                    {{ $story->estimated_read_time }} min read</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center">No additional stories added yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="col-12">
                            <p class="text-center">No stories found.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif

    @if (!$testimonials->isEmpty())
        <div class=comment>
            <img data-src="{{ asset('frontend/assets/images/comment.webp') }}" alt=image
                class="peoples-img lazy imgFluid" loading="lazy">
            <div class=ocizgi_imgs>
                <img data-src="{{ asset('frontend/assets/images/ocizgi.webp') }}" alt=image class="ocizgi imgFluid lazy"
                    loading="lazy">
            </div>
            <div class=container>
                <div class=section-content>
                    <h2 class=subHeading>
                        Comment
                    </h2>
                    <p>What are our customers saying?</p>
                </div>
                <div class="row pt-3">
                    @foreach ($testimonials as $testimonial)
                        <div class=col-md-3>
                            <div class=comment-card>
                                <div class="comment-card__img comment-slider">

                                    <img data-src="{{ asset($testimonial->main_img_path ?? 'assets/images/placeholder.png') }}"
                                        alt={{ $testimonial->title }} class="imgFluid lazy" loading="lazy">

                                    @if (!$testimonial->images->isEmpty())
                                        @foreach ($testimonial->images as $image)
                                            <img data-src="{{ asset($image->img_path ?? 'assets/images/placeholder.png') }}"
                                                alt={{ $testimonial->title }} class="imgFluid lazy" loading="lazy">
                                        @endforeach
                                    @endif
                                </div>
                                <div class=comment-card__content>
                                    <div class=comment-details>
                                        <div class=customer-name>
                                            {{ $testimonial->title }}
                                        </div>
                                        <div class=card-rating>

                                            @for ($i = 0; $i < 5; $i++)
                                                <i
                                                    class="bx bxs-star {{ $testimonial->rating > $i ? 'yellow-star' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class=comment-pra>
                                        {{ $testimonial->content }}
                                    </div>
                                    <button class="app-btn themeBtn">Read</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <x-section :section="$sections->where('section_name', 'newsletter')->first()" />


    <x-section :section="$sections->where('section_name', 'download_app')->first()" />

    <div class=top-location>
        <div class=container>
            <div class=section-content>
                <h2 class=heading>Top Location</h2>
            </div>
            <div class="top-location__list pt-4">
                <ul>
                    @foreach ($cities as $city)
                        <li><a href=#>{{ $city->name }}
                                <span>{{ $city->tours->count() }}</span>
                            </a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @if (!$countries->isEmpty())
        <div class=popular-related-destinations>
            <div class=container>
                <div class=section-content>
                    <h2 class=heading>Popular Related Destinations</h2>
                </div>
                <div class="row mt-4">
                    @foreach ($countries->slice(0, 4) as $country)
                        <div class=col-md-3>
                            <a href="{{ route('country.details', $country->slug) }}"
                                class=popular-related-destinations__content>
                                <div class=popular-related-destinations__img>
                                    <img data-src="{{ asset($country->img_path ?? 'assets/images/placeholder.png') }}"
                                        alt={{ $country->name }} class="imgFluid lazy" loading="lazy">
                                </div>
                                <div class=popular-related-destinations__title>
                                    <div class="title">{{ $country->name }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class=tripviz-contact>
        <div class=container>
            <div class=tripviz-contact__main>
                <div class=tripviz-contact__details>
                    <div class=fi1>Tripviz Contact<div class=Questions>Got Questions ?<b> Call us 24/7</b></div>
                    </div>
                    <div class=fi2> <i class="bx bx-phone"></i> {{ $config['COMPANYPHONE'] ?? '' }}
                    </div>
                    <div class=fi2> <i class="bx bx-envelope"></i> {{ $config['COMPANYEMAIL'] ?? '' }}
                    </div>
                    <div class=fi4>
                        <div class=fi4-1>Social Media<div class=temiz></div><span class=thin>Follow Me <img alt=""
                                    class=darrow2 data-src="{{ asset('frontend/assets/images/darrow.webp') }}" width=37.19
                                    height=7.56></span></div>
                        <div class=fi4-2>
                            <ul>
                                <li><a target=_blank aria-label="facebook" href="{{ $config['FACEBOOK'] ?? '' }}"><i
                                            class="bx bxl-facebook-circle"></i></a></li>
                                <li><a target=_blank aria-label="instagram" href="{{ $config['INSTAGRAM'] ?? '' }}"><i
                                            class="bx bxl-instagram"></i></a>
                                </li>
                                <li><a target=_blank aria-label="twitter" href="{{ $config['TWITTER'] ?? '' }}"><i
                                            class="bx bxl-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
