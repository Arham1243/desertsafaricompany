@extends('frontend.layouts.main')
@section('content')
    <div class="blog-details section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="blog-details__mainHeading">
                        {{ $blog->title }}
                    </h1>
                    <p>{{ $blog->short_description }}</p>
                    <div class="post-content">
                        <div class="post-content__img">
                            <img src="{{ asset($blog->featured_image ?? 'assets/images/placeholder.png') }}"
                                alt='{{ $blog->feature_image_alt_text }}' class='imgFluid' loading='lazy'>
                        </div>
                    </div>
                    @if ($blog->content)
                        <div class="my-3">
                            <div class="tour-content__details" data-show-more>
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
                    @endif
                </div>

                <div class="col-md-4">
                    @if ($blog->top_highlighted_tour_id)
                        @php
                            $tour = $tours->find($blog->top_highlighted_tour_id);
                        @endphp
                        <div class="availability-frame">
                            <div class="availability-frame__deatils">
                                <div class="availability-frame__img">
                                    <img src="{{ asset($tour->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                        alt='{{ $tour->feature_image_alt_text }}' class='imgFluid' loading='lazy'>
                                </div>
                                <div class="availability-frame__content">
                                    <div class="availability-title">
                                        {{ $tour->title }}
                                    </div>
                                    <div class="card-rating">
                                        <x-star-rating :rating="$tour->average_rating" />
                                        @if ($tour->reviews->count() > 0)
                                            <span>
                                                {{ $tour->reviews->count() }}
                                                Review{{ $tour->reviews->count() > 1 ? 's' : '' }}</span>
                                        @else
                                            <span>No reviews yet</span>
                                        @endif
                                    </div>
                                    <div class="priceLabel__no-deal">
                                        From {{ formatPrice($tour->tour_lower_price) }} per person
                                    </div>
                                    <div class="availability-frame__btn">
                                        <a href="{{ buildTourDetailUrl($tour) }}" class="app-btn mt-4 w-100 themeBtn">Book
                                            Now</a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    @endif

                    <div class="you-may-also-like">
                        <h2>
                            You may also like
                        </h2>
                        @foreach ($relatedBlogs as $blog)
                            <div class="blogDet-card mt-4 mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ buildBlogDetailUrl($blog) }}" class="blogDet-card__img">
                                            <img src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                                alt='{{ $blog->feature_image_alt_text }}' class='imgFluid' loading='lazy'>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="blogDet-card__content">
                                            <a href="{{ buildBlogDetailUrl($blog) }}">
                                                <h5 class="blogDet-card__title">{{ $blog->title }}</h5>
                                            </a>
                                            <div class="blogDet-card__pra">
                                                <p>{{ $blog->short_description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
