@extends('frontend.layouts.main')
@section('content')
    @php
        $is_enabled_blogs_you_may_also_like = $settings->get('is_enabled_blogs_you_may_also_like')
            ? (int) $settings->get('is_enabled_blogs_you_may_also_like') === 1
            : false;
    @endphp
    <div class="blog-details section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-{{ $is_enabled_blogs_you_may_also_like ? 8 : 12 }}">
                    <div class="stories-content">
                        <div class="stories-content__img">
                            <img src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                alt="{{ $blog->featured_image_alt_text ?? 'Image' }}" class="imgFluid" loading="lazy">
                        </div>

                        <ul class="stories-content__details">
                            <li>
                                <span><i class='bx bxs-calendar'></i></span>
                                <span>{{ $blog->created_at ? $blog->created_at->format('d-M-Y') : 'Date not available' }}</span>
                            </li>
                            <li>
                                <span><i class='bx bxs-folder'></i></span>
                                <span>{{ $blog->category->name ?? 'Uncategorized' }}</span>
                            </li>
                        </ul>

                        <div class="stories-content__title">{{ $blog->title ?? 'Title not available' }}</div>

                        <div class="stories-content__desc">
                            {{ $blog->short_description ?? 'Short description not available' }}</div>

                        @if ($blog->content)
                            <div class="editor-content">{!! $blog->content !!}</div>
                        @endif
                    </div>
                </div>
                @if ($is_enabled_blogs_you_may_also_like)
                    <div class="col-md-4">
                        <div class="you-may-also-like">
                            <div class="section-content">
                                <h2 class="subHeading block-heading">
                                    You may also like
                                </h2>
                            </div>
                            @foreach ($relatedBlogs as $blog)
                                <div class="Desti-Pract__activities">
                                    <div class="activities-details">
                                        <a href="{{ buildBlogDetailUrl($blog) }}" class="activities-img"
                                            style="    flex: 0.4;">
                                            <img data-src="{{ asset($blog->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                alt="{{ $blog->feature_image_alt_text }}" class="imgFluid lazy"
                                                loading="lazy">
                                        </a>
                                        <div class="activities-content">
                                            <p><b>{{ $blog->category->name ?? '' }}</b></p>
                                            <a class="line-clamp-1"
                                                href="{{ buildBlogDetailUrl($blog) }}">{{ $blog->title ?? '' }}</a>
                                            <p>{{ formatDate($blog->created_at) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
