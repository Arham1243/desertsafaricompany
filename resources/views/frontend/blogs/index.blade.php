@extends('frontend.layouts.main')
@section('content')
    <div class="mt-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @if ($defaultCountry)
                        <li class="breadcrumb-item active" aria-current="page">{{ $defaultCountry->name }}</li>
                    @endif
                </ol>
            </nav>
        </div>
        @if ($settings->get('listing_heading_enabled') && (int) $settings->get('listing_heading_enabled') === 1)
            <div class="container">
                <div class="tour-content__header section-content">
                    <h1 class="heading heading--lg mb-0"
                        @if ($settings->get('listing_heading_color')) style="color: {{ $settings->get('listing_heading_color') }}" @endif>
                        {{ $settings->get('listing_heading_text') }}
                    </h1>
                </div>
            </div>
        @endif
        @if ($settings->get('listing_banner_enabled') && (int) $settings->get('listing_banner_enabled') === 1)
            <div class="tour-details_banner blogs-banner">
                <div class=tour-details_img>
                    <img data-src="{{ asset($settings->get('listing_banner_image') ?? 'frontend/assets/images/placeholder.png') }}"
                        alt='{{ $settings->get('listing_banner_image_alt_text') }}' class='imgFluid lazy' loading='lazy'>
                </div>
            </div>
        @endif
        <div class=" mt-4">
            <div class="container">
                <div class="filter-sort-container justify-content-end" x-data="{
                    filterType: '{{ request('category') ? 'categories' : (request('country') ? 'countries' : '') }}',
                    category: '{{ request('category') ?? '' }}',
                    country: '{{ request('country') ?? '' }}',
                    sort: '{{ request('sort') ?? '' }}',
                    buildUrl() {
                        let params = []
                        if (this.filterType === 'categories' && this.category) params.push('category=' + this.category)
                        if (this.filterType === 'countries' && this.country) params.push('country=' + this.country)
                        if (this.sort) params.push('sort=' + this.sort)
                        return '{{ route('frontend.blogs.index') }}' + (params.length ? '?' + params.join('&') : '')
                    }
                }" x-init="$watch('category', value => { if (filterType === 'categories' && value) window.location = buildUrl() })
                $watch('country', value => { if (filterType === 'countries' && value) window.location = buildUrl() })
                $watch('sort', value => { if (value) window.location = buildUrl() })">
                    <div class="filter-group">
                        <label for="filter-type">Filter by</label>
                        <div class="custom-select-wrapper">
                            <select id="filter-type" x-model="filterType">
                                <option value="" selected>Select</option>
                                <option value="countries">Countries</option>
                                <option value="categories">Categories</option>
                            </select>
                            <span class="select-arrow"></span>
                        </div>
                    </div>

                    <div class="filter-group" x-show="filterType === 'categories'" x-transition>
                        <label for="filter-category">Select Category</label>
                        <div class="custom-select-wrapper">
                            <select id="filter-category" x-model="category">
                                <option value="" selected>Select</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow"></span>
                        </div>
                    </div>

                    <div class="filter-group" x-show="filterType === 'countries'" x-transition>
                        <label for="filter-country">Select Country</label>
                        <div class="custom-select-wrapper">
                            <select id="filter-country" x-model="country">
                                <option value="" selected>Select</option>
                                @foreach ($countries as $c)
                                    <option value="{{ $c->iso_alpha2 }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <span class="select-arrow"></span>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="sort-by">Sort by</label>
                        <div class="custom-select-wrapper">
                            <select id="sort-by" x-model="sort">
                                <option value="" selected>Select</option>
                                <option value="newest">Newest</option>
                                <option value="oldest">Oldest</option>
                                <option value="a_to_z">A to Z</option>
                                <option value="z_to_a">Z to A</option>
                            </select>
                            <span class="select-arrow"></span>
                        </div>
                    </div>

                    <template x-if="category || country || sort">
                        <div class="filter-group">
                            <a href="{{ route('frontend.blogs.index') }}" class="themeBtn-round">Clear Filters</a>
                        </div>
                    </template>
                </div>

                <div class="blog-banner__btns py-4 mb-2 position-relative">
                    <button id="arrowLeft" class="arrow-btn start-0" onclick="scrollList(-200)">
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <ul id="blogsScroller" class="d-flex overflow-auto flex-nowrap scroll-smooth no-scrollbar">
                        <li class="flex-shrink-0">
                            <a href="{{ route('frontend.blogs.index') }}"
                                class="themeBtn-round {{ request('city') ? '' : 'active' }}">
                                All
                            </a>
                        </li>
                        @if ($defaultCountry->cities->isNotEmpty())
                            @foreach ($defaultCountry->cities->filter(fn($city) => $city->blogs()->exists()) as $city)
                                @php
                                    $query = request()->all();
                                    $query['city'] = $city->id;
                                @endphp
                                <li class="flex-shrink-0">
                                    <a href="{{ route('frontend.blogs.index', $query) }}"
                                        class="themeBtn-round {{ request('city') == $city->id ? 'active' : '' }}">
                                        {{ $city->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <button id="arrowRight" class="arrow-btn end-0" onclick="scrollList(200)">
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-md-3">
                            <div class="main-blog__card">
                                <a href="{{ buildBlogDetailUrl($blog) }}" class="blog__card-img">
                                    <img data-src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                        alt="{{ $blog->feature_image_alt_text ?? '' }}" class='imgFluid lazy'
                                        loading='lazy'>
                                </a>
                                <div class="main-blog__content">
                                    <div class="main-blog__heading">
                                        {{ $blog->title ?? '' }}
                                    </div>
                                    <div class="main-blog__title">
                                        {{ $blog->city->name ?? '' }}
                                    </div>
                                    <p class="main-blog__pra">
                                        {{ $blog->short_description ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="pagination mt-4">
            {{ $blogs->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
@push('css')
    <script defer src="{{ asset('admin/assets/js/alpine.min.js') }}"></script>
    <style>
        ol.breadcrumb {
            font-weight: 600;
        }

        /* scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }


        .scroll-smooth {
            scroll-behavior: smooth;
        }

        .arrow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #fff;
            color: #000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            transition: box-shadow .2s;
        }

        .arrow-btn:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35);
        }

        .arrow-btn i {
            font-size: 20px;
            color: #000;
        }

        /* scrollbar */
    </style>
@endpush
@push('js')
    <script>
        const scroller = document.getElementById('blogsScroller');
        const arrowLeft = document.getElementById('arrowLeft');
        const arrowRight = document.getElementById('arrowRight');

        function updateArrows() {
            const maxScrollLeft = scroller.scrollWidth - scroller.clientWidth;

            arrowLeft.style.display = scroller.scrollLeft > 0 ? 'flex' : 'none';
            arrowRight.style.display = scroller.scrollLeft >= maxScrollLeft - 1 ? 'none' : 'flex';
        }

        function scrollList(offset) {
            scroller.scrollBy({
                left: offset,
                behavior: 'smooth'
            });
            setTimeout(updateArrows, 300);
        }

        scroller.addEventListener('scroll', updateArrows);
        window.addEventListener('resize', updateArrows);
        document.addEventListener('DOMContentLoaded', updateArrows);
    </script>
@endpush
