@if (!$content)
    <div class="event-promotions section-padding">
        <div class=container>
            <div class=section-content>
                <h2 class=heading>
                    Latest travel promotions
                </h2>
            </div>
            <div class="row pt-3 three-items-slider">
                <div class=col-md-4>
                    <div class=card-event-item>
                        <div class=card-event-item__img>
                            <img data-src="{{ asset('frontend/assets/images/jpg (1).webp') }}" alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=event-detail>
                            <p>
                                Our Birthday, Your Travel Party
                            </p> <span>Learn more<i class="bx bx-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class=col-md-4>
                    <div class=card-event-item>
                        <div class=card-event-item__img>
                            <img data-src="{{ asset('frontend/assets/images/jpg.webp') }}" alt=image class="imgFluid lazy"
                                loading="lazy">
                        </div>
                        <div class=event-detail>
                            <p>
                                Our Birthday, Your Travel Party
                            </p> <span>Learn more<i class="bx bx-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class=col-md-4>
                    <div class=card-event-item>
                        <div class=card-event-item__img>
                            <img data-src="{{ asset('frontend/assets/images/jpg (1).webp') }}" alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=event-detail>
                            <p>
                                Our Birthday, Your Travel Party
                            </p> <span>Learn more<i class="bx bx-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
                <div class=col-md-4>
                    <div class=card-event-item>
                        <div class=card-event-item__img>
                            <img data-src="{{ asset('frontend/assets/images/jpg.webp') }}" alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=event-detail>
                            <p>
                                Our Birthday, Your Travel Party
                            </p> <span>Learn more<i class="bx bx-chevron-right"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $backgroundColor = null;
        if ($content->box_type === 'normal_with_background_color') {
            $backgroundColor = isset($content->normal_background_color) ? $content->normal_background_color : null;
        } elseif ($content->box_type === 'slider_carousel_with_background_color') {
            $backgroundColor = isset($content->slider_carousel_background_color)
                ? $content->slider_carousel_background_color
                : null;
        }
        $toursQuery = \App\Models\Tour::query();

        // Order conditions (as per your original code)
        if ($content->order_by === 'latest') {
            $toursQuery->orderBy('created_at', 'desc');
        } elseif ($content->order_by === 'title') {
            $toursQuery->orderBy('title', 'asc');
        } elseif ($content->order_by === 'price_low_to_high') {
            $toursQuery->orderBy('regular_price', 'asc');
        } elseif ($content->order_by === 'price_high_to_low') {
            $toursQuery->orderBy('regular_price', 'desc');
        }
        if (isset($content->show_only_featured_items)) {
            $toursQuery->where('is_featured', '1');
        }

        // Additional conditions like 'custom' filters or categories
        if ($content->filter_type === 'custom') {
            $toursQuery->whereIn('id', $content->custom_tour_ids ?? []);
        } else {
            if (isset($content->filter_category_id)) {
                $toursQuery->where('category_id', $content->filter_category_id);
            }
            if (isset($content->filter_city_id)) {
                $cityIds = (array) $content->filter_city_id;
                $toursQuery->whereHas('cities', function ($query) use ($cityIds) {
                    $query->whereIn('cities.id', $cityIds);
                });
            }
        }

        // Limit the number of tours if applicable
        $toursLimit = $content->no_of_items;
        $tours = $toursQuery->limit($toursLimit)->get();
    @endphp


    <div class="event-promotions section-padding" style="background-color: {{ $backgroundColor ?? 'transparent' }}">
        <div class=container>
            <div class=section-content>
                <h2 class=heading style="color: {{ isset($content->title_color) ? $content->title_color : '' }};">
                    {{ $content->title }} </h2>
            </div>

            <div
                class="row pt-3 {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'promotions-slider' : 'row-cols-md-1 row-cols-lg-2 row-cols-xl-3' }}">
                @foreach ($tours as $tour)
                    @php
                        $image = null;
                        $alt_text = null;
                        if ($content->featured_image_type === 'featured') {
                            $image = $tour->featured_image;
                            $alt_text = $tour->featured_image_alt_text;
                        } elseif ($content->featured_image_type === 'promotional') {
                            $image = $tour->promotional_image ?? $tour->featured_image;
                            $alt_text = $tour->promotional_image_alt_text ?? $tour->featured_image_alt_text;
                        }
                    @endphp
                    <div class=col>
                        <a href="#" target="_blank" class=card-event-item>
                            <div class=card-event-item__img>
                                <img data-src="{{ asset($image ?? 'admin/assets/images/placeholder.png') }}"
                                    alt={{ $alt_text ?? 'promotional image' }} class="imgFluid lazy" loading="lazy">
                            </div>
                            <div class=event-detail>
                                <p>
                                    {{ $tour->title }}
                                </p> <span>Learn more<i class="bx bx-chevron-right"></i></span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
