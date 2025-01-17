@extends('frontend.layouts.main')
@section('content')

    @php
        $seo = $tour->seo ?? null;
    @endphp

    @if (isset($tour->show_phone) && $tour->show_phone === 1)
        <a href="tel:{{ $tour->phone_dial_code . $tour->phone_number }}" class="whatsapp-contact d-flex"><i
                class='bx bxl-whatsapp'></i></a>
    @endif

    <div class=tour-details id=tour-details.php>
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
                                    alt="{{ $tour->media[3]->alt_text ?? 'image' }}" class="imgFluid lazy" width="662.5"
                                    height="400">
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
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tours.index') }}">Tours</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $tour->slug }}</li>
                        </ol>
                    </nav>
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
                                    @if ($tour->badge_icon_class && $tour->badge_name)
                                        <span class=pipeDivider></span>
                                        <div class="badge-of-excellence">
                                            <i class="{{ $tour->badge_icon_class }}"></i>
                                            {{ $tour->badge_name }}
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
                            <li><a href=#>
                                    <span data-type=tour class="header-listGroup faq-icon">
                                        <i class="bx bx-heart"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href=#>
                                    <span class="header-listGroup faq-icon"> <i class="bx bx-share-alt"></i></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if (json_decode($tour->features))
                        <div class=tour-content__line></div>

                        <div class=tour-content__moreDetail>
                            <ul class=tour-content__moreDetail--content>
                                @foreach (json_decode($tour->features) as $feature)
                                    @if ($feature->icon && $feature->title)
                                        <li>
                                            <i class="{{ $feature->icon }}"></i>
                                            <div>{{ $feature->title }}</div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (json_decode($tour->inclusions) && $tour->exclusions && $tour->content)
                        <div class=tour-content__line></div>
                        <div class=tour-content__description>
                            @if ($tour->content)
                                <div class=tour-content__details>
                                    <div class=tour-content__SubTitle>
                                        Description
                                    </div>
                                    <div class="tour-content__pra editor-content">
                                        {!! $tour->content !!}
                                    </div>
                                </div>
                            @endif
                            <div class=row>
                                @if (json_decode($tour->inclusions))
                                    <div class="col-md-6 mb-4">
                                        <div class="tour-content__title mb-3">Price Includes</div>
                                        @foreach (json_decode($tour->inclusions) as $inclusion)
                                            <div class=Price-Includes__content>
                                                <div class="tour-content__pra-icon check-icon">
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
                    @endif

                    @if ($tour->tourAttributes->isNotEmpty())
                        <div class=tour-content__line></div>
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
                    @endif

                    @if ($tour->tourDetails->isNotEmpty())
                        <div class=tour-content__line></div>
                        <div class="tour-content__moreDetail">
                            @foreach ($tour->tourDetails as $i => $detail)
                                <div class="tour-content__title">
                                    {{ $detail->name ?? '' }}
                                </div>
                                <ul class="tour-content__moreDetail--content">
                                    <li class="d-block">
                                        <div>{{ $detail->items ?? '' }}</div>
                                        <div><a href="{{ sanitizedLink($detail->urls) }}" target="_blank"
                                                class="link">View</a></div>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                    @endif

                    @if ($tour->location_type === 'normal_itinerary')
                        <div class=tour-content__line></div>
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
                                                    <div class="tour-content__pra-icon check-icon">
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

                                                                                    <div class="grey-circle-icon"></div>
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
                    @endif

                    @if ($tour->faqs->isNotEmpty())
                        <div class=tour-content__line></div>
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
                    @endif

                    <div class=tour-content__line></div>
                    @if ($tour->reviews->isNotEmpty())
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
                <div class="row pt-3 mb-4">
                    @php
                        $relatedTours = App\Models\Tour::whereIn('id', json_decode($tour->related_tour_ids ?? '[]'))
                            ->where('status', 'publish')
                            ->get();

                    @endphp
                    @foreach ($relatedTours as $relatedTour)
                        <div class=col-md-3>
                            <div class="card-content normal-card__content">
                                <a href={{ route('tours.details', $relatedTour->slug) }}
                                    class="card_img normal-card__img">
                                    <img data-src={{ asset($relatedTour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt={{ $relatedTour->featured_image_alt_text ?? 'image' }} class='imgFluid lazy'>
                                    <div class=price-details>
                                        <div class=price-location data-tooltip="tooltip"
                                            title="{{ $relatedTour->cities->pluck('name')->implode(', ') }}">
                                            <i class="bx bxs-location-plus"></i>
                                            {{ $relatedTour->cities[0]->name }}
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlist>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="tour-activity-card__details normal-card__details">
                                    <div class=vertical-activity-card__header>
                                        <div data-tooltip="tooltip" title="{{ $tour->title }}"
                                            class="tour-activity-card__details--title text-truncate">
                                            {{ $relatedTour->title }}
                                        </div>
                                    </div>
                                    <div class="card-rating mb-2">
                                        <div class=card-rating__yellow>
                                            {{ $relatedTour->average_rating ?? 0 }}/5
                                        </div>
                                        <span>
                                            ({{ count($relatedTour->reviews) === 0
                                                ? 'No Reviews Yet'
                                                : count($relatedTour->reviews) . ' Review' . (count($relatedTour->reviews) > 1 ? 's' : '') }})
                                        </span>
                                    </div>
                                    <div class="tour-listing__info normal-card__info">
                                        <p class=baseline-pricing__from>
                                            <span class=baseline-pricing__from--text>From</span>
                                            <span
                                                class=baseline-pricing__from--value>{{ formatPrice($relatedTour->regular_price) }}</span>
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
        window.addEventListener("load", function() {
            const loader = document.getElementById("loader");
            loader.style.display = "none";
        });
    </script>
@endpush
