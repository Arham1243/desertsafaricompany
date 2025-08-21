@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
    $jsonContent = json_decode($item->json_content, true) ?? null;
    $bannerTitle =
        isset($jsonContent['h1_banner_text']['title']) && $jsonContent['h1_banner_text']['title']
            ? $jsonContent['h1_banner_text']['title']
            : null;
    $bannerSubtitle =
        isset($jsonContent['h1_banner_text']['subtitle']) && $jsonContent['h1_banner_text']['subtitle']
            ? $jsonContent['h1_banner_text']['subtitle']
            : null;

@endphp

@section('content')
    <div class="header-form mb-5">
        <div class="container">
            <div class="header-form__banner mt-5">
                <div class="row">
                    <div class="col-md-8">
                        <div class="header-form__title header-banner__heading">
                            <h1 class="banner-heading banner-alt-heading">
                                @if ($bannerTitle || $bannerSubtitle)
                                    {{ $bannerTitle }}
                                    @if ($bannerSubtitle)
                                        <div class="bannerMain-title">{{ $bannerSubtitle }}</div>
                                    @endif
                                @else
                                    {{ explode(' ', $item->name)[0] }}
                                    <div class="bannerMain-title">
                                        {{ implode(' ', array_slice(explode(' ', $item->name), 1)) }}</div>
                                @endif
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
        @php
            $tour_category_content_color = $settings->get('tour_category_content_color');
            $tour_category_content_read_more_color = $settings->get('tour_category_content_read_more_color');
        @endphp
        <div class="my-5">
            <div class=container>
                <div class="tour-content__details " data-show-more>
                    <div class="editor-content line-clamp" data-show-more-content
                        @if ($item->long_description_line_limit > 0) style="
            -webkit-line-clamp: {{ $item->long_description_line_limit }}; @if ($tour_category_content_color)color:{{ $tour_category_content_color }}; @endif "
                                                                                                                                                 
                                                                                    @endif>
                        {!! $item->long_description !!}
                    </div>
                    @if ($item->long_description_line_limit > 0)
                        <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn more-text="Read more"
                            less-text='Read less'
                            style="@if ($tour_category_content_read_more_color) color:{{ $tour_category_content_read_more_color }}; @endif">
                            Read more</a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @php
        $category_block = $jsonContent['category_block'] ?? null;
        $category_block_category_ids = isset($category_block['category_ids'])
            ? $category_block['category_ids']
            : [null];
        $category_block_categories = $tourCategories->whereIn('id', $category_block_category_ids);

        $first_tour_block = $jsonContent ? $jsonContent['first_tour_block'] : null;
        $first_tour_block_tour_ids = $first_tour_block['tour_ids'] ?? [];
        $first_tour_block_tours = $tours->whereIn('id', $first_tour_block_tour_ids);

        $second_tour_block = $jsonContent ? $jsonContent['second_tour_block'] : null;
        $second_tour_block_tour_ids = $second_tour_block['tour_ids'] ?? [];
        $second_tour_block_tours = $tours->whereIn('id', $second_tour_block_tour_ids);
    @endphp

    @if (isset($category_block['is_enabled']) &&
            $category_block['is_enabled'] === '1' &&
            $category_block_categories->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @if (isset($category_block['heading_enabled']) && $category_block['heading_enabled'] === '1')
                            <div class="section-content">
                                <h2 class="subHeading">
                                    {{ $category_block['heading'] ?? '' }}
                                </h2>
                            </div>
                        @endif
                        <div class="activity-sorting-block mt-2">
                            <div class="search-header__activity">
                                <div class="activities-found">
                                    {{ $category_block_categories->count() }}
                                    {{ Str::plural('activity', $category_block_categories->count()) }} found
                                    <div class="activities-found__icon">
                                        <i class='bx bxs-error-circle'></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($category_block_categories as $category_block_category)
                        <div class="col-md-3">
                            <x-category-card :category="$category_block_category" style="style3" />
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
        $newsletterContent = $sectionContent->newsletter ?? null;
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
        <div class="offers-section my-5">
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
                    <div class="col-md-12">
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
        <div class="location-banner my-5 img-zoom-wrapper">
            <div class="container">
                <div class="location-banner__content"
                    style="{{ $isCountBackgroundColor && $tourCountContent->background_color ? 'background-color: ' . $tourCountContent->background_color : '' }}">
                    @if ($isCountBackgroundImage)
                        <div class="location-banner__img img-zoom">
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
                        @php
                            $tourCountCategory = $tourCategories->firstWhere(
                                'id',
                                (int) $tourCountContent->btn_link_category ?? null,
                            );
                            if ($tourCountCategory && $item->city) {
                                $tourCountBtnLink = buildCategoryDetailUrl($tourCountCategory);
                            } else {
                                $tourCountBtnLink = 'javascript:void(0)';
                            }

                            $tour_count_all_sub_category_Ids = $tourCountCategory
                                ? getAllCategoryIds((int) $tourCountCategory->id)
                                : [];

                            $tour_count_all_sub_category_Ids_tours = $tours->filter(
                                fn($tour) => $tour->categories
                                    ->pluck('id')
                                    ->intersect($tour_count_all_sub_category_Ids)
                                    ->isNotEmpty(),
                            );

                            if ($tourCountCategory) {
                                $tourCountCategory->tours_count = $tour_count_all_sub_category_Ids_tours->count();
                            }
                        @endphp

                        @if (isset($tourCountContent->is_button_enabled) && $tourCountContent->is_button_enabled === '1')
                            <a target="_blank" href="{{ sanitizedLink($tourCountBtnLink) }}"
                                style="
                                {{ $tourCountContent->btn_background_color ? 'background-color: ' . $tourCountContent->btn_background_color . ';' : '' }}
                                {{ $tourCountContent->btn_text_color ? 'color: ' . $tourCountContent->btn_text_color . ';' : '' }}"
                                class="app-btn themeBtn" type="button">
                                {{ str_replace('{x}', $tourCountCategory?->tours_count ?? 0, $tourCountContent->btn_text ?? 'Explore more') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if (isset($second_tour_block['is_enabled']) &&
            $second_tour_block['is_enabled'] === '1' &&
            $second_tour_block_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="section-content">
                            <h2 class="subHeading">
                                {{ $second_tour_block['heading'] ?? '' }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row four-items-slider tours-slider">
                    @foreach ($second_tour_block_tours as $second_tour_block_tour)
                        <div class="col-md-3">
                            <x-tour-card :tour="$second_tour_block_tour" style="style3" />
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
                            <div class="comment-card comment-card--shadow">
                                {{-- <div class="comment-card__img one-items-slider">
                                    <img data-src="{{ asset($testimonial->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                        alt="{{ $testimonial->featured_image_alt_text }}" class="imgFluid lazy"
                                        loading="lazy">
                                    @if ($testimonial->media->isNotEmpty())
                                        @foreach ($testimonial->media as $media)
                                            <img data-src="{{ asset($media->file_path ?? 'admin/assets/images/placeholder.png') }}"
                                                alt="{{ $media->alt_text }}" class="imgFluid lazy" loading="lazy">
                                        @endforeach
                                    @endif
                                </div> --}}
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
                                        {!! $testimonial->review ?? '' !!}
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

    @if (isset($newsletterContent->is_enabled) && $newsletterContent->is_enabled === '1')
        <div class="newsletter my-5">
            <div class="container">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="newsletter__img">
                            <img data-src="{{ asset($newsletterContent->left_image ?? 'admin/assets/images/placeholder.png') }}"
                                alt="{{ $newsletterContent->left_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                loading="lazy">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="newsletter__content"
                            @if ($newsletterContent->right_background_color) style="background-color: {{ $newsletterContent->right_background_color }}" @endif>
                            <div class="section-content">
                                <h2 class="subHeading"
                                    @if ($newsletterContent->title_text_color) style="color: {{ $newsletterContent->title_text_color }}" @endif>
                                    {{ $newsletterContent->title ?? '' }}
                                </h2>
                            </div>
                            <p
                                @if ($newsletterContent->description_text_color) style="color: {{ $newsletterContent->description_text_color }}" @endif>
                                {{ $newsletterContent->description ?? '' }}
                            </p>

                            <form class="line-form" method="POST" action="{{ route('save-newsletter') }}">
                                @csrf
                                <div class="line-form__input">
                                    <input id="email" type="email" name="email" placeholder="Email" required>
                                    <i class="bx bx-envelope"></i>
                                </div>
                                <button type="submit" class="primary-btn"
                                    @if ($newsletterContent->btn_background_color || $newsletterContent->btn_text_color) style="{{ $newsletterContent->btn_background_color ? 'background: ' . $newsletterContent->btn_background_color . ';' : '' }}{{ $newsletterContent->btn_text_color ? 'color: ' . $newsletterContent->btn_text_color . ';' : '' }}" @endif>
                                    {{ $newsletterContent->btn_text ?? 'Sign up' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="privacy-content"
                    @if ($newsletterContent->privacy_statement_text_color) style="color: {{ $newsletterContent->privacy_statement_text_color }}" @endif>
                    <p>{!! $newsletterContent->privacy_statement ?? '' !!}</p>
                </div>
            </div>
        </div>
    @endif
@endsection
