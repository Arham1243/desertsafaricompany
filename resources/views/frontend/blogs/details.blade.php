@extends('frontend.layouts.main')
@section('content')
    @php
        $jsonContent = json_decode($blog->json_content, true) ?? [];
        $is_enabled_blogs_you_may_also_like = $settings->get('is_enabled_blogs_you_may_also_like')
            ? (int) $settings->get('is_enabled_blogs_you_may_also_like') === 1
            : false;
    @endphp

    <div class="mt-3">
        <div class="container">
            <nav aria-label="breadcrumb" class="mt-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @if ($blog->country)
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.country', $blog->country->iso_alpha2) }}">
                                {{ $blog->country->name ?? '' }}
                            </a>
                        </li>
                        <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @endif
                    @if ($blog->city)
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.city', [$blog->city->country->iso_alpha2, $blog->city->slug]) }}">
                                {{ $blog->city->name ?? '' }}
                            </a>
                        </li>
                        <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $blog->slug }}</li>
                </ol>
            </nav>
        </div>
        <div class="blog-details mt-4 pt-1 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="blog-details__mainHeading">
                            {{ $blog->title }}
                        </h1>
                        <div class="post-content">
                            <div class="post-content__img mb-4">
                                <img data-src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt='{{ $blog->featured_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
                            </div>
                            <div class="my-3">
                                <div class="tour-content__details " data-show-more>
                                    <div class="editor-content line-clamp" data-show-more-content
                                        @if ($blog->content_line_limit > 0) style="-webkit-line-clamp: {{ $blog->content_line_limit }};" @endif>
                                        {!! $blog->content !!}
                                    </div>
                                    @if ($blog->content_line_limit > 0)
                                        <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn
                                            more-text="Read more" less-text='Read less'>
                                            Read more</a>
                                    @endif
                                </div>
                            </div>
                            <div class="separator"></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        @php
                            $topFeaturedTourIds = $jsonContent['top_featured_tour_ids'] ?? [];
                            $topFeaturedTours = $tours->whereIn('id', $topFeaturedTourIds);
                        @endphp
                        @if ($topFeaturedTours->isNotEmpty())
                            <div class="one-tour-card-slider">
                                @foreach ($topFeaturedTours as $topFeaturedTour)
                                    <div class="availability-frame">
                                        <div class="availability-frame__deatils">
                                            <div class="availability-frame__img">
                                                <img data-src="{{ asset($topFeaturedTour->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                                    alt="{{ $topFeaturedTour->featured_image_alt_text }}"
                                                    class="imgFluid lazy" loading="lazy">
                                            </div>
                                            <div class="availability-frame__content w-100">
                                                <div class="availability-title">
                                                    {{ $topFeaturedTour->title }}
                                                </div>
                                                <div class="card-rating">
                                                    <x-star-rating :rating="$topFeaturedTour->average_rating" />
                                                    @if ($topFeaturedTour->reviews->count() > 0)
                                                        <span>{{ $topFeaturedTour->reviews->count() }}
                                                            Review{{ $topFeaturedTour->reviews->count() > 1 ? 's' : '' }}</span>
                                                    @else
                                                        <span>No reviews yet</span>
                                                    @endif
                                                </div>

                                                @if ($topFeaturedTour->tour_lowest_price)
                                                    <div class="priceLabel__no-deal">
                                                        From {!! formatPrice($topFeaturedTour->tour_lowest_price) !!} per person
                                                    </div>
                                                @endif

                                                <input id="date" type="date" class="booking-assistant-dropdown">


                                                <div class="availability-frame__btn">
                                                    <a href="{{ buildTourDetailUrl($topFeaturedTour) }}"
                                                        class="w-100 app-btn themeBtn">Book
                                                        Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @php
                            $mayAlsoLike = json_decode($blog->may_also_like, true) ?? [];
                            $enabled = isset($mayAlsoLike['enabled']) ? (int) $mayAlsoLike['enabled'] : 0;
                            $type = $mayAlsoLike['type'] ?? 'category_based';
                            $customIds = $mayAlsoLike['custom_ids'] ?? [];
                        @endphp

                        @if ($mayAlsoLike && (int) $mayAlsoLike['enabled'] === 1)
                            @php
                                if ($type === 'custom') {
                                    $blogsToShow = $allBlogs->whereIn('id', $customIds);
                                } elseif ($type === 'latest') {
                                    $blogsToShow = $allBlogs->sortByDesc('created_at');
                                } else {
                                    $blogsToShow = $allBlogs->where('category_id', $blog->category_id);
                                }
                            @endphp
                            <div class="you-may-also-like mt-4">
                                <div class="section-content">
                                    <h2 class="subHeading block-heading">
                                        You may also like
                                    </h2>
                                </div>
                                @if ($blogsToShow->isNotEmpty())
                                    @foreach ($blogsToShow as $itemBlog)
                                        <div class="blogDet-card blogDet-card-like mt-4 mt-4">
                                            <a href="{{ buildBlogDetailUrl($itemBlog) }}" class="blogDet-card__img">
                                                <img data-src="{{ asset($itemBlog->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                    alt="{{ $itemBlog->feature_image_alt_text }}" class="imgFluid lazy"
                                                    loading="lazy">
                                            </a>

                                            <div class="blogDet-card__content">
                                                <a href="{{ buildBlogDetailUrl($itemBlog) }}"
                                                    class="blogDet-card__title line-clamp-4">
                                                    {{ $itemBlog->title }}
                                                </a>
                                                <div class="blogDet-card__pra line-clamp-7">
                                                    {{ $itemBlog->description }}
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="you-may-also-like mt-4">
                                        <div class="text-document ">
                                            <h3 class="subHeading text-start">No blogs found</h3>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $bottomFeaturedTourIds = $jsonContent['bottom_featured_tour_ids'] ?? [];
        $bottomFeaturedTours = $tours->whereIn('id', $bottomFeaturedTourIds);
    @endphp

    @if ($bottomFeaturedTours->isNotEmpty())
        <div class="one-tour-card-slider">
            @foreach ($bottomFeaturedTours as $bottomFeaturedTour)
                <div class="availability-frame my-5 pt-3 pb-4">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-3">
                            <div class="availability-frame__img availability-frame__img-ver">
                                <img data-src="{{ asset($bottomFeaturedTour->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                    alt="{{ $bottomFeaturedTour->featured_image_alt_text }}" class="imgFluid lazy"
                                    loading="lazy">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="availability-frame__content w-100">
                                <div class="availability-title">
                                    {{ $bottomFeaturedTour->title }}
                                </div>

                                <div class="card-rating">
                                    <x-star-rating :rating="$bottomFeaturedTour->average_rating" />
                                    @if ($bottomFeaturedTour->reviews->count() > 0)
                                        <span>{{ $bottomFeaturedTour->reviews->count() }}
                                            Review{{ $bottomFeaturedTour->reviews->count() > 1 ? 's' : '' }}</span>
                                    @else
                                        <span>No reviews yet</span>
                                    @endif
                                </div>
                                @if ($bottomFeaturedTour->tour_lowest_price)
                                    <div class="priceLabel__no-deal">
                                        From {!! formatPrice($bottomFeaturedTour->tour_lowest_price) !!} per person
                                    </div>
                                @endif
                                <input id="date" type="date" class="booking-assistant-dropdown">
                                <div class="availability-frame__btn">
                                    <a href="{{ buildTourDetailUrl($bottomFeaturedTour) }}"
                                        class="w-100 app-btn themeBtn">Book
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
@push('css')
    <style>
        ol.breadcrumb {
            font-weight: 600;
        }
    </style>
@endpush
