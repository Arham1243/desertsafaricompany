@extends('frontend.layouts.main')
@section('content')
    @php
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

        <div class="container">
            <div class="tour-content__header section-content">
                <h1 class="heading heading--lg mb-0">
                    {{ $blog->title }}
                </h1>
            </div>
        </div>

        <div class=tour-details_banner>
            <div class=tour-details_img>
                <img data-src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                    alt='{{ $blog->featured_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
            </div>
        </div>
    </div>

    <div class="blog-details mt-4 pt-1 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-{{ $is_enabled_blogs_you_may_also_like ? 8 : 12 }}">
                    <div class="stories-content">
                        <ul class="stories-content__details">
                            <li>
                                <span><i class='bx bxs-calendar'></i></span>
                                <span>{{ $blog->created_at ? $blog->created_at->format('d-M-Y') : 'Date not available' }}</span>
                            </li>
                            <li>
                                <span><i class='bx bxs-purchase-tag'></i></span>
                                <span>{{ $blog->category->name ?? 'Uncategorized' }}</span>
                            </li>
                        </ul>

                        <div class="stories-content__desc mt-4">
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
@push('css')
    <style>
        ol.breadcrumb {
            font-weight: 600;
        }
    </style>
@endpush
