@if (!$content)
    <div class="popular-related-destinations section-padding">
        <div class=container>
            <div class=section-content>
                <h2 class=heading>Popular Related Destinations</h2>
            </div>
            <div class="row mt-4">
                <div class=col-md-3>
                    <div class=popular-related-destinations__content>
                        <div class=popular-related-destinations__img>
                            <img data-src={{ asset('frontend/assets/images/popular1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <a href=# class=popular-related-destinations__title>
                            Vietnam
                        </a>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=popular-related-destinations__content>
                        <div class=popular-related-destinations__img>
                            <img data-src={{ asset('frontend/assets/images/popular1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <a href=# class=popular-related-destinations__title>
                            Vietnam
                        </a>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=popular-related-destinations__content>
                        <div class=popular-related-destinations__img>
                            <img data-src={{ asset('frontend/assets/images/popular1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <a href=# class=popular-related-destinations__title>
                            Vietnam
                        </a>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=popular-related-destinations__content>
                        <div class=popular-related-destinations__img>
                            <img data-src={{ asset('frontend/assets/images/popular1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <a href=# class=popular-related-destinations__title>
                            Vietnam
                        </a>
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

        $itemsLimit = $content->no_of_items;
        $contentType = $content->content_type;
        $resourceIds = [];
        $columnMappings = [
            'city' => [
                'name' => 'name',
                'image' => 'featured_image',
                'alt_text' => 'featured_image_alt_text',
                'slug' => 'slug',
                'route' => 'city.details',
            ],
            'country' => [
                'name' => 'name',
                'image' => 'featured_image',
                'alt_text' => 'featured_image_alt_text',
                'slug' => 'slug',
                'route' => 'country.details',
            ],
            'category' => [
                'name' => 'name',
                'image' => 'featured_image',
                'alt_text' => 'featured_image_alt_text',
                'slug' => 'slug',
                'route' => 'tours.category.details',
            ],
        ];

        $resourceIds = match ($contentType) {
            'city' => $content->city_ids ?? [],
            'country' => $content->country_ids ?? [],
            'category' => $content->category_ids ?? [],
            default => [],
        };

        $resourcesToShow = collect([]);
        if ($contentType && !empty($resourceIds)) {
            $resourcesToShow = match ($contentType) {
                'city' => \App\Models\City::withCount('tours')
                    ->whereIn('id', $resourceIds)
                    ->where('status', 'publish')
                    ->limit($itemsLimit)
                    ->get(),
                'country' => \App\Models\Country::whereIn('id', $resourceIds)
                    ->where('status', 'publish')
                    ->limit($itemsLimit)
                    ->get(),
                'category' => \App\Models\TourCategory::withCount('tours')
                    ->whereIn('id', $resourceIds)
                    ->where('status', 'publish')
                    ->limit($itemsLimit)
                    ->get(),
                default => collect([]),
            };
        }
        $columns = $columnMappings[$contentType] ?? [];
    @endphp
    <div class="popular-related-destinations section-padding"
        style="background-color: {{ $backgroundColor ?? 'transparent' }}">
        <div class=container>
            <div class="section-content mb-5">
                <h2 class=heading style="color: {{ isset($content->title_color) ? $content->title_color : '' }};">
                    {{ $content->title }} </h2>
            </div>
            <div
                class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'four-items-slider' : 'row-cols-1 row-cols-md-2row-cols-xl-3' }}">
                @foreach ($resourcesToShow as $resource)
                    <div class=col-md-3>
                        <a href="{{ route($columns['route'], $resource->{$columns['slug']}) }}"
                            class=popular-related-destinations__content>
                            <div class=popular-related-destinations__img>
                                <img data-src={{ asset($resource->{$columns['image']} ?? 'admin/assets/images/placeholder.png') }}
                                    alt="{{ $resource->{$columns['alt_text']} }}" class="imgFluid lazy">
                            </div>
                            <div class=popular-related-destinations__title>
                                {{ $resource->{$columns['name']} }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
