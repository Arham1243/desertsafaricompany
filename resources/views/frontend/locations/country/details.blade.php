@extends('frontend.layouts.main')

@php
    $seo = $item->seo ?? null;
    $jsonContent = json_decode($item->json_content, true) ?? null;
    $newsContent = $jsonContent['news_section'] ?? null;
    $headingTitle =
        isset($jsonContent['h1_title_text']['title']) && $jsonContent['h1_title_text']['title']
            ? $jsonContent['h1_title_text']['title']
            : null;
    $headingSubtitle =
        isset($jsonContent['h1_title_text']['subtitle']) && $jsonContent['h1_title_text']['subtitle']
            ? $jsonContent['h1_title_text']['subtitle']
            : null;
    $newsContent = $jsonContent['news_section'] ?? null;
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
                <div class="col-md-8">
                    <div class="location1-content__section--details">
                        <div class="location1-content__section--heading">
                            <h1 class="heading">
                                @if ($headingTitle || $headingSubtitle)
                                    <div class="mainHeading">{{ $headingTitle }}</div>
                                    @if ($headingSubtitle)
                                        <div class="subHeading">{{ $headingSubtitle }}</div>
                                    @endif
                                @endif
                            </h1>
                        </div>
                        <div class="my-3">
                            <div class="tour-content__details " data-show-more>
                                <div class="editor-content line-clamp" data-show-more-content
                                    @if ($item->content_line_limit > 0) style="-webkit-line-clamp: {{ $item->content_line_limit }};" @endif>
                                    {!! $item->content !!}
                                </div>
                                @if ($item->content_line_limit > 0)
                                    <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn
                                        more-text="Read more" less-text='Read less'>
                                        Read more</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $sectionContent = json_decode($item->section_content);
                    $guideContent = $sectionContent->guide ?? null;
                @endphp
                @if (isset($guideContent->is_enabled) && $guideContent->is_enabled === '1')
                    <div class="col-md-4">
                        <div class="loaction-guide"
                            @if (!empty($guideContent->background_color)) style="background: {{ $guideContent->background_color }};" @endif>
                            <div class="loaction-guide-content">
                                <div class="loaction-guide-heading"
                                    @if (!empty($guideContent->title_color)) style="color: {{ $guideContent->title_color }};" @endif>
                                    {{ $guideContent->title ?? '' }}
                                </div>
                                <div class="loaction-guide-title"
                                    @if (!empty($guideContent->subtitle_color)) style="color: {{ $guideContent->subtitle_color }};" @endif>
                                    {{ $guideContent->subtitle ?? '' }}
                                </div>
                                <div class="loaction-guide-pra mb-0"
                                    @if (!empty($guideContent->description_color)) style="color: {{ $guideContent->description_color }};" @endif>
                                    {{ $guideContent->description ?? '' }}
                                </div>
                                <div class="loaction-guide-pra fw-bold mt-1 "
                                    @if (!empty($guideContent->last_line_color)) style="color: {{ $guideContent->last_line_color }};" @endif>
                                    {{ $guideContent->last_line ?? '' }}
                                </div>
                                @if (isset($guideContent->is_button_enabled) && $guideContent->is_button_enabled === '1')
                                    <div class="loaction-guide-btn">
                                        @php
                                            $finalLink = null;

                                            if (
                                                $guideContent->btn_link_type === 'category' &&
                                                $guideContent->btn_link_category_id
                                            ) {
                                                $btnCategory = $categories->firstWhere(
                                                    'id',
                                                    $guideContent->btn_link_category_id,
                                                );

                                                if ($btnCategory) {
                                                    $routeParams = [$btnCategory->country->iso_alpha2 ?? 'no-country'];

                                                    if ($btnCategory->city) {
                                                        $routeParams[] = $btnCategory->city->slug;
                                                    }

                                                    $routeParams[] = $btnCategory->slug;

                                                    $finalLink = route('tours.category.details', $routeParams);
                                                }
                                            } else {
                                                $finalLink = sanitizedLink($guideContent->btn_link);
                                            }
                                        @endphp
                                        <a href="{{ $finalLink ?? 'javascript:void(0)' }}" target="_blank"
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
        $category_block = $jsonContent['category_block'] ?? null;
        $category_block_category_ids = isset($category_block['category_ids'])
            ? $category_block['category_ids']
            : [null];
        $category_block_categories = $categories->whereIn('id', $category_block_category_ids);

        $category_block_2 = $jsonContent['category_block_2'] ?? null;
        $category_block_category_ids_2 = isset($category_block_2['category_ids'])
            ? $category_block_2['category_ids']
            : [null];
        $category_block_2_categories = $categories->whereIn('id', $category_block_category_ids_2);

        $first_tour_block = $jsonContent ? $jsonContent['first_tour_block'] : null;
        $first_tour_block_tour_ids = $first_tour_block['tour_ids'] ?? [];
        $first_tour_block_tours = $tours->whereIn('id', $first_tour_block_tour_ids);

        $second_tour_block = $jsonContent ? $jsonContent['second_tour_block'] : null;
        $second_tour_block_tour_ids = $second_tour_block['tour_ids'] ?? [];
        $second_tour_block_tours = $tours->whereIn('id', $second_tour_block_tour_ids);
    @endphp


    @if (isset($category_block['is_enabled']) &&
            (int) $category_block['is_enabled'] === 1 &&
            $category_block_categories->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-md-12">
                        @if (isset($category_block['heading_enabled']) && $category_block['heading_enabled'] === '1')
                            <div class="section-content">
                                <h2 class="subHeading block-heading">
                                    {{ $category_block['heading'] ?? '' }}
                                </h2>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    @foreach ($category_block_categories as $category_block_category)
                        <div class="col-md-4">
                            <x-category-card :category="$category_block_category" style="style1" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if (isset($first_tour_block['is_enabled']) &&
            $first_tour_block['is_enabled'] === '1' &&
            $first_tour_block_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                @if (isset($first_tour_block['heading_enabled']) && (int) $first_tour_block['heading_enabled'] === 1)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="section-content">
                                <h2 class="subHeading block-heading">
                                    {{ $first_tour_block['heading'] ?? '' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                @endif
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

    @if (isset($second_tour_block['is_enabled']) &&
            $second_tour_block['is_enabled'] === '1' &&
            $second_tour_block_tours->isNotEmpty())
        <div class="my-5">
            <div class="container">
                @if (isset($second_tour_block['heading_enabled']) && (int) $second_tour_block['heading_enabled'] === 1)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="section-content">
                                <h2 class="subHeading block-heading">
                                    {{ $second_tour_block['heading'] ?? '' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    @foreach ($second_tour_block_tours as $second_tour_block_tour)
                        <div class="col-md-4">
                            <x-tour-card :tour="$second_tour_block_tour" style="style2" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif



    @if (isset($category_block_2['is_enabled']) &&
            (int) $category_block_2['is_enabled'] === 1 &&
            $category_block_2_categories->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-12">
                        @if (isset($category_block_2['heading_enabled']) && $category_block_2['heading_enabled'] === '1')
                            <div class="section-content">
                                <h2 class="subHeading block-heading">
                                    {{ $category_block_2['heading'] ?? '' }}
                                </h2>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    @foreach ($category_block_2_categories as $category_block_2_category)
                        <div class="col-md-6">
                            <x-category-card :category="$category_block_2_category" style="style2" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @php
        $faqContent = $jsonContent['faq_section'] ?? null;
        $faqs = [];

        if ($faqContent && isset($faqContent['is_enabled'])) {
            $questions = $faqContent['question'] ?? [];
            $answers = $faqContent['answer'] ?? [];

            foreach ($questions as $index => $question) {
                $faqs[] = (object) [
                    'question' => $question,
                    'answer' => $answers[$index] ?? '',
                ];
            }
        }
    @endphp
    @if (!empty($faqs) && (int) $faqContent['is_enabled'] === 1)
        <div class="faqs faqs-category my-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="section-content">
                            <h2 class="subHeading block-heading">
                                FAQS
                            </h2>
                        </div>
                    </div>
                </div>

                @foreach ($faqs as $faq)
                    <div class="faqs-single accordian">
                        <div class="faqs-single__header accordian-header">
                            <div class="faq-icon"><i class="bx bx-plus"></i></div>
                            <div class="tour-content__title">{{ $faq->question }}</div>
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

    @if (isset($newsContent['is_enabled']) && (int) $newsContent['is_enabled'] === 1)
        <div class="latest-stories my-5">
            <div class=container>
                <div class="section-content mb-4 pb-1">
                    <div class=latest-stories__title style="color:{{ $newsContent['title_text_color'] ?? '' }};">
                        {{ $newsContent['title'] ?? '' }}</div>
                    <h2 class="subHeading block-heading" style="color:{{ $newsContent['subTitle_text_color'] ?? '' }};">
                        {{ $newsContent['subTitle'] ?? '' }}
                    </h2>
                </div>
                @php
                    $featured_news = $news->find($newsContent['featured_news_id'])->first();
                    $news_list = $news->whereIn('id', $newsContent['news_list_ids'] ?? []);
                @endphp

                <div class="row">
                    <div class=col-md-7>
                        @if ($featured_news)
                            <div class=Desti-Pract__details>
                                <a href="{{ buildNewsDetailUrl($featured_news) }}" class=Desti-Pract__img>
                                    <img data-src="{{ asset($featured_news->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                        alt="{{ $featured_news->feature_image_alt_text }}" class="imgFluid lazy"
                                        loading="lazy">
                                </a>
                                <div class=Desti-Pract__content>
                                    <div class="sub-title">
                                        {{ $featured_news->category->name ?? '' }}
                                    </div>
                                    <a href="{{ buildNewsDetailUrl($featured_news) }}"
                                        @if (strlen($featured_news->title ?? '') > 19) data-tooltip="tooltip" @endif
                                        title="{{ $featured_news->title ?? '' }}"
                                        class="Desti-Pract__title line-clamp-1">{{ $featured_news->title ?? '' }}</a>
                                    <div class="date">{{ formatDate($featured_news->created_at) }}</div>
                                    <div class="editor-content">
                                        {!! $featured_news->content ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="section-content">
                                <div class="heading">No featured news available.</div>
                            </div>
                        @endif
                    </div>
                    <div class=col-md-5>
                        @if ($news_list->isNotEmpty())
                            @foreach ($news_list as $news)
                                <div class=Desti-Pract__activities>
                                    <div class=activities-details>
                                        <a href="{{ buildNewsDetailUrl($news) }}" class=activities-img>
                                            <img data-src="{{ asset($news->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                alt="{{ $news->feature_image_alt_text }}" class="imgFluid lazy"
                                                loading="lazy">
                                        </a>
                                        <div class=activities-content>
                                            <p><b>{{ $news->category->name ?? '' }}</b></p>
                                            <a href="{{ buildNewsDetailUrl($news) }}">{{ $news->title ?? '' }}</a>
                                            <p>{{ formatDate($news->created_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="section-content">
                                <div class="heading">No news available.</div>
                            </div>
                        @endif
                    </div>
                </div>
                @if (isset($newsContent['is_button_enabled']) && (int) $newsContent['is_button_enabled'] === 1)
                    <button
                        style="background: {{ $newsContent['btn_background_color'] ?? '' }};color:{{ $newsContent['btn_text_color'] ?? '' }};"
                        class="primary-btn primary-btn--center mt-4">{{ $newsContent['btn_text'] ?? '' }}</button>
                @endif
            </div>
        </div>
    @endif

    @if ($relatedCities->isNotEmpty())
        <div class="my-5">
            <div class="container">
                <div class="latest-stories__details">
                    <div class="section-content">
                        <h2 class="subHeading">
                            {{ $item->name }} and beyond
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
