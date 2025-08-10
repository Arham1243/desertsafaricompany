@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
@endphp

@section('content')
    <div class="location-banner1">
        <div class="container-Fluid">
            <div class="location-banner1__img">
                <img data-src="{{ asset($item->banner_image ?? 'frontend/assets/images/placeholder.png') }}"
                    alt='{{ $item->banner_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
            </div>
        </div>
    </div>

    <div class="location1-content__section my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="location1-content__section--details">
                        <div class="location1-content__section--heading">
                            <h2>
                                {{ $item->name }}
                            </h2>
                            <ul class="location1-Breadcrumb">
                                <li><a
                                        href="{{ route('locations.country', $item->country->iso_alpha2) }}">{{ $item->country ? $item->country->name . ',' : '' }}</a>
                                </li>
                                <li><a href="javascript:void(0)">{{ $item->name }}</a></li>
                            </ul>

                        </div>
                        <div class="location1-content__section--pra editor-content">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
                @php
                    $sectionContent = json_decode($item->section_content);
                    $guideContent = $sectionContent->guide ?? null;
                @endphp
                @if (isset($guideContent->is_enabled) && $guideContent->is_enabled === '1')
                    <div class="col-md-5">
                        <div class="loaction-guide"
                            @if (!empty($guideContent->background_color)) style="background: {{ $guideContent->background_color }};" @endif>
                            <div class="loaction-guide-content">
                                <div class="loaction-guide-heading"
                                    @if (!empty($guideContent->title_color)) style="color: {{ $guideContent->title_color }};" @endif>
                                    {{ $guideContent->title }}
                                </div>
                                <div class="loaction-guide-title"
                                    @if (!empty($guideContent->subtitle_color)) style="color: {{ $guideContent->subtitle_color }};" @endif>
                                    {{ $guideContent->subtitle }}
                                </div>
                                <div class="loaction-guide-pra"
                                    @if (!empty($guideContent->description_color)) style="color: {{ $guideContent->description_color }};" @endif>
                                    {{ $guideContent->description }}
                                </div>
                                @if (isset($guideContent->is_button_enabled) && $guideContent->is_button_enabled === '1')
                                    <div class="loaction-guide-btn">
                                        <a href="{{ sanitizedLink($guideContent->btn_link) }}" target="_blank"
                                            class="themeBtn-round"
                                            @php
$btnStyles = [];
                                        if (!empty($guideContent->btn_background_color)) $btnStyles[] = "background: {$guideContent->btn_background_color};";
                                        if (!empty($guideContent->btn_text_color)) $btnStyles[] = "color: {$guideContent->btn_text_color};"; @endphp
                                            @if (count($btnStyles) > 0) style="{{ implode(' ', $btnStyles) }}" @endif>
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
        $jsonContent = json_decode($item->json_content, true) ?? null;
        $first_tour_block = $jsonContent ? $jsonContent['first_tour_block'] : null;
        $first_tour_block_tour_ids = $first_tour_block['tour_ids'] ?? [];
        $first_tour_block_tours = $tours->whereIn('id', $first_tour_block_tour_ids);

        $second_tour_block = $jsonContent ? $jsonContent['second_tour_block'] : null;
        $second_tour_block_tour_ids = $second_tour_block['tour_ids'] ?? [];
        $second_tour_block_tours = $tours->whereIn('id', $second_tour_block_tour_ids);
    @endphp

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
                <div class="row four-items-slider">
                    @foreach ($first_tour_block_tours as $first_tour_block_tour)
                        <div class="col-md-3">
                            <x-tour-card :tour="$first_tour_block_tour" style="style3" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (isset($second_tour_block['is_enabled']) &&
            $second_tour_block['is_enabled'] === '1' &&
            $second_tour_block_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row  mb-3">
                    <div class="col-md-12">
                        <div class="section-content">
                            <h2 class="subHeading">
                                {{ $second_tour_block['heading'] ?? '' }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row three-items-slider">
                    @foreach ($second_tour_block_tours as $second_tour_block_tour)
                        <div class="col-md-3">
                            <x-tour-card :tour="$second_tour_block_tour" style="style3" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($relatedCities->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="latest-stories__details">
                    <div class="section-content">
                        <h2 class="subHeading">
                            {{ ucfirst($item->name) }} and beyond
                        </h2>
                    </div>
                </div>

                <div class="row pt-3">
                    @foreach ($relatedCities as $city)
                        <div class="col-md-3">
                            <div class="blog-more__dest-content">
                                <div class="location1-beyond__img">
                                    <a href="{{ route('locations.city', [$city->country->iso_alpha2, $city->slug]) }}">
                                        <img data-src="{{ asset($city->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
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
