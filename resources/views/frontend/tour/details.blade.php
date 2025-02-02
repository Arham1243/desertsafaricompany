@extends('frontend.layouts.main')
@section('content')

    @php
        $seo = $tour->seo ?? null;
    @endphp

    @if (isset($tour->show_phone) && $tour->show_phone === 1)
        <a href="tel:{{ $tour->phone_dial_code . $tour->phone_number }}" class="whatsapp-contact d-flex"><i
                class='bx bxl-whatsapp'></i></a>
    @endif
    <div class=container>
        <div class=row>
            <div class=col-md-9>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">Tours</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $tour->slug }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class=tour-details>
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
    </div>


    <div class="share-popup-wrapper" data-send-popup>
        <div class="share-popup light">
            <div class="share-popup__header">
                <div class="title">Share</div>
                <div class="close-btn">
                    <i class='bx bx-x'></i>
                </div>
            </div>
            <div class="share-popup__body">
                <ul class="platforms">
                    <li class="platform">
                        <a href="https://wa.me/?text={{ $tour->title }}%20{{ url()->current() }}" target="_blank">
                            <div class="icon" style="background: #27D469;">
                                <i class='bx bxl-whatsapp'></i>
                            </div>
                            <div class="title">WhatsApp</div>
                        </a>
                    </li>
                    <li class="platform">
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}&title={{ $tour->title }}"
                            target="_blank">
                            <div class="icon" style="background: #0179B7;">
                                <i class='bx bxl-linkedin'></i>
                            </div>
                            <div class="title">LinkedIn</div>
                        </a>
                    </li>
                    <li class="platform">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank">
                            <div class="icon" style="background: #3D5A98;">
                                <i class='bx bxl-facebook'></i>
                            </div>
                            <div class="title">Facebook</div>
                        </a>
                    </li>
                    <li class="platform">
                        <a href="https://twitter.com/intent/tweet?text={{ $tour->title }}&url={{ url()->current() }}"
                            target="_blank">
                            <div class="icon" style="background: #000;">
                                <img src="https://imagecme.com/public/frontend/assets/images/x.png" alt="">
                            </div>
                            <div class="title">X</div>
                        </a>
                    </li>
                    <li class="platform">
                        <a href="mailto:?subject={{ $tour->title }}&body={{ url()->current() }}" target="_blank">
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


    @if ($tour->media->isNotEmpty())
        <div class="media-gallery--view mt-2">
            <div class="row g-0">
                <div class="col-lg-6">
                    <a href="{{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                        data-fancybox="gallery-2" class="media-gallery__item--1">
                        <img data-src="{{ asset($tour->media[0]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                            alt="{{ $tour->media[0]->alt_text ?? 'image' }}" class="imgFluid lazy" width="662.5"
                            height="400">
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                        data-fancybox="gallery-2" class="media-gallery__item--2">
                        <img data-src="{{ asset($tour->media[1]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                            alt="{{ $tour->media[1]->alt_text ?? 'image' }}" class="imgFluid lazy" width="662.5"
                            height="400">
                    </a>
                </div>
                <div class="col-lg-3">
                    <div class="row g-0">
                        <div class="col-12">
                            <a href="{{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                data-fancybox="gallery-2" class="media-gallery__item--3">
                                <img data-src="{{ asset($tour->media[2]->file_path ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt="{{ $tour->media[2]->alt_text ?? 'image' }}" class="imgFluid lazy" width="662.5"
                                    height="400">
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
                                            data-fancybox=gallery-3 class="{{ $loop->first ? 'd-flex' : 'd-none' }}">
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

    <div class="tour-details_banner2 mt-2 one-items-slider">
        @foreach ($tour->media as $media)
            <div class=tour-details_banner2--img>
                <img src={{ asset($media->file_path ?? 'frontend/assets/images/placeholder.png') }}
                    alt={{ $media->alt_text ?? 'image' }} class="imgFluid">
            </div>
        @endforeach
    </div>

    <div class=tour-content>
        <div class=container>
            <div class=row>
                <div class=col-md-9>
                    <div class=tour-content__header>
                        <div>
                            <div class=section-content>
                                <h2 class=heading>
                                    {{ $tour->title }}
                                </h2>
                            </div>
                            <div class=tour-content__headerLocation>
                                <div class=tour-content__headerReviews>
                                    <div class=headerReviews-content>


                                        <ul class="headerReviews--icon">
                                            <li>
                                                <x-star-rating :rating="$tour->average_rating" />
                                            </li>
                                        </ul>

                                        <span>
                                            @if ($tour->reviews->count() > 0)
                                                {{ $tour->reviews->count() }}
                                                Review{{ $tour->reviews->count() > 1 ? 's' : '' }}
                                            @else
                                                No Reviews Yet
                                            @endif
                                        </span>

                                    </div>
                                </div>
                                <div class=tour-content__headerLocation--details>
                                    @if (json_decode($tour->badge) && optional(json_decode($tour->badge))->is_enabled)
                                        <span class=pipeDivider></span>

                                        <div class="badge-of-excellence">
                                            <i style="{{ optional(json_decode($tour->badge))->background_color ? 'background-color: ' . json_decode($tour->badge)->background_color . ';' : '' }}
                                                {{ optional(json_decode($tour->badge))->icon_color ? 'color: ' . json_decode($tour->badge)->icon_color . ';' : '' }}"
                                                class="{{ json_decode($tour->badge)->icon_class }}"></i>
                                            {{ json_decode($tour->badge)->name }}
                                        </div>
                                    @endif
                                    @if ($tour->cities->isNotEmpty())
                                        <div class=location-headerLocation--details>
                                            <span class=pipeDivider></span>
                                            @foreach ($tour->cities as $i => $city)
                                                <span>
                                                    {{ $city->name }}
                                                    @if ($i != count($tour->cities) - 1)
                                                        ,
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul class=header-listGroup>
                            @if (Auth::check())
                                @php
                                    $isFavorited = Auth::user()->favoriteTours->contains($tour->id);
                                @endphp
                                <li>
                                    @if ($isFavorited)
                                        <a href="{{ route('tours.favorites.index') }}">
                                            <span class="header-listGroup faq-icon added">
                                                <i class="bx bxs-heart"></i>
                                            </span>
                                        </a>
                                    @else
                                        <span class="header-listGroup faq-icon">

                                            <form action="{{ route('tours.favorites.add', $tour->id) }}" method="post">
                                                @csrf
                                                <button type="submit"> <i class="bx bx-heart"></i></button>
                                            </form>
                                        </span>
                                    @endif
                                </li>
                            @endif
                            <li>
                                <a href='javascript:void(0)' data-send-button>
                                    <span class="header-listGroup faq-icon"> <i class="bx bx-share-alt"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @php
                        $features = json_decode($tour->features);
                    @endphp
                    @if ($features && isset($features->icon))
                        <div class=tour-content__line></div>
                        <div class="features-list">
                            <div class=row>
                                @foreach ($features->icon as $i => $feature)
                                    @if (isset($features->icon) && isset($features->title))
                                        <div class="col-md-6">
                                            <div class="features-item">
                                                <div class="icon">
                                                    @if (isset($features->icon[$i]))
                                                        <i class="{{ $features->icon[$i] }}"></i>
                                                    @endif
                                                </div>
                                                <div class="content">
                                                    @if (isset($features->title[$i]))
                                                        <div class="title">{{ $features->title[$i] }}</div>
                                                    @endif
                                                    @if (isset($features->content[$i]))
                                                        <p>{{ $features->content[$i] }}</p>
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
                                    <div class=tour-content__details>
                                        <div class="editor-content pt-4">
                                            {!! $tour->content !!}
                                        </div>
                                    </div>
                                @endif
                                @php
                                    $tourDetails = json_decode($tour->details, true) ?? [
                                        'title' => 'Important Information',
                                        'sections' => [],
                                    ];
                                @endphp

                                @if (!empty($tourDetails['sections']))
                                    <div class="tour-details">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="tour-details__title">
                                                    {{ $tourDetails['title'] ?? 'Important information' }}
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                @foreach ($tourDetails['sections'] as $section)
                                                    <div class="category-group mb-4">
                                                        <div class="tour-details__title mb-2">
                                                            {{ $section['title'] }}
                                                        </div>
                                                        @foreach ($section['categories'] as $category)
                                                            @if (!empty($category['category_name']))
                                                                <div class="tour-details__title mb-1">
                                                                    {{ $category['category_name'] }}
                                                                </div>
                                                            @endif

                                                            @if (!empty($category['items']))
                                                                <ul class="tour-details__items">
                                                                    @foreach ($category['items'] as $item)
                                                                        <li>{{ $item }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row pt-2">
                                    @if (json_decode($tour->inclusions))
                                        <div class="col-md-6 mb-4">
                                            <div class="tour-content__title mb-3">Price Includes</div>
                                            @foreach (json_decode($tour->inclusions) as $inclusion)
                                                <div class=Price-Includes__content>
                                                    <div class="tour-content__pra-icon">
                                                        <i class="bx bx-check mr-3"></i>
                                                    </div>
                                                    <div class=tour-content__pra>
                                                        {{ $inclusion }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if (json_decode($tour->exclusions))
                                        <div class="col-md-6 mb-4">
                                            <div class="tour-content__title mb-3">Price Excludes</div>
                                            @foreach (json_decode($tour->exclusions) as $exclusion)
                                                <div class=Price-Includes__content>
                                                    <div class="tour-content__pra-icon x-icon">
                                                        <i class="bx bx-x mr-3"></i>
                                                    </div>
                                                    <div class=tour-content__pra>
                                                        {{ $exclusion }}
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
                                                        <div>{{ $item->item ?? '' }}</div>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif



                    @if ($tour->location_type === 'normal_itinerary')
                        <div class=tour-content__line></div>
                        <div class="pb-2 pt-3">
                            <div class=itinerary>
                                @if ($tour->normalItineraries->isNotEmpty())
                                    <div class=tour-content__SubTitle>
                                        Itinerary
                                    </div>
                                    @foreach ($tour->normalItineraries as $itinerary)
                                        <div class="itinerary-card accordian-2 {{ $loop->first ? 'active' : '' }} mb-3">
                                            <div class="itinerary-card__header accordian-2-header border-bottom-0 p-0">
                                                <h5 class="mb-0">
                                                    <button type="button" class="itinerary-card__header--btn">
                                                        <div class="tour-content__pra-icon">
                                                        </div>
                                                        <div class="tour-content__title tour-content__title--Blue">
                                                            Day {{ $itinerary->day }} <span class="px-2">-</span>
                                                        </div>
                                                        <h6 class="tour-content__title text-left mb-0">
                                                            {{ $itinerary->title }}</h6>
                                                    </button>
                                                </h5>
                                            </div>
                                            <div class="itinerary-card__body accordian-2-content">
                                                <div class="hidden-wrapper">
                                                    <p class="tour-content__pra mb-1">
                                                        {{ $itinerary->description }}
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

                    @if ($tour->description)
                        <div class=tour-content__line></div>
                        <div class="pb-4 pt-3">
                            <div class="tour-content__SubTitle">Description</div>

                            <div data-show-more>
                                <div class="tour-content__pra line-clamp" data-show-more-content
                                    @if ($tour->description_line_limit > 0) style="
-webkit-line-clamp: {{ $tour->description_line_limit }};
" @endif>
                                    {{ $tour->description }}

                                </div>
                                @if ($tour->description_line_limit > 0)
                                    <a href="javascript:void(0)" class="loginBtn mt-2" data-show-more-btn
                                        more-text="See more" less-text='Show less'> See more</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($tour->location_type === 'itinerary_experience')
                        @php
                            $itineraryExperience = json_decode($tour->itinerary_experience, true);
                            $orderedItems = collect();

                            if (isset($itineraryExperience['vehicles']) && isset($itineraryExperience['stops'])) {
                                foreach ($itineraryExperience['vehicles'] as $vehicle) {
                                    $orderedItems->push([
                                        'order' => isset($vehicle['order']) ? $vehicle['order'] : -1,
                                        'type' => 'vehicle',
                                        'name' => $vehicle['name'],
                                        'time' => $vehicle['time'],
                                    ]);
                                }

                                foreach ($itineraryExperience['stops'] as $key => $stop) {
                                    $stopItem = [
                                        'order' => isset($stop['order']) ? $stop['order'] : -1,
                                        'type' => 'stop',
                                        'title' => $stop['title'],
                                        'activities' => $stop['activities'],
                                        'sub_stops' => [],
                                    ];

                                    // Check if sub-stops are enabled and append sub-stops to the stop item
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

                                    // Add the stop item to the collection
                                    $orderedItems->push($stopItem);
                                }
                            }

                            $orderedItems = $orderedItems->reject(fn($item) => $item['order'] === -1)->sortBy('order');
                        @endphp
                        <div class=tour-content__line></div>
                        <div class=activity-experience>
                            <div class=tour-content__SubTitle>
                                experience
                            </div>
                            <div class="timeline-item-info--primary mb-2">
                                Itinerary
                            </div>
                            <div class=activity-experience-items__content>
                                <div class=activity-experience__itinerary>
                                    <div class="row mb-4">
                                        <div class=col-md-4>
                                            <ul class=experience-itinerary-timeline>
                                                @if ($itineraryExperience['pickup_locations'])
                                                    <li class=activity-itinerary-timeline__item>
                                                        <div class=timeline-item__wrapper>
                                                            <div class=timeline-item-stop>
                                                                <span class=timeline-item__icon>
                                                                    <i class='bx bx-location-plus'></i>
                                                                </span>
                                                                <div class="timeline-item-info timeline-item__info">
                                                                    <h3
                                                                        class="timeline-item-info--primary tour-content__title">
                                                                        {{ $itineraryExperience['pickup_locations'] ? count(explode(',', $itineraryExperience['pickup_locations'])) : 0 }}
                                                                        pickup location options:</h3>
                                                                    <section>
                                                                        <div
                                                                            class="timeline-item-info--secondary tour-content__pra">
                                                                            <p>{{ $itineraryExperience['pickup_locations'] ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif

                                                @foreach ($orderedItems as $item)
                                                    @if ($item['type'] == 'vehicle')
                                                        <li class="activity-itinerary-timeline__item">
                                                            <div class="timeline-item__wrapper">
                                                                <div class="timeline-item-stop">
                                                                    <span
                                                                        class="timeline-item__icon timeline-item__staricon">
                                                                        <i class='bx bxs-car'></i>
                                                                    </span>
                                                                    <div class="timeline-item-info timeline-item__info">
                                                                        <h3
                                                                            class="timeline-item-info--primary tour-content__title">
                                                                            {{ $item['name'] }}
                                                                        </h3>
                                                                        <section>
                                                                            <div
                                                                                class="timeline-item-info--secondary tour-content__pra">
                                                                                <p>({{ $item['time'] }} minutes)</p>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif ($item['type'] == 'stop')
                                                        <li class="activity-itinerary-timeline__item">
                                                            <div class="timeline-item__wrapper">
                                                                <div class="timeline-item-stop">
                                                                    <span
                                                                        class="timeline-item__icon timeline-item__staricon">
                                                                        <i class="bx bx-star"></i>
                                                                    </span>
                                                                    <div class="timeline-item-info timeline-item__info">
                                                                        <h3
                                                                            class="timeline-item-info--primary tour-content__title">
                                                                            @if (is_array($item['title']))
                                                                                {{ implode(', ', $item['title']) }}
                                                                            @else
                                                                                {{ $item['title'] }}
                                                                            @endif
                                                                        </h3>
                                                                        <section>
                                                                            <div
                                                                                class="timeline-item-info--secondary tour-content__pra">
                                                                                <p>
                                                                                    @if (is_array($item['activities']))
                                                                                        {{ implode(', ', $item['activities']) }}
                                                                                    @else
                                                                                        {{ $item['activities'] }}
                                                                                    @endif
                                                                                </p>
                                                                            </div>
                                                                        </section>
                                                                        @if (!empty($item['sub_stops']))
                                                                            @foreach ($item['sub_stops'] as $index => $subStop)
                                                                                <div
                                                                                    class="timeline-item__subitems-wrapper">

                                                                                    <div class="grey-circle-icon">
                                                                                    </div>
                                                                                    <div
                                                                                        class="timeline-item-info timeline-item__info timeline-item__info--subitem">
                                                                                        <p
                                                                                            class="timeline-subitems-info--primary">
                                                                                            {{ $subStop['title'] }}</p>
                                                                                        <p
                                                                                            class="timeline-subitems-info--secondary">
                                                                                            {{ $subStop['activities'] }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach


                                                @if ($itineraryExperience['dropoff_locations'])
                                                    <li>
                                                        <div class=timeline-item__wrapper>
                                                            <div class=timeline-item-stop>
                                                                <span class=timeline-item__icon>
                                                                    <i class='bx bx-location-plus'></i>
                                                                </span>
                                                                <div class="timeline-item-info timeline-item__info">
                                                                    <h3
                                                                        class="timeline-item-info--primary tour-content__title">
                                                                        {{ $itineraryExperience['dropoff_locations'] ? count(explode(',', $itineraryExperience['dropoff_locations'])) : 0 }}
                                                                        drop-off locations:
                                                                    </h3>
                                                                    <section>
                                                                        <div
                                                                            class="timeline-item-info--secondary tour-content__pra">
                                                                            <p>{{ $itineraryExperience['dropoff_locations'] ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                    </section>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                        <div class=col-md-8>
                                            <div class="tour-content-location__map activity-experience__map">
                                                <iframe
                                                    src="{{ $itineraryExperience['map_iframe'] ?? 'https://www.google.com/maps?qUnited Arab Emirates&output=embed' }}"
                                                    width=600 height=450 style=border:0 allowfullscreen
                                                    referrerpolicy=no-referrer-when-downgrade></iframe>
                                            </div>
                                            <div class=activity-experience-itinerary__map-title>
                                            </div>
                                            <div class=itinerary__map-title-main>
                                                <i class="bx bx-star"></i>
                                                <div class=itinerary__map-label>
                                                    Main stop
                                                </div>
                                            </div>
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

                    @if ($tour->faqs->isNotEmpty())
                        <div class=tour-content__line></div>
                        <div class="pb-2 pt-3">
                            <div class="faqs">
                                <div class="tour-content__SubTitle">
                                    FAQS
                                </div>
                                @foreach ($tour->faqs as $faq)
                                    <div class="faqs-single accordian {{ $loop->first ? 'active' : '' }}">
                                        <div class="faqs-single__header accordian-header">
                                            <div class="faq-icon"><i class="bx bx-plus"></i></div>
                                            <div class="tour-content__title">{{ $faq->question }}</div>
                                        </div>
                                        <div class="faqs-single__content accordian-content">
                                            <div class="hidden-wrapper tour-content__pra">
                                                {{ $faq->answer }}
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

                                    // Find the most common rating
                                    $ratingCounts = $reviews
                                        ->groupBy('rating')
                                        ->map(fn($group) => $group->count())
                                        ->sortDesc();

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
                                                    {{ $mostCommonCategory }} ({{ $mostCommonRatingCount }} reviews)
                                                </div>
                                                <div class="tour-content__pra">From {{ $totalReviews }} reviews</div>
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
                                                <div class="username">{{ $review->user->full_name ?? 'N/A' }}</div>

                                                <div class="date">{{ $review->created_at->format('d/M/Y H:i') }}</div>


                                                <div class="title-wrapper">
                                                    <div class="review-box">{{ $review->rating }}/5</div>
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
                        <div class=tour-content__line></div>
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
                            You must <a href="javascript:void(0)" class="loginBtn">log in</a> to write review
                        </div>
                    @endif


                </div>
                <div class=col-md-3>
                    @include('frontend.vue.main', [
                        'appId' => 'tour-pricing',
                        'appComponent' => 'tour-pricing',
                        'appJs' => 'tour-pricing',
                    ])
                    <div class=tour-content_book_app>
                        <div class=Why-Book-Us>
                            <h6 class="tour-content__title mb-4">
                                Why Book With Us?
                            </h6>
                            <div class=Why-Book-Us__content>
                                <div class="Why-Book-Us__icon tour-content__pra-icon">
                                    <i class="bx bx-phone"></i>
                                </div>
                                <div class=tour-content__pra>
                                    No-hassle best price guarantee
                                </div>
                            </div>
                            <div class=Why-Book-Us__content>
                                <div class="Why-Book-Us__icon tour-content__pra-icon">
                                    <i class="bx bx-calendar-star"></i>
                                </div>
                                <div class=tour-content__pra>
                                    Customer care available 24/7
                                </div>
                            </div>
                            <div class=Why-Book-Us__content>
                                <div class="Why-Book-Us__icon tour-content__pra-icon">
                                    <i class="bx bx-star"></i>
                                </div>
                                <div class=tour-content__pra>
                                    Hand-picked Tours & Activities
                                </div>
                            </div>
                            <div class=Why-Book-Us__content>
                                <div class="Why-Book-Us__icon tour-content__pra-icon">
                                    <i class="bx bxs-plane-alt"></i>
                                </div>
                                <div class=tour-content__pra>
                                    Free Travel Insureance
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @if ($tour->related_tour_ids)
        <div class="my-5 pb-2">
            <div class=container>
                <div class="section-content text-center">
                    <h2 class=subHeading>
                        You might also like...
                    </h2>
                </div>
                <div class="row four-items-slider pt-3 mb-4">
                    @php
                        $relatedTours = App\Models\Tour::whereIn('id', json_decode($tour->related_tour_ids ?? '[]'))
                            ->where('status', 'publish')
                            ->get();

                    @endphp
                    @foreach ($relatedTours as $relatedTour)
                        <div class=col-md-3>
                            <x-tour-card :tour="$relatedTour" style="style1" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="loader-mask" id="loader">
        <div class="loader"></div>
    </div>
@endsection
@push('css')
    <style type="text/css">
        .whatsapp-contact {
            display: none;
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
                btn.addEventListener('click', function() {
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
    </script>
@endpush
