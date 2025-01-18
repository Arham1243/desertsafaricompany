@extends('frontend.layouts.main')
@section('content')
    <div class="header-form" id="tour-listing.php">
        <div class="container">
            <div class="row">
                <div class="for-generic ">
                    <form action="#" class="input-details generic-form">
                        <i class='bx bx-search'></i>
                        <input type="text" name="" placeholder="Search generic "
                            class="mobile-number-app app-input">
                        <button class="app-btn themeBtn">SEND</button>
                    </form>
                </div>
            </div>


            <div class="header-form__banner mt-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="header-form__title header-banner__heading">
                            <div class="banner-heading">
                                <h1 class="banner-heading banner-alt-heading">
                                    Explore
                                    <div class="bannerMain-title">Top Tours
                                    </div>
                                </h1>
                            </div>
                            <div class="highlights-item__container">
                                <div class="highlights-item__icon">
                                    <i class='bx bxs-purchase-tag-alt'></i>
                                </div>
                                <div class="highlights-item__pra">
                                    <p>Booked 3,000+ times last week</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="header-form__img">
                            <img src="{{ asset('frontend/assets/images/49.webp') }}" alt='image' class='imgFluid'
                                loading='lazy'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    @if ($tours->isNotEmpty())
        <div class="section-padding">
            <div class="container">
                <div class="activity-sorting-block">
                    <div class="search-header__activity">
                        <div class="activities-found">
                            {{ count($tours) }} activities found
                            <div class="activities-found__icon">
                                <i class='bx bxs-error-circle'></i>
                            </div>

                        </div>

                        {{-- <div class="sort-by">
                            <div class="sort-by__title">
                                Sort by :
                            </div>
                            <label class="dropdown-label">
                                <select class="dropdown-select">
                                    <option value="recommended">Recommended</option>
                                </select>
                            </label>
                        </div> --}}
                    </div>
                </div>
                <div class="row four-items-slider pt-2">
                    @foreach ($tours as $tour)
                        <div class="col">
                            <div class=card-content>
                                <a href={{ route('tours.details', $tour->slug) }} class=card_img>
                                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                        loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                Top pick
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=tour-activity-card__details>
                                    <div class=vertical-activity-card__header>
                                        @if ($tour->category)
                                            <div><span> {{ $tour->category->name }}</span></div>
                                        @endif
                                        <div class="tour-activity-card__details--title">{{ $tour->title }}</div>
                                    </div>
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                    <div class="baseline-pricing__value baseline-pricing__value--high">
                                        <p class=baseline-pricing__from>
                                            <span class="baseline-pricing__from--text receive">Receive voucher
                                                instantly</span>
                                        </p>
                                    </div>
                                    <div class="baseline-pricing__value baseline-pricing__value--high">
                                        <p class=baseline-pricing__from>
                                            @if ($tour->formated_price_type)
                                                <span
                                                    class="baseline-pricing__from--value green">{{ $tour->formated_price_type }}</span>
                                            @else
                                                <span class=baseline-pricing__from--text>From </span>
                                                <span class="baseline-pricing__from--value green">
                                                    {{ formatPrice($tour->regular_price) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection
