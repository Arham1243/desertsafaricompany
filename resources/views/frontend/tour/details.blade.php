@extends('frontend.layouts.main')
@section('content')
    @php
        $isFavorited = Auth::check() ? Auth::user()->favoriteTours->contains($tour->id) : null;
        $entityTitle = $tour->title;
        $shareUrl = urlencode(url()->current());
        $shareText = urlencode($entityTitle . ' - ' . url()->current());
    @endphp
    <div class="gt-eesti">
        <div class="share-popup-wrapper" data-send-popup>
            <div class="share-popup light">
                <div class="share-popup__header">
                    <div class="title">Share</div>
                    <div class="popup-close-icon close-btn">
                        <i class='bx bx-x'></i>
                    </div>
                </div>
                <div class="share-popup__body">
                    <ul class="platforms">
                        <li class="platform">
                            <a href="https://wa.me/?text={{ $shareText }}" target="_blank">
                                <div class="icon" style="background: #27D469;">
                                    <i class='bx bxl-whatsapp'></i>
                                </div>
                                <div class="title">WhatsApp</div>
                            </a>
                        </li>

                        <li class="platform">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}&quote={{ urlencode($entityTitle) }}"
                                target="_blank">
                                <div class="icon" style="background: #3D5A98;">
                                    <i class='bx bxl-facebook'></i>
                                </div>
                                <div class="title">Facebook</div>
                            </a>
                        </li>

                        <li class="platform">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($entityTitle) }}&url={{ $shareUrl }}"
                                target="_blank">
                                <div class="icon" style="background: #000;">
                                    <img src="https://imagecme.com/public/frontend/assets/images/x.png" alt="X">
                                </div>
                                <div class="title">X</div>
                            </a>
                        </li>

                        <li class="platform">
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ urlencode($entityTitle) }}"
                                target="_blank">
                                <div class="icon" style="background: #0A66C2;">
                                    <i class='bx bxl-linkedin'></i>
                                </div>
                                <div class="title">LinkedIn</div>
                            </a>
                        </li>

                        <li class="platform">
                            @php
                                $companyEmail = $settings->get('company_email');
                                $subject = rawurlencode("Check this out: {$entityTitle}");
                                $body = rawurlencode("Hey,\nI thought you’d like this:\n\n{$entityTitle}\n{$shareUrl}");
                            @endphp
                            <a href="mailto:?subject={{ $subject }}&body={{ $body }}">
                                <div class="icon" style="background: grey;">
                                    <i class='bx bxs-envelope'></i>
                                </div>
                                <div class="title">Email</div>
                            </a>
                        </li>
                    </ul>

                    <div class="copy-link">
                        <input type="text" readonly class="copy-link__input" value="{{ url()->current() }}">
                        <button type="button" class="copy-link__btn primary-btn" onclick="copyLink()">Copy</button>
                    </div>
                </div>
            </div>
        </div>

        @php
            $seo = $tour->seo ?? null;
        @endphp

        @php
            $fullPhone = '+' . $tour->phone_dial_code . $tour->phone_number;
        @endphp

        @if ((int) $settings->get('is_global_whatsapp_number_enabled') !== 1)
            @if (isset($tour->show_phone) && (int) $tour->show_phone === 1)
                <a href="https://api.whatsapp.com/send?phone={{ $fullPhone }}" target="_blank"
                    class="whatsapp-contact d-flex">
                    <i class='bx bxl-whatsapp'></i>
                </a>
            @endif
        @endif

        <div class=container>
            <div class=row>
                <div class=col-md-12>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                            <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Tours</a></li>
                            <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                            @if ($currentCategory && $tour->city)
                                <li class="breadcrumb-item">
                                    <a
                                        href="{{ route('tours.category.details', [$tour->city->country->iso_alpha2, $tour->city->slug, $currentCategory->slug]) }}">
                                        {{ $currentCategory->name ?? '' }}
                                    </a>
                                </li>
                                <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ $tour->slug }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="tour-content py-0">
            <div class=container>
                <div class=row>
                    <div class=col-md-12>
                        <div class=tour-content__header>
                            <div>
                                <div class=section-content>
                                    <h1 class="heading">
                                        {{ $tour->title }}
                                    </h1>
                                </div>
                                <div class=tour-content__headerLocation>
                                    <div class=tour-content__headerReviews>
                                        <div class=headerReviews-content>
                                            <ul class="headerReviews--icon">
                                                <li>
                                                    <x-star-rating :rating="$tour->average_rating" />
                                                </li>
                                            </ul>
                                            <span style="margin-top: 4px;">
                                                @if ($tour->reviews->count() > 0)
                                                    {{ $tour->reviews->count() }}
                                                    Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                                                @else
                                                    No reviews yet
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <ul class="header-listGroup show-in-mobile">
                                        <li>
                                            @if (!Auth::check())
                                                <span open-vue-login-popup class="header-listGroup faq-icon not-logged-in"
                                                    onclick="showMessage('Please log in to add this tour to favorites.', 'error','top-right')">
                                                    <i class="bx bx-heart"></i>
                                                </span>
                                            @elseif ($isFavorited)
                                                <a href="{{ route('tours.favorites.index') }}">
                                                    <span class="header-listGroup faq-icon red-heart">
                                                        <i class="bx bxs-heart"></i>
                                                    </span>
                                                </a>
                                            @else
                                                <span class="header-listGroup faq-icon">
                                                    <form action="{{ route('tours.favorites.add', $tour->id) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit"><i class="bx bx-heart"></i></button>
                                                    </form>
                                                </span>
                                            @endif
                                        </li>
                                        <li>
                                            <a href='javascript:void(0)' data-send-button>
                                                <span class="header-listGroup faq-icon"> <i
                                                        class="bx bx-share-alt"></i></span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class=tour-content__headerLocation--details>
                                        @php
                                            $authorConfig = $tour->author_config
                                                ? json_decode($tour->author_config)
                                                : null;
                                            $authorPrefixText = $settings->get('tour_author_prefix_text') ?? '';
                                            $authorBgColor = $authorConfig->background_color ?? '';
                                            $authorIconColor = $authorConfig->icon_color ?? '';
                                            $authorIconClass = $authorConfig->icon_class ?? '';
                                            $badgeBgColor = $settings->get('badge_background_color') ?? '';
                                            $badgeIconColor = $settings->get('badge_icon_color') ?? '';
                                            $badge = $tour->badge ? json_decode($tour->badge) : null;
                                            $badgeIconClass = $badge->icon_class ?? '';
                                            $badgeName = $badge->name ?? '';

                                            $authorStyle = '';
                                            if ($authorBgColor) {
                                                $authorStyle .= "background-color: $authorBgColor;";
                                            }
                                            if ($authorIconColor) {
                                                $authorStyle .= "color: $authorIconColor;";
                                            }

                                            $badgeStyle = '';
                                            if ($badgeBgColor) {
                                                $badgeStyle .= "background-color: $badgeBgColor;";
                                            }
                                            if ($badgeIconColor) {
                                                $badgeStyle .= "color: $badgeIconColor;";
                                            }
                                        @endphp
                                        @if (json_decode($tour->badge) && $tour->has_five_star_five_review)
                                            <span class=pipeDivider><i class='bx bxs-circle'></i> </span>
                                            <div class="badge-of-excellence">
                                                @if ($badgeStyle && $badgeIconClass)
                                                    <i style="{{ $badgeStyle }}" class="{{ $badgeIconClass }}"></i>
                                                @endif
                                                {{ $badgeName }}
                                            </div>
                                        @else
                                            <span class=pipeDivider><i class='bx bxs-circle'></i> </span>
                                            @if ($tour->author)
                                                <div class="badge-of-excellence">
                                                    @if ($authorStyle && $authorIconClass)
                                                        <i style="{{ $authorStyle }}" class="{{ $authorIconClass }}"></i>
                                                    @endif
                                                    <span>{{ $authorPrefixText . ' : ' ?? 'Designed and Developed by : ' }}</span>
                                                    <div>{{ $tour->author->name }}</div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <ul class="header-listGroup show-in-desktop">
                                <li>
                                    @if (!Auth::check())
                                        <span open-vue-login-popup class="header-listGroup faq-icon not-logged-in"
                                            onclick="showMessage('Please log in to add this tour to favorites.', 'error','top-right')">
                                            <i class="bx bx-heart"></i>
                                        </span>
                                    @elseif ($isFavorited)
                                        <a href="{{ route('tours.favorites.index') }}">
                                            <span class="header-listGroup faq-icon red-heart">
                                                <i class="bx bxs-heart"></i>
                                            </span>
                                        </a>
                                    @else
                                        <span class="header-listGroup faq-icon">
                                            <form action="{{ route('tours.favorites.add', $tour->id) }}" method="post">
                                                @csrf
                                                <button type="submit"><i class="bx bx-heart"></i></button>
                                            </form>
                                        </span>
                                    @endif
                                </li>
                                <li>
                                    <a href='javascript:void(0)' data-send-button>
                                        <span class="header-listGroup faq-icon"> <i class="bx bx-share-alt"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $bannerStyle = $settings->get('banner_style');
        @endphp
        @if ($bannerStyle === 'style-1')
            <div class=tour-details_banner>
                <div class=tour-details_img>
                    <img data-src="{{ asset($tour->banner_image ?? 'frontend/assets/images/placeholder.png') }}"
                        alt='{{ $tour->banner_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
                </div>
                <div class=tour-details_btns>
                    @if ($tour->video_link)
                        <a href={{ sanitizedLink($tour->video_link) }} data-fancybox="gallery"
                            class="themeBtn themeBtn-white">Video</a>
                    @endif
                    @if ($tour->media->isNotEmpty())
                        @foreach ($tour->media as $media)
                            <a href={{ asset($media->file_path ?? 'frontend/assets/images/placeholder.png') }}
                                data-fancybox="gallery-1"
                                class="themeBtn themeBtn-white {{ $loop->first ? 'd-block' : 'd-none' }}">Gallery</a>
                        @endforeach
                    @endif
                </div>
            </div>
        @elseif ($bannerStyle === 'style-2')
            @if ($tour->media->isNotEmpty())
                <div class="media-gallery--view mt-2">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <a href="{{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                data-fancybox="gallery-2" class="media-gallery__item--1">
                                <img data-src="{{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt="{{ $tour->media[0]->alt_text ?? 'image' }}" class="imgFluid lazy"
                                    width="662.5" height="400">
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <a href="{{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                data-fancybox="gallery-2" class="media-gallery__item--2">
                                <img data-src="{{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt="{{ $tour->media[1]->alt_text ?? 'image' }}" class="imgFluid lazy"
                                    width="662.5" height="400">
                            </a>
                        </div>
                        <div class="col-lg-3">
                            <div class="row g-0">
                                <div class="col-12">
                                    <a href="{{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                        data-fancybox="gallery-2" class="media-gallery__item--3">
                                        <img data-src="{{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                            alt="{{ $tour->media[2]->alt_text ?? 'image' }}" class="imgFluid lazy"
                                            width="662.5" height="400">
                                    </a>
                                </div>
                                <div class="col-12">
                                    <a href="{{ asset($tour->media[3]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                        data-fancybox="gallery-2" class="media-gallery__item--4">
                                        <img data-src="{{ asset($tour->media[3]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                            alt="{{ $tour->media[3]->alt_text ?? 'image' }}" class="imgFluid lazy"
                                            width="662.5" height="400">
                                    </a>
                                    @if (count($tour->media) > 4)
                                        <div class="media-gallery--view__morePics">
                                            @foreach ($tour->media->slice(4) as $media)
                                                <a href="{{ asset($media->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                                    type="button" data-fancybox="gallery-4"
                                                    class="{{ $loop->first ? 'd-flex' : 'd-none' }}">
                                                    <span class="media-gallery--view__morePics-icon">
                                                        <i class="bx bx-photo-album"></i>
                                                    </span>
                                                    +{{ count($tour->media) - 4 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif ($bannerStyle === 'style-3')
            @if ($tour->media->isNotEmpty())
                <div class="media-gallery--view media-gallery--view2 mt-2">
                    <div class="row g-0">
                        <div class=col-md-8>
                            <a href={{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}
                                class="media-gallery__item--1 media-gallery--view2" data-fancybox=gallery-3>
                                <img data-src="{{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt="{{ $tour->media[0]->alt_text ?? 'image' }}" class="imgFluid lazy">
                            </a>
                        </div>
                        <div class=col-md-4>
                            <div class="row g-0">
                                <div class=col-12>
                                    <a href={{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}
                                        class="media-gallery__item--3 media-gallery--view2" data-fancybox=gallery-3>
                                        <img data-src="{{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                            alt="{{ $tour->media[1]->alt_text ?? 'image' }}" class="imgFluid lazy">
                                    </a>
                                    @if (count($tour->media) > 4)
                                        <div class=media-gallery--view2__morePics>
                                            @foreach ($tour->media as $media)
                                                <a href={{ asset($media->file_path ?? 'frontend/assets/images/placeholder.png') }}
                                                    data-fancybox=gallery-3
                                                    class="{{ $loop->first ? 'd-flex' : 'd-none' }}">
                                                    <span class=media-gallery--view2__morePics-icon>
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </span>
                                                    Show all photos
                                                </a>
                                            @endforeach

                                        </div>
                                    @endif
                                </div>
                                <div class=col-12>
                                    <a href={{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}
                                        class="media-gallery__item--4 media-gallery--view2" data-fancybox=gallery-3>
                                        <img data-src="{{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                            alt="{{ $tour->media[2]->alt_text ?? 'image' }}" class="imgFluid lazy">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif ($bannerStyle === 'style-4')
            <div class="tour-details_banner2 one-items-slider">
                @foreach ($tour->media as $media)
                    <div class=tour-details_banner2--img>
                        <img src={{ asset($media->file_path ?? 'frontend/assets/images/placeholder.png') }}
                            alt={{ $media->alt_text ?? 'image' }} class="imgFluid">
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $global_heading_color = $settings->get('global_heading_color');
            $global_paragraph_color = $settings->get('global_paragraph_color');

            $global_color_style = [];

            if ($global_heading_color) {
                $global_color_style[] = "--global-heading-color: {$global_heading_color}";
            }
            if ($global_paragraph_color) {
                $global_color_style[] = "--global-paragraph-color: {$global_paragraph_color}";
            }

            $global_color_style_attribute = empty($global_color_style)
                ? ''
                : 'style="' . implode('; ', $global_color_style) . '"';

        @endphp
        <div class="tour-content pt-4" {!! $global_color_style_attribute !!}>
            <div class="availability-bar" availability-bar>
                <div class="container">
                    <div class="availability-bar-padding">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-md-6">
                                <div class="details-wrapper">
                                    <div class="details">from <span availability-bar-lowest-price></span> per person </div>
                                    <div class="details sub">Lowest Price Guarantee</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="details-btn-wrapper">
                                    <button type="button" class="primary-btn"
                                        data-action="scroll-to-pricing-and-open-calendar">Check Availability</button>
                                    @if (!Auth::check())
                                        <button open-vue-login-popup type="button" class="wishlist-btn"
                                            title="Add to wishlist"
                                            onclick="showMessage('Please log in to add this tour to favorites.', 'error','top-right')">
                                            <i class='bx bx-heart'></i>
                                        </button>
                                    @elseif ($isFavorited)
                                        <a href="{{ route('tours.favorites.index') }}" title="View favorites"
                                            class="wishlist-btn" style="color: #DD0029;">
                                            <i class='bx bxs-heart'></i>
                                        </a>
                                    @else
                                        <form action="{{ route('tours.favorites.add', $tour->id) }}" method="post">
                                            @csrf
                                            <button type="submit" title="Add to wishlist" class="wishlist-btn">
                                                <i class='bx bx-heart'></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=container>
                <div class="row flex-column-reverse flex-md-row">
                    <div class=col-md-8>
                        @php
                            $features = json_decode($tour->features);
                        @endphp

                        @if ($features)
                            @php
                                $features_icon_color = $settings->get('features_icon_color');
                            @endphp
                            <div class="features-list">
                                <div class=row>
                                    @foreach ($features as $i => $feature)
                                        @if (isset($feature->icon) && isset($feature->title))
                                            <div class="col-md-6">
                                                <div class="features-item">
                                                    <div class="icon">
                                                        @if (isset($feature->icon))
                                                            <i @if ($features_icon_color) style="color: {{ $features_icon_color }};" @endif
                                                                class="{{ $feature->icon }}"></i>
                                                        @endif
                                                    </div>
                                                    <div class="content">
                                                        @if (isset($feature->title))
                                                            <div class="title">{!! $feature->title !!}</div>
                                                        @endif
                                                        @if (isset($feature->content))
                                                            <p>{!! $feature->content !!}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (json_decode($tour->inclusions) || $tour->exclusions || $tour->content)
                            <div class=tour-content__line></div>
                            <div class="pb-2 pt-3">
                                <div class=tour-content__description>
                                    @if ($tour->content)
                                        <div class="tour-content__details mb-4" data-show-more>
                                            <div class="editor-content pt-4 line-clamp" data-show-more-content
                                                @if ($tour->description_line_limit > 0) style="
                                        -webkit-line-clamp: {{ $tour->description_line_limit }};
                                        " @endif>
                                                {!! $tour->content !!}
                                            </div>
                                            @if ($tour->description_line_limit > 0)
                                                <a href="javascript:void(0)" class="loginBtn mt-2" data-show-more-btn
                                                    more-text="See more" less-text='Show less'> See more</a>
                                            @endif
                                        </div>
                                    @endif
                                    <div>

                                        @if ($tour->gift_image)
                                            <div class="gift-image">
                                                <img src="{{ asset($tour->gift_image) }}"
                                                    alt="{{ $tour->gift_image_alt_text ?? 'gift image' }}"
                                                    class="imgFluid">
                                            </div>
                                        @endif
                                        <div class="row pt-2">
                                            @if ($tour->enable_includes && json_decode($tour->inclusions))
                                                @php
                                                    $inclusion_icon_color = $settings->get('inclusion_icon_color');
                                                @endphp
                                                <div class="col-md-12">
                                                    <div class="tour-content__title mb-3">
                                                        @if (isset($tour->exclusions_inclusions_heading) &&
                                                                is_array(json_decode($tour->exclusions_inclusions_heading, true)) &&
                                                                array_key_exists('inclusions', json_decode($tour->exclusions_inclusions_heading, true)))
                                                            {{ json_decode($tour->exclusions_inclusions_heading, true)['inclusions'] }}
                                                        @else
                                                            Price Includes
                                                        @endif
                                                    </div>
                                                    @foreach (json_decode($tour->inclusions) as $inclusion)
                                                        <div class=Price-Includes__content>
                                                            <div class="tour-content__pra-icon"
                                                                @if ($inclusion_icon_color) style="color:{{ $inclusion_icon_color }};" @endif>
                                                                <i class="bx bx-check mr-3"></i>
                                                            </div>
                                                            <div class=tour-content__pra>
                                                                {!! $inclusion !!}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($tour->enable_excludes && json_decode($tour->exclusions))
                                                @php
                                                    $exclusion_icon_color = $settings->get('exclusion_icon_color');
                                                @endphp
                                                <div class="col-md-12 pb-4">
                                                    <div class="tour-content__title mb-3">
                                                        @if (isset($tour->exclusions_inclusions_heading) &&
                                                                is_array(json_decode($tour->exclusions_inclusions_heading, true)) &&
                                                                array_key_exists('exclusions', json_decode($tour->exclusions_inclusions_heading, true)))
                                                            {{ json_decode($tour->exclusions_inclusions_heading, true)['exclusions'] }}
                                                        @else
                                                            Price Excludes
                                                        @endif
                                                    </div>
                                                    @foreach (json_decode($tour->exclusions) as $exclusion)
                                                        <div class=Price-Includes__content>
                                                            <div class="tour-content__pra-icon x-icon"
                                                                @if ($exclusion_icon_color) style="color:{{ $exclusion_icon_color }};" @endif>
                                                                <i class="bx bx-x mr-3"></i>
                                                            </div>
                                                            <div class=tour-content__pra>
                                                                {!! $exclusion !!}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        @endif

                        @if ($tour->tourAttributes->isNotEmpty())
                            <div class=tour-content__line></div>
                            <div class="pb-2 pt-3">
                                <div class="tour-content__moreDetail">
                                    @foreach ($attributes as $attribute)
                                        @php
                                            $hasItems = $attribute->attributeItems->isNotEmpty();
                                            $isAssociatedWithTour = $tour->attributes->contains($attribute->id);
                                        @endphp

                                        @if ($hasItems && $isAssociatedWithTour)
                                            <div class="tour-content__title">
                                                {{ $attribute->name ?? '' }}
                                            </div>
                                            <ul class="tour-content__moreDetail--content">
                                                @foreach ($attribute->attributeItems as $item)
                                                    @if ($item->tourAttributes->contains($attribute->id))
                                                        <li>
                                                            <i class="bx bx-check-circle"></i>
                                                            <div>{!! $item->item ?? '' !!}</div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($tour->location_type === 'normal_itinerary' && $tour->enable_location)
                            <div class=tour-content__line></div>
                            <div class="pb-2 pt-3">
                                <div class=itinerary>
                                    @if ($tour->normalItineraries->isNotEmpty())
                                        <div class=tour-content__SubTitle>
                                            Itinerary
                                        </div>
                                        @foreach ($tour->normalItineraries as $itinerary)
                                            <div
                                                class="itinerary-card accordian-2 {{ $loop->first ? 'active' : '' }} mb-3">
                                                <div class="itinerary-card__header accordian-2-header border-bottom-0 p-0">
                                                    <h5 class="mb-0">
                                                        <button type="button" class="itinerary-card__header--btn">
                                                            <div class="tour-content__pra-icon">
                                                            </div>
                                                            <div class="tour-content__title tour-content__title--Blue">
                                                                {{ $itinerary->day }} <span class="px-2">-</span>
                                                            </div>
                                                            <h6 class="tour-content__title text-left mb-0">
                                                                {{ $itinerary->title }}</h6>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div class="itinerary-card__body accordian-2-content">
                                                    <div class="hidden-wrapper">
                                                        <p class="tour-content__pra mb-1">
                                                            {!! $itinerary->description !!}
                                                        </p>
                                                        @if ($itinerary->featured_image)
                                                            <div class="itinerary-card__body__img">
                                                                <img data-src="{{ asset($itinerary->featured_image) }}"
                                                                    alt="{{ $itinerary->featured_image_alt_text }}"
                                                                    class="lazy imgFluid">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class=tour-content__SubTitle>
                                            No Itinerary available for this tour
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($tour->location_type === 'itinerary_experience' && $tour->enable_location)
                            @php
                                $itinerary_section_background_line_color = $settings->get(
                                    'itinerary_section_background_line_color',
                                );
                                $itinerary_section_pickup_circle_background_color = $settings->get(
                                    'itinerary_section_pickup_circle_background_color',
                                );
                                $itinerary_section_pickup_circle_icon_color = $settings->get(
                                    'itinerary_section_pickup_circle_icon_color',
                                );
                                $itinerary_section_dropoff_circle_background_color = $settings->get(
                                    'itinerary_section_dropoff_circle_background_color',
                                );
                                $itinerary_section_dropoff_circle_icon_color = $settings->get(
                                    'itinerary_section_dropoff_circle_icon_color',
                                );
                                $itinerary_section_vehicles_circle_background_color = $settings->get(
                                    'itinerary_section_vehicles_circle_background_color',
                                );
                                $itinerary_section_vehicles_circle_icon_color = $settings->get(
                                    'itinerary_section_vehicles_circle_icon_color',
                                );
                                $itinerary_section_stops_circle_background_color = $settings->get(
                                    'itinerary_section_stops_circle_background_color',
                                );
                                $itinerary_section_stops_circle_icon_color = $settings->get(
                                    'itinerary_section_stops_circle_icon_color',
                                );
                                $itineraryExperience = json_decode($tour->itinerary_experience, true);
                                $orderedItems = collect();

                                if (isset($itineraryExperience['vehicles']) && isset($itineraryExperience['stops'])) {
                                    foreach ($itineraryExperience['vehicles'] as $vehicle) {
                                        $orderedItems->push([
                                            'order' => isset($vehicle['order']) ? $vehicle['order'] : -1,
                                            'type' => 'vehicle',
                                            'name' => $vehicle['name'],
                                            'icon_class' => $vehicle['icon_class'] ?? null,
                                            'time' => $vehicle['time'],
                                        ]);
                                    }

                                    foreach ($itineraryExperience['stops'] as $key => $stop) {
                                        $stopItem = [
                                            'order' => isset($stop['order']) ? $stop['order'] : -1,
                                            'type' => 'stop',
                                            'title' => $stop['title'],
                                            'icon_class' => $stop['icon_class'] ?? null,
                                            'activities' => $stop['activities'],
                                            'sub_stops' => [],
                                        ];

                                        if (
                                            isset($itineraryExperience['enable_sub_stops']) &&
                                            $itineraryExperience['enable_sub_stops'] == '1' &&
                                            isset($itineraryExperience['stops']['sub_stops']['main_stop']) &&
                                            in_array(
                                                $stop['title'],
                                                $itineraryExperience['stops']['sub_stops']['main_stop'],
                                            )
                                        ) {
                                            foreach (
                                                $itineraryExperience['stops']['sub_stops']['title']
                                                as $index => $subStopTitle
                                            ) {
                                                if (
                                                    $itineraryExperience['stops']['sub_stops']['main_stop'][$index] ==
                                                    $stop['title']
                                                ) {
                                                    $stopItem['sub_stops'][] = [
                                                        'order' =>
                                                            (isset($stop['order']) ? $stop['order'] : -1) +
                                                            ($index + 1) * 0.1,
                                                        'title' => $subStopTitle,
                                                        'activities' =>
                                                            $itineraryExperience['stops']['sub_stops']['activities'][
                                                                $index
                                                            ],
                                                    ];
                                                }
                                            }
                                        }

                                        $orderedItems->push($stopItem);
                                    }
                                }

                                $orderedItems = $orderedItems
                                    ->reject(fn($item) => $item['order'] === -1)
                                    ->sortBy('order');

                                $itinerary_section_style = [];

                                if ($itinerary_section_background_line_color) {
                                    $itinerary_section_style[] = "--background-line-color: {$itinerary_section_background_line_color}";
                                }
                                if ($itinerary_section_pickup_circle_background_color) {
                                    $itinerary_section_style[] = "--pickup-circle-background-color: {$itinerary_section_pickup_circle_background_color}";
                                }
                                if ($itinerary_section_pickup_circle_icon_color) {
                                    $itinerary_section_style[] = "--pickup-circle-icon-color: {$itinerary_section_pickup_circle_icon_color}";
                                }
                                if ($itinerary_section_dropoff_circle_background_color) {
                                    $itinerary_section_style[] = "--dropoff-circle-background-color: {$itinerary_section_dropoff_circle_background_color}";
                                }
                                if ($itinerary_section_dropoff_circle_icon_color) {
                                    $itinerary_section_style[] = "--dropoff-circle-icon-color: {$itinerary_section_dropoff_circle_icon_color}";
                                }
                                if ($itinerary_section_vehicles_circle_background_color) {
                                    $itinerary_section_style[] = "--vehicles-circle-background-color: {$itinerary_section_vehicles_circle_background_color}";
                                }
                                if ($itinerary_section_vehicles_circle_icon_color) {
                                    $itinerary_section_style[] = "--vehicles-circle-icon-color: {$itinerary_section_vehicles_circle_icon_color}";
                                }
                                if ($itinerary_section_stops_circle_background_color) {
                                    $itinerary_section_style[] = "--stops-circle-background-color: {$itinerary_section_stops_circle_background_color}";
                                }
                                if ($itinerary_section_stops_circle_icon_color) {
                                    $itinerary_section_style[] = "--stops-circle-icon-color: {$itinerary_section_stops_circle_icon_color}";
                                }

                                $itinerary_section_style_attribute = empty($itinerary_section_style)
                                    ? ''
                                    : 'style="' . implode('; ', $itinerary_section_style) . '"';
                            @endphp
                            <div class="tour-content__line"></div>
                            <div class="journey">
                                <div class="tour-content__SubTitle mb-3">
                                    Itinerary
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="journey-details" {!! $itinerary_section_style_attribute !!}>
                                            @if (isset($itineraryExperience['pickup_locations']))
                                                <div
                                                    class="journey-details__stop journey-details__stop--pickup journey-details__stop--location">
                                                    <div class="content-wrapper">
                                                        <div class="icon">
                                                            <i
                                                                class='{{ $itineraryExperience['pickup_dropoff_details']['pickup_icon_class'] ?? '' }}'></i>
                                                        </div>
                                                        <div class="info">
                                                            @php
                                                                $pickups =
                                                                    $itineraryExperience['pickup_locations'] ?? [];
                                                                $formattedPickups = implode(', ', $pickups);
                                                                $pickupCount = count($pickups);
                                                            @endphp
                                                            <div class="title">
                                                                {{ $pickupCount }}
                                                                {{ $pickupCount === 1 ? 'pickup location' : 'pickup locations' }}:
                                                            </div>
                                                            <div class="sub-title">{{ $formattedPickups }}</div>
                                                        </div>
                                                    </div>

                                                    @foreach ($itineraryExperience['pickup_dropoff_details']['pickup'] ?? [] as $entry)
                                                        @if (!empty($entry['points']))
                                                            <div class="journey-details__stop journey-details__stop--sub">
                                                                <div class="content-wrapper">
                                                                    <div class="sub-icon"></div>
                                                                    <div class="info">
                                                                        <div class="title">{{ $entry['city'] }}
                                                                        </div>
                                                                        <div class="sub-title">
                                                                            {!! implode(', ', $entry['points']) !!}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            @php
                                                $insideStopsWrapper = false;
                                                $stopCount = collect($orderedItems)->where('type', 'stop')->count();
                                            @endphp

                                            @foreach ($orderedItems as $item)
                                                @if ($item['type'] === 'stop' && !$insideStopsWrapper)
                                                    @php $insideStopsWrapper = true; @endphp
                                                    <div
                                                        class="destinations-wrapper {{ $stopCount === 1 ? 'one-stop' : '' }}">
                                                @endif

                                                @if ($item['type'] === 'stop')
                                                    <div class="journey-details__stop">
                                                        <div class="content-wrapper">
                                                            <div class="icon">
                                                                <i class="{{ $item['icon_class'] }}"></i>
                                                            </div>
                                                            <div class="info">
                                                                <div class="title">
                                                                    {!! is_array($item['title']) ? implode(', ', $item['title']) : $item['title'] !!}
                                                                </div>
                                                                <div class="sub-title">
                                                                    {!! is_array($item['activities']) ? implode(', ', $item['activities']) : $item['activities'] !!}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (!empty($item['sub_stops']))
                                                            @foreach ($item['sub_stops'] as $subStop)
                                                                <div
                                                                    class="journey-details__stop journey-details__stop--sub">
                                                                    <div class="content-wrapper">
                                                                        <div class="sub-icon"></div>
                                                                        <div class="info">
                                                                            <div class="title">{{ $subStop['title'] }}
                                                                            </div>
                                                                            <div class="sub-title">
                                                                                {!! $subStop['activities'] !!}</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @elseif ($item['type'] === 'vehicle')
                                                    @if ($insideStopsWrapper)
                                        </div> {{-- close destinations-wrapper --}}
                                        @php $insideStopsWrapper = false; @endphp
                        @endif

                        <div class="journey-details__stop journey-details__stop--vehicle">
                            <div class="content-wrapper">
                                <div class="icon">
                                    <i class="{{ $item['icon_class'] }}"></i>
                                </div>
                                <div class="info">
                                    <div class="title">{{ $item['name'] }}</div>
                                    <div class="sub-title">{{ $item['time'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        @if ($insideStopsWrapper)
                    </div> {{-- close destinations-wrapper if still open --}}
                    @endif

                    @if (isset($itineraryExperience['dropoff_locations']))
                        <div class="journey-details__stop  journey-details__stop--dropoff journey-details__stop--location">
                            <div class="content-wrapper">
                                <div class="icon">
                                    <i
                                        class='{{ $itineraryExperience['pickup_dropoff_details']['dropoff_icon_class'] ?? '' }}'></i>
                                </div>
                                <div class="info">
                                    @php
                                        $dropoffs = $itineraryExperience['dropoff_locations'] ?? [];
                                        $formattedDropoffs = implode(', ', $dropoffs);
                                        $dropoffCount = count($dropoffs);
                                    @endphp
                                    <div class="title">
                                        {{ $dropoffCount }}
                                        {{ $dropoffCount === 1 ? 'drop-off location' : 'drop-off locations' }}:
                                    </div>
                                    <div class="sub-title">{{ $formattedDropoffs }}</div>
                                </div>
                            </div>

                            @foreach ($itineraryExperience['pickup_dropoff_details']['dropoff'] ?? [] as $entry)
                                @if (!empty($entry['points']))
                                    <div class="journey-details__stop journey-details__stop--sub">
                                        <div class="content-wrapper">
                                            <div class="sub-icon"></div>
                                            <div class="info">
                                                <div class="title">{{ $entry['city'] }}</div>
                                                <div class="sub-title">{!! implode(', ', $entry['points']) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="journey-map">
                    <iframe
                        src="{{ $itineraryExperience['map_iframe'] ?? 'https://www.google.com/maps?q=United Arab Emirates&output=embed' }}"
                        width="600" height="450" style="border: none"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <div class="journey-map__label">
                        <i class="bx bx-star"></i>
                        Main stop
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($tour->location_type === 'normal_location' && $tour->address)
        <div class=tour-content__line></div>
        <div class="pb-2 pt-3">
            <div class=tour-content-location>
                <div class=tour-content__SubTitle>
                    Location
                </div>
                <div class=tour-content-location__map>

                    <iframe
                        src="https://www.google.com/maps?q={{ $tour->address ?? 'United Arab Emirates' }}&output=embed"
                        width=600 height=450 style=border:0 allowfullscreen
                        referrerpolicy=no-referrer-when-downgrade></iframe>
                </div>
            </div>
        </div>
    @endif

    @php
        $tourDetails = json_decode($tour->details, true) ?? [
            'sections' => [],
        ];
    @endphp

    @if (!empty($tourDetails['sections']))
        <div class="tour-details">
            @foreach ($tourDetails['sections'] as $section)
                <div class="row">
                    <div class="col-md-3">
                        <div class="tour-details__title">
                            {{ $section['title'] ?? '' }}
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="category-group mb-4">
                            @if (!empty($section['category']['title']))
                                <div class="tour-details__title mb-2">
                                    {{ $section['category']['title'] }}
                                </div>
                            @endif

                            @if (!empty($section['category']['items']))
                                <ul class="tour-details__items">
                                    @foreach ($section['category']['items'] as $item)
                                        <li>{!! $item !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($tour->faqs->isNotEmpty())
        <div class="pb-2 pt-3">
            <div class="faqs">
                <div class="tour-content__SubTitle mb-4">
                    FAQS
                </div>
                @foreach ($tour->faqs as $faq)
                    <div class="faqs-single accordian">
                        <div class="faqs-single__header accordian-header">
                            <div class="faq-icon"><i class="bx bx-plus"></i></div>
                            <div class="tour-content__title">{{ $faq->question }}
                            </div>
                        </div>
                        <div class="faqs-single__content accordian-content">
                            <div class="hidden-wrapper tour-content__pra">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    @if ($tour->reviews->isNotEmpty())
        <div class=tour-content__line></div>
        <div class="pb-2 pt-3">
            <div class=main-reviews__details>
                <div class=tour-content__SubTitle>
                    Reviews
                </div>
                @php

                    $reviews = $tour->reviews;
                    $excellentCount = $reviews->where('rating', 5)->count();
                    $veryGoodCount = $reviews->where('rating', 4)->count();
                    $averageCount = $reviews->where('rating', 3)->count();
                    $poorCount = $reviews->where('rating', 2)->count();
                    $terribleCount = $reviews->where('rating', 1)->count();

                    $totalReviews = $reviews->count();
                    $sumOfRatings = $reviews->sum('rating');

                    $averageRating = $totalReviews > 0 ? $sumOfRatings / $totalReviews : 0;

                    $ratingCounts = $reviews->groupBy('rating')->map(fn($group) => $group->count())->sortDesc();

                    $mostCommonRating = $ratingCounts->keys()->first();
                    $mostCommonRatingCount = $ratingCounts->first();

                    $ratingCategories = [
                        5 => 'Excellent',
                        4 => 'Very Good',
                        3 => 'Average',
                        2 => 'Poor',
                        1 => 'Terrible',
                    ];

                    $mostCommonCategory = $ratingCategories[$mostCommonRating] ?? 'Not Rated';
                @endphp

                <div class="row mb-5">
                    <div class=col-md-4>
                        <div class=main-reviews__box>
                            <div class="text-center">
                                <h2 class="main-reviews__detailsNum">
                                    {{ number_format($averageRating, 1) }}<span
                                        class="main-reviews__detailsNum">/5</span>
                                </h2>
                                <div class="tour-content__title mb-3">
                                    {{ $mostCommonCategory }}
                                    ({{ $mostCommonRatingCount }} reviews)
                                </div>
                                <div class="tour-content__pra">From
                                    {{ $totalReviews }} reviews</div>
                            </div>
                        </div>
                    </div>
                    <div class=col-md-8>
                        <div class="bars-wrapper">

                            <div class=row>
                                <div class="col-md-6 mb-4">
                                    <h6 class="tour-content__pra mb-1">
                                        Excellent
                                    </h6>
                                    <div class=main-reviews__details--remarks>
                                        <div class=main-reviews__details--lines></div>
                                        <div class=tour-content__title>
                                            {{ $excellentCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6 class="tour-content__pra mb-1">
                                        Very Good
                                    </h6>
                                    <div class=main-reviews__details--remarks>
                                        <div class=main-reviews__details--lines></div>
                                        <div class=tour-content__title>
                                            {{ $veryGoodCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6 class="tour-content__pra mb-1">
                                        Average
                                    </h6>
                                    <div class=main-reviews__details--remarks>
                                        <div class=main-reviews__details--lines></div>
                                        <div class=tour-content__title>
                                            {{ $averageCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6 class="tour-content__pra mb-1">
                                        Poor
                                    </h6>
                                    <div class=main-reviews__details--remarks>
                                        <div class=main-reviews__details--lines></div>
                                        <div class=tour-content__title>
                                            {{ $poorCount }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h6 class="tour-content__pra mb-1">
                                        Terrible
                                    </h6>
                                    <div class=main-reviews__details--remarks>
                                        <div class=main-reviews__details--lines></div>
                                        <div class=tour-content__title>
                                            {{ $terribleCount }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=tour-content__line></div>
            <div class="main-reviews mb-5">
                <div class="reviews">
                    <div class=tour-content__SubTitle>
                        Showing {{ $reviews->count() }} total
                    </div>
                    @foreach ($reviews as $review)
                        <div class="reviews-single">
                            <div class="reviews-single__img">
                                <img src="{{ $review->user && $review->user->avatar ? $review->user->avatar : asset('frontend/assets/images/avatar.png') }}"
                                    class="imgFluid">
                            </div>
                            <div class="reviews-single__info">
                                <div class="username">
                                    {{ $review->user->full_name ?? 'N/A' }}</div>

                                <div class="date">
                                    {{ $review->created_at->format('d/M/Y H:i') }}
                                </div>


                                <div class="title-wrapper">
                                    <div class="review-box">{{ $review->rating }}/5
                                    </div>
                                    <div class="title">{{ $review->title }}</div>
                                </div>
                                <p>
                                    {{ $review->review }}
                            </div>
                        </div>
                    @endforeach



                </div>
            </div>
        </div>
    @else
        <div class=main-reviews__details>
            <div class=tour-content__SubTitle>
                No Review
            </div>
        </div>
    @endif


    @if (Auth::check())
        <div class="main-reviews mb-5">
            <form class="review-form" action="{{ route('save_review') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $tour->id }}" name="tour_id">
                <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                <div class=tour-content__SubTitle>
                    Add a Review
                </div>
                <div class="row no-gutters">
                    <div class="col-12">
                        <div class="review-form__fields">
                            <label class="title"> Title <span class="text-danger">*</span>:</label>
                            <input type="text" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="review-form__fields">
                            <label class="title">Message <span class="text-danger">*</span>:</label>
                            <textarea rows="6" placeholder="Message" name="review" required>{{ old('review') }}</textarea>
                            @error('review')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="review-form__fields">
                            <label class="title"> Rating <span class="text-danger">*</span>:</label>
                            <div class="working-rating">
                                <input type="radio" id="star5" name="rating" value="5">
                                <label class="star" for="star5" title="Awesome"></label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label class="star" for="star4" title="Great"></label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label class="star" for="star3" title="Very good"></label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label class="star" for="star2" title="Good"></label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label class="star" for="star1" title="Bad"></label>
                            </div>
                            @error('rating')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="review-form__fields">
                            <button class="themeBtn themeBtn--fill">Submit</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    @else
        <div class="review-message tour-content__title">
            You must <a href="javascript:void(0)" class="loginBtn" open-vue-login-popup>log in</a> to write
            review
        </div>
    @endif
    </div>
    </div>
    <div class=col-md-4>
        <div class="tour-pricing-wrapper">
            @include('frontend.vue.main', [
                'appId' => 'tour-pricing',
                'appComponent' => 'tour-pricing',
                'appJs' => 'tour-pricing',
            ])
            @if ((int) $settings->get('is_enabled_why_book_with_us') === 1)
                @php
                    $perks = $settings->get('perks');
                    $perks_icon_color = $settings->get('perks_icon_color');
                @endphp
                @if (json_decode($perks))
                    <div class="tour-content_book_app mt-4">
                        <div class=Why-Book-Us>
                            <h6 class="tour-content__title mb-4">
                                Why Book With Us?
                            </h6>
                            @foreach (json_decode($perks) as $perk)
                                <div class=Why-Book-Us__content>
                                    <div class="Why-Book-Us__icon tour-content__pra-icon"
                                        @if ($perks_icon_color) style="color:{{ $perks_icon_color }};" @endif>
                                        <i class="{{ $perk->icon }}"></i>
                                    </div>
                                    <div class=tour-content__pra>
                                        {{ $perk->title }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
    </div>
    </div>
    @if ($tour->addOns->isNotEmpty())
        @foreach ($tour->addOns as $index => $addOn)
            <div class="{{ !$loop->last ? 'my-5' : '' }} pb-2">
                <div class=container>
                    <div class="section-content text-center">
                        @if ($addOn->heading)
                            <h2 class=subHeading>
                                {{ $addOn->heading }}
                            </h2>
                        @endif
                    </div>
                    @if (!empty($addOn->tour_ids))
                        <div class="row four-items-slider pt-3">
                            @php
                                $relatedTours = App\Models\Tour::whereIn('id', $addOn->tour_ids ?? [])
                                    ->where('status', 'publish')
                                    ->get();
                            @endphp
                            @foreach ($relatedTours as $relatedTour)
                                <div class=col-md-3>
                                    <x-tour-card :tour="$relatedTour" style="style1" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <div class="loader-mask" id="loader">
        <div class="loader"></div>
    </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style type="text/css">
        @font-face {
            font-family: "gt-eesti";
            src: url("{{ asset('frontend/assets/fonts/gt-eesti/regular.woff2') }}") format("woff2");
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: "gt-eesti";
            src: url("{{ asset('frontend/assets/fonts/gt-eesti/medium.woff2') }}") format("woff2");
            font-weight: 500;
            font-style: normal;
        }

        @font-face {
            font-family: "gt-eesti";
            src: url("{{ asset('frontend/assets/fonts/gt-eesti/bold.woff2') }}") format("woff2");
            font-weight: 700;
            font-style: normal;
        }

        @font-face {
            font-family: "gt-eesti";
            src: url("{{ asset('frontend/assets/fonts/gt-eesti/extra-bold.woff2') }}") format("woff2");
            font-weight: 800;
            font-style: normal;
        }

        .gt-eesti {
            font-family: "gt-eesti", sans-serif !important;
        }

        .gt-eesti * {
            font-family: inherit
        }

        .loader-mask {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1000000000000;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader {
            width: 48px;
            height: 48px;
            border: 4px solid var(--color-primary);
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sendPopupBtn = document.querySelectorAll('[data-send-button]');
            const popupWrapper = document.querySelector('[data-send-popup]');
            const copyLinkInput = popupWrapper.querySelector('.copy-link__input');
            const closeIcon = popupWrapper.querySelector('.close-btn');

            sendPopupBtn.forEach(btn => {
                btn?.addEventListener('click', function() {
                    popupWrapper.classList.add('open');
                });
            });

            closeIcon.addEventListener('click', function(e) {
                popupWrapper.classList.remove('open');
            });
        });

        const copyLink = () => {
            var copyText = document.querySelector('.copy-link__input');

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            document.execCommand('copy');

            showMessage('Link copied to clipboard!', 'success', 'top-right');
        }

        window.addEventListener("load", function() {
            const loader = document.getElementById("loader");
            loader.style.display = "none";
        });

        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date();

            const isPromo = @json($tour->price_type === 'promp');
            const lowestPrice = @json($tour->tour_lowest_price);
            const weekdayPrice = window.lowestPromoWeekdayDiscountPrice;
            const weekendPrice = window.lowestPromoWeekendDiscountPrice;

            function formatPrice(price) {
    return Number(price).toLocaleString('en-US', { maximumFractionDigits: 0 });
}

            flatpickr("#start_date", {
                dateFormat: "Y-m-d",
                disable: [
                    function(date) {
                        return date < today.setHours(0, 0, 0, 0);
                    }
                ],
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const date = dayElem.dateObj;
                    if (date < new Date().setHours(0, 0, 0, 0)) return;

                    let price;

                    if (isPromo) {
                        const day = date.getDay();
                        const isWeekend = [0, 5, 6].includes(day);
                        price = isWeekend ? parseInt(weekendPrice) : parseInt(weekdayPrice)
                    } else {
                        price = parseInt(lowestPrice);
                    }

                    const formattedPrice = `{{ currencySymbol() }}${formatPrice(price)}`;
                    const formattedFloatPrice =
                        `{{ currencySymbol() }}${price}`;

                    const priceTag = document.createElement("div");
                    const availabilityBarLowesPrice = document.querySelector(
                        "[availability-bar-lowest-price]");
                    availabilityBarLowesPrice.innerHTML = formattedFloatPrice;

                    priceTag.innerHTML = formattedPrice;
                    priceTag.className = "price";

                    dayElem.appendChild(priceTag);
                }
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            const tourPricing = document.querySelector('#tour-pricing');
            const bar = document.querySelector('[availability-bar]');
            const startDateInput = document.getElementById('start_date');
            if (!tourPricing || !bar || !startDateInput) return;

            window.addEventListener('scroll', () => {
                const rect = tourPricing.getBoundingClientRect();
                const scrollBottom = window.innerHeight + 150;
                if (rect.bottom <= scrollBottom) {
                    bar.classList.add('show');
                } else {
                    bar.classList.remove('show');
                }
            });
            const btn = document.querySelector('[data-action="scroll-to-pricing-and-open-calendar"]');
            const section = document.querySelector('#tour-pricing');
            const input = document.getElementById('start_date');

            if (!btn || !section || !input) return;

            btn.addEventListener('click', () => {
                const observer = new IntersectionObserver((entries, observer) => {
                    if (entries[0].isIntersecting) {
                        observer.disconnect();
                        setTimeout(() => input.click(), 300);
                    }
                }, {
                    threshold: 0.6
                });

                observer.observe(section);
                section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
@endpush
