@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
@endphp

@section('content')
    <div class="location-banner1">
        <div class="container-Fluid">
            <div class="location-banner1__img">
                <img data-src="{{ asset($item->banner_image ?? 'assets/images/placeholder.png') }}"
                    alt='{{ $item->banner_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
            </div>
        </div>
    </div>

    <div class="location1-content__section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="location1-content__section--details">
                        <div class="location1-content__section--heading">
                            <h2>
                                {{ $item->name }}
                            </h2>

                        </div>
                        <div class="location1-content__section--pra editor-content">
                            {!! $item->content !!}
                        </div>
                        <a class="location1-content__section--btn">
                            <button class="app-btn">
                                Best Things to Do
                                <i class='bx bx-right-arrow-alt'></i>
                            </button>

                        </a>

                    </div>


                </div>

                @php
                    $sectionContent = json_decode($item->section_content);
                    $guideContent = $sectionContent->guide ?? null;
                @endphp
                @if (isset($guideContent->is_enabled))
                    <div class="col-md-5">
                        <div class="loaction-guide"
                            style=" {{ $guideContent->background_color ? 'background: ' . $guideContent->background_color . ';' : '' }} ">
                            <div class="loaction-guide-content">
                                <div style="
                            {{ $guideContent->title_color ? 'color: ' . $guideContent->title_color . ';' : '' }} "
                                    class="loaction-guide-heading">
                                    {{ $guideContent->title }}
                                </div>
                                <div style=" {{ $guideContent->subtitle_color ? 'color: ' . $guideContent->subtitle_color . ';' : '' }} "
                                    class="loaction-guide-title">
                                    {{ $guideContent->subtitle }}
                                </div>
                                <div style=" {{ $guideContent->description_color ? 'color: ' . $guideContent->description_color . ';' : '' }} "
                                    class="loaction-guide-pra">
                                    {{ $guideContent->description }}
                                </div>
                                @if (isset($guideContent->is_button_enabled))
                                    <div class="loaction-guide-btn">
                                        <a style="
                                {{ $guideContent->btn_background_color ? 'background: ' . $guideContent->btn_background_color . ';' : '' }}
                                {{ $guideContent->btn_text_color ? 'color: ' . $guideContent->btn_text_color . ';' : '' }}
                            "
                                            href="{{ sanitizedLink($guideContent->btn_link) }}" class="themeBtn-round"
                                            target="_blank">
                                            {{ $guideContent->btn_text }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @php
        $bestTours = \App\Models\Tour::whereIn('id', json_decode($item->best_tours_ids ?? '[]'))
            ->where('status', 'publish')
            ->get();
        $popularTours = \App\Models\Tour::whereIn('id', json_decode($item->popular_tours_ids ?? '[]'))
            ->where('status', 'publish')
            ->get();
    @endphp

    @if ($bestTours->isNotEmpty())
        <div class="section-padding pt-4">
            <div class="container">
                <div class="top-picks-experts__heading">
                    <div class="section-content">
                        <h2 class="subHeading">
                            {{ $bestTours->count() }} best things to do in {{ $item->name }}
                        </h2>
                    </div>
                </div>
                <div class="row four-items-slider pt-3">
                    @foreach ($bestTours as $tour)
                        <div class="col">
                            <div class=card-content>
                                <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                        loading="lazy">
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
                                        <div class="tour-activity-card__details--title">{{ $tour->title }}</div>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                    <div class="baseline-pricing__value baseline-pricing__value--high">
                                        <p class=baseline-pricing__from>
                                            <span class="baseline-pricing__from--text receive">Receive voucher
                                                instantly</span>
                                        </p>
                                    </div>
                                    <div class="baseline-pricing__value baseline-pricing__value--high">
                                        <p class=baseline-pricing__from>
                                            <span class=baseline-pricing__from--text>From </span>
                                            <span class="baseline-pricing__from--value green">
                                                {{ formatPrice($tour->regular_price) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($popularTours->isNotEmpty())
        <div class="container">
            <div class="top-picks-experts__heading">
                <div class="section-content text-center">
                    <h2 class="subHeading">
                        Book popular activities in {{ $item->name }}
                    </h2>
                </div>
            </div>
            <div class="row three-items-slider pt-3">
                @foreach ($popularTours as $tour)
                    <div class="col">
                        <div class=card-content>
                            <a href=# class=card_img>
                                <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                    alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                    loading="lazy">
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
                                    <div><span> From {{ formatPrice($tour->regular_price) }}
                                        </span></div>
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
        </div>
    @endif

    @if ($relatedCities->isNotEmpty())
        <div class="location1-beyond section-padding ">
            <div class="container">
                <div class="latest-stories__details">
                    <div class="section-content">
                        <h2 class="subHeading">
                            {{ $item->name }} and beyond
                        </h2>
                    </div>
                </div>

                <div class="row pt-3">
                    @foreach ($relatedCities as $city)
                        <div class="col-md-3">
                            <div class="blog-more__dest-content">
                                <div class="location1-beyond__img">
                                    <a href="{{ route('city.details', $city->slug) }}">
                                        <img data-src="{{ asset($city->featured_image ?? 'assets/images/placeholder.png') }}"
                                            alt='{{ $city->featured_image_alt_text }}' class='imgFluid lazy'
                                            loading='lazy'>
                                    </a>
                                </div>
                                <div class="blog-more__dest-title">
                                    {{ $city->name }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
