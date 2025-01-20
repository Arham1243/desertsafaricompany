@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
@endphp

@section('content')
    <div class="header-form mb-5">
        <div class="container">
            <div class="row">
                <div class="for-generic ">
                    <form action="#" class="input-details generic-form">
                        <i class='bx bx-search'></i>
                        <input type="text" name="" placeholder="Search generic " class="mobile-number-app app-input">
                        <button class="app-btn themeBtn">SEND</button>
                    </form>
                </div>
            </div>


            <div class="header-form__banner mt-5">
                <div class="row">
                    <div class="col-md-8">
                        <div class="header-form__title header-banner__heading">

                            <h1 class="banner-heading banner-alt-heading">
                                {{ explode(' ', $item->name)[0] }}
                                <div class="bannerMain-title">{{ implode(' ', array_slice(explode(' ', $item->name), 1)) }}
                                </div>
                            </h1>
                            <div class="highlights-item__container">
                                <div class="highlights-item__icon">
                                    <i class='bx bxs-purchase-tag-alt'></i>
                                </div>
                                <div class="highlights-item__pra">
                                    <p>Booked 3,000+ times last week</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="header-form__img">
                            <img data-src={{ asset($item->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                alt="{{ $item->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($featuredTours->isNotEmpty())
        <div class="section-padding pb-4">
            <div class="container">
                <div class="activity-sorting-block">
                    <div class="search-header__activity">
                        <div class="activities-found">
                            {{ $totalActivities }} activities found
                            <div class="activities-found__icon">
                                <i class='bx bxs-error-circle'></i>
                            </div>

                        </div>

                        {{-- <div class="sort-by">
                            <div class="sort-by__title">
                                Sort by :
                            </div>
                            <label class="dropdown-label">
                                <select class="dropdown-select">
                                    <option value="recommended">Recommended</option>
                                </select>
                            </label>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    @foreach ($featuredTours as $tour)
                        <div class="col-md-3">
                            <div class=card-content>
                                <a href=# class=card_img>
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
                                            <div class=service-wishlist>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=tour-activity-card__details>
                                    <div class=vertical-activity-card__header>
                                        @if ($tour->category)
                                            <div><span> {{ $tour->category->name }}</span></div>
                                        @else
                                            <div><span> {{ $item->name }}</span></div>
                                        @endif
                                        <div data-tooltip="tooltip" title="{{ $tour->title }}"
                                            class="tour-activity-card__details--title text-truncate">{{ $tour->title }}
                                        </div>
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


    @php
        $sectionContent = json_decode($item->section_content);
        $tourCountContent = $sectionContent->tour_count ?? null;
        $callToActionContent = $sectionContent->call_to_action ?? null;
    @endphp

    @if (isset($callToActionContent->is_enabled))
        @php
            $isCtaBackgroundColor = isset($callToActionContent->call_to_action_background_type)
                ? $callToActionContent->call_to_action_background_type === 'background_color'
                : null;
            $isCtaBackgroundImage = isset($callToActionContent->call_to_action_background_type)
                ? $callToActionContent->call_to_action_background_type === 'background_image'
                : null;
        @endphp
        <div class="offers-section section-padding">
            <div class=container>
                <div class=offers-section__details
                    style="{{ $isCtaBackgroundColor && $callToActionContent->background_color ? 'background-color: ' . $callToActionContent->background_color : '' }}">
                    @if ($isCtaBackgroundImage)
                        <img data-src="{{ asset($callToActionContent->background_image ?? 'admin/assets/images/placeholder.png') }}"
                            alt="{{ $callToActionContent->background_image_alt_text ?? 'Cta Background Image' }}"
                            class="imgFluid lazy offers-section__img" loading="lazy" height="200">
                    @endif
                    <div class=GroupTourCard_content>
                        <span class=GroupTourCard_title
                            @if ($callToActionContent->title_color) style="color: {{ $callToActionContent->title_color }};" @endif>{{ $callToActionContent->title ?? '' }}</span>
                        <span class=GroupTourCard_subtitle
                            @if ($callToActionContent->description_color) style="color: {{ $callToActionContent->description_color }};" @endif>{{ $callToActionContent->description ?? '' }}</span>
                        @if (isset($callToActionContent->is_button_enabled))
                            <div class="GroupTourCard_callBackButton pt-3">
                                <a href="{{ sanitizedLink($callToActionContent->btn_link) ?? '' }}"
                                    style="
{{ $callToActionContent->btn_background_color ? 'background-color: ' . $callToActionContent->btn_background_color . ';' : '' }}
{{ $callToActionContent->btn_text_color ? 'color: ' . $callToActionContent->btn_text_color . ';' : '' }}
"
                                    target="_blank"
                                    class="GroupTourCard_text app-btn themeBtn">{{ $callToActionContent->btn_text ?? '' }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($bottomFeaturedTours->isNotEmpty())
        <div class="section-padding pt-4">
            <div class="container">
                <div class="row">
                    @foreach ($bottomFeaturedTours as $tour)
                        <div class="col-md-3">
                            <div class=card-content>
                                <a href=# class=card_img>
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
                                            <div class=service-wishlist>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=tour-activity-card__details>
                                    <div class=vertical-activity-card__header>
                                        @if ($tour->category)
                                            <div><span> {{ $tour->category->name }}</span></div>
                                        @else
                                            <div><span> {{ $item->name }}</span></div>
                                        @endif
                                        <div data-tooltip="tooltip" title="{{ $tour->title }}"
                                            class="tour-activity-card__details--title text-truncate">{{ $tour->title }}
                                        </div>
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
                @if ($bottomFeaturedTours->count() > 8)
                    <div class="show-more pt-3">
                        <button type="submit" class="app-btn themeBtn"> See more</button>
                    </div>
                @endif
            </div>
        </div>
    @endif


    @if (isset($tourCountContent->is_enabled))
        @php
            $isCountBackgroundColor = isset($tourCountContent->tour_count_background_type)
                ? $tourCountContent->tour_count_background_type === 'background_color'
                : null;
            $isCountBackgroundImage = isset($tourCountContent->tour_count_background_type)
                ? $tourCountContent->tour_count_background_type === 'background_image'
                : null;
        @endphp
        <div class="location-banner mb-3">
            <div class="container">
                <div class="location-banner__content"
                    style="{{ $isCountBackgroundColor && $tourCountContent->background_color ? 'background-color: ' . $tourCountContent->background_color : '' }}">
                    @if ($isCountBackgroundImage)
                        <div class="location-banner__img">
                            <img data-src="{{ asset($tourCountContent->background_image ?? 'admin/assets/images/placeholder.png') }}"
                                alt="{{ $tourCountContent->background_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                loading="lazy">
                        </div>
                    @endif
                    <div class="location-banner-wrapper">
                        <div class="banner-heading">
                            <h1>
                                <div class="bannerMain-title"
                                    @if ($tourCountContent->heading_color) style="color: {{ $tourCountContent->heading_color }} !important;" @endif>
                                    {{ $tourCountContent->heading ?? '' }}</div>
                            </h1>
                        </div>
                        @if (isset($tourCountContent->is_button_enabled))
                            <a href="{{ sanitizedLink($tourCountContent->btn_link ?? 'javascript:void(0)') }}"
                                style="
    {{ $tourCountContent->btn_background_color ? 'background-color: ' . $tourCountContent->btn_background_color . ';' : '' }}
    {{ $tourCountContent->btn_text_color ? 'color: ' . $tourCountContent->btn_text_color . ';' : '' }}"
                                class="app-btn
                                themeBtn"
                                type="button">{{ $tourCountContent->btn_text ?? 'Click' }} </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($recomTours->isNotEmpty())
        <div class="py-5">
            <div class="container">
                <div class="section-content">
                    <h2 class="subHeading">
                        Our most recommended {{ $item->name }}
                    </h2>
                </div>

                <div class="row">
                    @foreach ($recomTours as $tour)
                        <div class="col-md-6 mt-4">
                            <div class="highlight">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="#" class="highlight__image" target="_blank">
                                            <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                                alt="{{ $tour->featured_image_alt_text ?? 'image' }}"
                                                class="imgFluid lazy" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="highlight__text-content">
                                            <div class="highlight__text">
                                                <a href="dubai-l173/burj-khalifa-ticket-t49019/"
                                                    class="highlight__text-link" target="_blank">
                                                    <p class="highlight__title">{{ $tour->title }}</p>
                                                </a>
                                                <div class="highlight__description editor-content">
                                                    {!! $tour->content !!}
                                                </div>
                                            </div>
                                            <div class="highlight__button-wrapper">
                                                <a class="" type="button">
                                                    See more
                                                </a>
                                            </div>
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

    @if ($featuredReviews->isNotEmpty())
        <div class="comment">
            <img src="{{ asset('frontend/assets/images/comment.webp') }}" alt="image" class="peoples-img imgFluid"
                loading="lazy">
            <div class="ocizgi_imgs">
                <img src="{{ asset('frontend/assets/images/ocizgi.webp') }}" alt="image" class="ocizgi imgFluid"
                    loading="lazy">
            </div>
            <div class="container">
                <div class="section-content">
                    <h2 class="subHeading">
                        Comment
                    </h2>
                    <p>What are our customers saying?</p>
                </div>

                <div class="row pt-3">
                    @foreach ($featuredReviews as $testimonial)
                        <div class=col-md-3>
                            <div class=comment-card>
                                <div class="comment-card__img one-items-slider">
                                    <img data-src="{{ asset($testimonial->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                        alt="{{ $testimonial->featured_image_alt_text }}" class="imgFluid lazy"
                                        loading="lazy">
                                    @if ($testimonial->media->isNotEmpty())
                                        @foreach ($testimonial->media as $media)
                                            <img data-src="{{ asset($media->file_path ?? 'admin/assets/images/placeholder.png') }}"
                                                alt="{{ $media->alt_text }}" class="imgFluid lazy" loading="lazy">
                                        @endforeach
                                    @endif
                                </div>
                                <div class=comment-card__content>
                                    <div class=comment-details>
                                        <div class="customer-name" title="{{ $testimonial->title ?? '' }}"
                                            @if (strlen($testimonial->title ?? '') > 19) data-tooltip="tooltip" @endif>
                                            {{ $testimonial->title ?? '' }}
                                        </div>
                                        <div class=card-rating>
                                            <x-star-rating :rating="$testimonial->rating" />
                                        </div>
                                    </div>
                                    <div class=comment-pra>
                                        {!! $testimonial->content ?? '' !!}
                                    </div>
                                    @if (isset($content->is_button_enabled))
                                        <a style="
                                        @if ($content->btn_background_color) background: {{ $content->btn_background_color }}; @else background: var(--color-primary); @endif
                                        @if ($content->btn_text_color) color: {{ $content->btn_text_color }}; @else color: #fff; @endif
                                    "
                                            href="javascript:void(0)" class="app-btn themeBtn">
                                            {{ $content->btn_text ?? 'Read' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="newsletter pt-3 pb-5 mb-2">
        <div class=container>
            <div class="row g-0">
                <div class=col-md-6>
                    <div class=newsletter__img>
                        <img src="{{ asset('frontend/assets/images/173.webp') }}" alt="image" class="imgFluid"
                            loading="lazy">
                    </div>
                </div>
                <div class=col-md-6>
                    <div class=newsletter__content>
                        <div class=section-content>
                            <h2 class=subHeading>
                                Your Dubai itinerary is waiting.
                            </h2>
                        </div>
                        <p>Receive a curated 48-hour itinerary featuring the most iconic experiences in Dubai, straight to
                            your inbox.

                        </p>
                        <form class=line-form method="POST" action="#">
                            <div class=line-form__input>
                                <input id=email type=email name=email placeholder="Email" required>
                                <i class="bx bx-envelope"></i>
                            </div>
                            <button type=submit class="primary-btn">Sign up</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=privacy-content>
                <p class="mb-0">By signing up, you agree to receive promotional emails on activities and insider tips.
                    You
                    can
                    unsubscribe or withdraw your consent at any time with future effect. For more information, read our
                    Privacy statement</p>
            </div>
        </div>
    </div>
@endsection
