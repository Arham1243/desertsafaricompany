@extends('frontend.layouts.main')
@section('content')
    <div class="blog-details section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="post-content">
                        <div class="post-content__img">
                            <img src="{{ asset($news->featured_image ?? 'assets/images/placeholder.png') }}"
                                alt='{{ $news->feature_image_alt_text }}' class='imgFluid' loading='lazy'>
                        </div>
                        <div class="main-content pt-3">
                            <p class="mb-1">{{ formatDate($news->created_at) }}<i class='bx bxs-circle mx-2'
                                    style="font-size: 8px"></i> <span>{{ $news->category->name ?? '' }}</span></p>
                            <h1 class="blog-details__mainHeading">
                                {{ $news->title }}
                            </h1>
                            <p>{{ $news->short_description }}</p>
                        </div>
                    </div>
                    @if ($news->content)
                        <div class="my-3">
                            <div class="tour-content__details" data-show-more>
                                <div class="editor-content line-clamp" data-show-more-content
                                    @if ($news->content_line_limit > 0) style="-webkit-line-clamp: {{ $news->content_line_limit }};" @endif>
                                    {!! $news->content !!}
                                </div>
                                @if ($news->content_line_limit > 0)
                                    <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn
                                        more-text="Read more" less-text='Read less'>
                                        Read more</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="you-may-also-like">
                        <h2>
                            You may also like
                        </h2>
                        @foreach ($relatedNews as $news)
                            <div class=Desti-Pract__activities>
                                <div class=activities-details>
                                    <a href="{{ buildNewsDetailUrl($news) }}" class="activities-img" style="    flex: 0.4;">
                                        <img data-src="{{ asset($news->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                            alt="{{ $news->feature_image_alt_text }}" class="imgFluid lazy" loading="lazy">
                                    </a>
                                    <div class="activities-content">
                                        <p><b>{{ $news->category->name ?? '' }}</b></p>
                                        <a class="line-clamp-1"
                                            href="{{ buildNewsDetailUrl($news) }}">{{ $news->title ?? '' }}</a>
                                        <p>{{ formatDate($news->created_at) }}</p>
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
