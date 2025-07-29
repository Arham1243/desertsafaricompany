@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
@endphp

@section('content')
    <div class="header-form mb-5">
        <div class="container">
            {{-- <div class="row">
                <div class="for-generic ">
                    <form action="#" class="input-details generic-form">
                        <i class='bx bx-search'></i>
                        <input type="text" name="" placeholder="Search generic " class="mobile-number-app app-input">
                        <button class="app-btn themeBtn">SEND</button>
                    </form>
                </div>
            </div> --}}


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
                                    @php
                                        $count = 250 + ($thisWeekViews ?? 0);
                                    @endphp

                                    <p>Booked {{ number_format($count) }}+ times last week</p>
                                </div>

                            </div>
                        </div>
                    </div>
                    @if ($item->media->isNotEmpty())
                        <div class="col-md-4">
                            <div class="header-form__img one-item-fade-slider">
                                @foreach ($item->media as $itemMedia)
                                    <img data-src={{ asset($itemMedia->file_path ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $itemMedia->alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($item->long_description)
        <div class="my-5">
            <div class=container>
                <div class="tour-content__details " data-show-more>
                    <div class="editor-content line-clamp" data-show-more-content
                        @if ($item->long_description_line_limit > 0) style="
            -webkit-line-clamp: {{ $item->long_description_line_limit }};
            " @endif>
                        {!! $item->long_description !!}
                    </div>
                    @if ($item->long_description_line_limit > 0)
                        <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn more-text="Read more"
                            less-text='Read less'> Read more</a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @php
        $jsonContent = json_decode($item->json_content, true) ?? null;
        $category_based_tour_block = $jsonContent ? $jsonContent['category_based_tour_block'] : null;
        $category_based_tour_category_id = $jsonContent ? $category_based_tour_block['category_id'] : null;
        $all_sub_category_Ids = getAllCategoryIds($category_based_tour_category_id);
        $category_based_tour_tours = $tours->whereIn('category_id', $all_sub_category_Ids);

        $first_tour_block = $jsonContent ? $jsonContent['first_tour_block'] : null;
        $first_tour_block_tour_ids = $first_tour_block['tour_ids'] ?? [];
        $first_tour_block_tours = $tours->whereIn('id', $first_tour_block_tour_ids);

        $second_tour_block = $jsonContent ? $jsonContent['second_tour_block'] : null;
        $second_tour_block_tour_ids = $second_tour_block['tour_ids'] ?? [];
        $second_tour_block_tours = $tours->whereIn('id', $second_tour_block_tour_ids);
    @endphp

    @if (isset($category_based_tour_block['is_enabled']) &&
            $category_based_tour_block['is_enabled'] === '1' &&
            $category_based_tour_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-7">
                        <div class="section-content">
                            <h2 class="subHeading">
                                {{ $category_based_tour_block['heading'] ?? '' }}
                            </h2>
                        </div>
                        <div class="activity-sorting-block mt-2">
                            <div class="search-header__activity">
                                <div class="activities-found">
                                    {{ $category_based_tour_tours->count() }} activities found
                                    <div class="activities-found__icon">
                                        <i class='bx bxs-error-circle'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($category_based_tour_tours as $category_based_tour_tour)
                        <div class="col-md-3">
                            <x-tour-card :tour="$category_based_tour_tour" style="style3" />
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

    @if (isset($callToActionContent->is_enabled) && $callToActionContent->is_enabled === '1')
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
                        @if (isset($callToActionContent->is_button_enabled) && $callToActionContent->is_button_enabled === '1')
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


    @if (isset($first_tour_block['is_enabled']) &&
            $first_tour_block['is_enabled'] === '1' &&
            $first_tour_block_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-7">
                        <div class="section-content">
                            <h2 class="subHeading">
                                {{ $first_tour_block['heading'] ?? '' }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($first_tour_block_tours as $first_tour_block_tour)
                        <div class="col-md-3">
                            <x-tour-card :tour="$first_tour_block_tour" style="style3" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (isset($tourCountContent->is_enabled) && $tourCountContent->is_enabled === '1')
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

                        @if (isset($tourCountContent->is_button_enabled) && $tourCountContent->is_button_enabled === '1')
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


    @if (isset($second_tour_block['is_enabled']) &&
            $second_tour_block['is_enabled'] === '1' &&
            $second_tour_block_tours->isNotEmpty())
        <div class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="section-content">
                            <h2 class="subHeading">
                                {{ $second_tour_block['heading'] ?? '' }}
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($second_tour_block_tours as $second_tour_block_tour)
                        <div class="col-md-6 mt-4">
                            <div class="highlight">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('tours.details', $second_tour_block_tour->slug) }}"
                                            class="highlight__image" target="_blank">
                                            <img data-src={{ asset($second_tour_block_tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                                alt="{{ $second_tour_block_tour->featured_image_alt_text ?? 'image' }}"
                                                class="imgFluid lazy" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="highlight__text-content">
                                            <div class="highlight__text">
                                                <a href="dubai-l173/burj-khalifa-ticket-t49019/"
                                                    class="highlight__text-link" target="_blank">
                                                    <p class="highlight__title">{{ $second_tour_block_tour->title }}</p>
                                                </a>
                                                <div class="highlight__description editor-content">
                                                    {!! $second_tour_block_tour->content !!}
                                                </div>
                                            </div>
                                            <div class="highlight__button-wrapper">
                                                <a href="{{ route('tours.details', $second_tour_block_tour->slug) }}">
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
