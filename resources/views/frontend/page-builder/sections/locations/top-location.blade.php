@if (!$content)
    <div class="top-location section-padding">
        <div class=container>
            <div class="section-content mb-4">
                <h2 class=heading>Top Location</h2>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
                <div class="col">
                    <ul class="locations-list">
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="locations-list">
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="locations-list">
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                    </ul>
                </div>
                <div class="col">
                    <ul class="locations-list">
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                        <li class="locations-list__item"><a href=#><span class="name">Alanya</span> <span
                                    class='count'>36</span> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="top-location section-padding"
        style="background-color: {{ $content->background_color ?? 'transparent' }}">
        <div class=container>
            <div class="section-content mb-5">
                <div class=heading
                    style="color: {{ isset($content->title_text_color) ? $content->title_text_color : '' }};">
                    {{ $content->title }}</div>
            </div>

            @php
                $itemsLimit = $content->no_of_items;
                $contentType = $content->content_type;
                $resourceIds = [];
                $columnMappings = [
                    'city' => [
                        'name' => 'name',
                        'image' => 'featured_image',
                        'alt_text' => 'featured_image_alt_text',
                        'slug' => 'slug',
                        'route' => 'locations.city',
                    ],
                    'country' => [
                        'name' => 'name',
                        'image' => 'featured_image',
                        'alt_text' => 'featured_image_alt_text',
                        'slug' => 'slug',
                        'route' => 'locations.country',
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
                $chunkedResources = $resourcesToShow->chunk(ceil($resourcesToShow->count() / 4));
            @endphp

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
                @foreach ($chunkedResources as $chunk)
                    <div class="col">
                        <ul class="locations-list">
                            @foreach ($chunk as $resource)
                                @php
                                    $resourceType = '';
                                    $url = 'javascript:void(0)';
                                    if ($resource instanceof \App\Models\City) {
                                        $resourceType = 'city';
                                        $url = route('locations.city', [
                                            $resource->country->iso_alpha2,
                                            $resource->slug,
                                        ]);
                                    } elseif ($resource instanceof \App\Models\Country) {
                                        $resourceType = 'country';
                                        $url = route('locations.country', [$resource->iso_alpha2, $resource->slug]);
                                    } elseif ($resource instanceof \App\Models\TourCategory) {
                                        $resourceType = 'category';
                                        $url = buildCategoryDetailUrl($resource);
                                    }
                                @endphp
                                <li class="locations-list__item">
                                    <a href="{{ $url }}" target="_blank">
                                        <span class="name" title="{{ $resource->{$columns['name']} }}"
                                            data-tooltip="tooltip">

                                            {{ $resource->{$columns['name']} }}
                                        </span>
                                        @if ($resourceType === 'city' || $resourceType === 'category')
                                            @if ($resource->tours_count > 0)
                                                <span class='count'>
                                                    {{ $resource->tours_count }}
                                                </span>
                                            @endif
                                        @elseif ($resourceType === 'country')
                                            @if ($resource->toursCount() > 0)
                                                <span class='count'>
                                                    {{ $resource->toursCount() }}
                                                </span>
                                            @endif
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
