@if (!$content)
    <div class="banner banner--shape" style="background-color: #fff">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="banner-content">
                        <div class="banner-heading">
                            <h1 class="bannerMain-title">
                                <div class="title">
                                    WE HELP TO FIND YOUR NEXT</div>
                                <div class="subTitle subTitle--lg">
                                    THRILLING ADVENTURE
                                </div>
                                <div class="subTitle subTitle--sm">
                                    Unforgettable memories with "Smile At Every Mile"
                                </div>
                            </h1>
                            <a href="#" class="primary-btn mt-3">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="banner-img">
                        <img data-src="{{ asset('frontend/assets/images/baloon.webp') }}" alt="image"
                            class="imgFluid lazy" width="345.89" height="186">
                    </div>
                </div>
                <div class="col-md-12">
                    <form action="" class="banner-search">
                        <i class="bx bx-search"></i>
                        <input name="location" placeholder="Where are you going?" class="banner-search__input">
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="banner-rating">
                        <div class="banner-rating__custom">
                            <img src="{{ asset('frontend/assets/images/google.png') }}" alt="Google"
                                class="banner-rating__platform">
                            <div class="banner-rating__stars">
                                <i class="banner-rating__star bx bxs-star" style="color:#fec10b"></i>
                                <i class="banner-rating__star bx bxs-star" style="color:#fec10b"></i>
                                <i class="banner-rating__star bx bxs-star" style="color:#fec10b"></i>
                                <i class="banner-rating__star bx bxs-star" style="color:#fec10b"></i>
                                <i class="banner-rating__star bx bxs-star" style="color:#fec10b"></i>
                            </div>
                            <div class="banner-rating__info">5 Stars</div>
                        </div>
                        <div class="banner-rating__reviews">
                            <div class="banner-rating__avatars">
                                <img src="https://i.pravatar.cc/150?img=51" alt="Reviewer 1"
                                    class="banner-rating__avatar">
                                <img src="https://i.pravatar.cc/150?img=52" alt="Reviewer 2"
                                    class="banner-rating__avatar">
                                <img src="https://i.pravatar.cc/150?img=53" alt="Reviewer 3"
                                    class="banner-rating__avatar">
                                <img src="https://i.pravatar.cc/150?img=54" alt="Reviewer 4"
                                    class="banner-rating__avatar">
                            </div>
                            <div class="banner-rating__info">196 Reviews</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=destinations>
        <div class=container>
            <div class="row justify-content-between">
                <div class=col-md-4>
                    <div class=destinations-content>
                        <div class=dst1>TOP DESTINATIONS</div>
                        <div class=dst2>
                            Meet With Carefully Selected Destinations!
                            <div class=darrow>
                                <img data-src={{ asset('frontend/assets/images/darrow.webp') }} alt=image
                                    class="imgFluid lazy" width=100 height=20.36>
                            </div>
                        </div>
                    </div>
                </div>
                <div class=col-md-8>
                    <div class="row five-items-slider">
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/dubai-600.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    dubai
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/belek1.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    belek
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/antalya-600.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    antalya
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/alanya2.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    alanya
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/side.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    manavgat
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                        <div class=col-md>
                            <div class=dst-card>
                                <div class=destinations-img>
                                    <img data-src={{ asset('frontend/assets/images/side.webp') }} alt=image
                                        class="imgFluid lazy">
                                </div>
                                <div class=dst-location>
                                    manavgat
                                </div>
                                <div class=dst-num>3</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @if ($content->background_type === 'normal_v1_right_side_image')
        <div class="banner banner--shape" style="background-color: #fff">
            @include('frontend.page-builder.sections.banners.moto')
            <div class="container">
                <div class="row">
                    @include('frontend.page-builder.sections.banners.content')
                    <div class="col-md-5">
                        <div class="banner-img {{ $content->right_image_position ?? '' }}">
                            <img data-src="{{ asset($content->right_image ?? 'admin/assets/images/placeholder.png') }}"
                                alt="{{ $content->right_image_alt_text ?? 'Banner Right image' }}"
                                class="imgFluid lazy" width="345.89" height="186">
                        </div>
                    </div>
                    @include('frontend.page-builder.sections.banners.search')
                    @include('frontend.page-builder.sections.banners.review')
                </div>
            </div>
        </div>
    @elseif($content->background_type === 'normal_v2_full_screen_background')
        <div class="banner {{ isset($content->background_image_is_banner_overlay_enabled) ? 'banner--overlay' : '' }}">
            @include('frontend.page-builder.sections.banners.moto')
            <img data-src="{{ asset($content->background_image ?? 'admin/assets/images/placeholder.png') }}"
                alt="{{ $content->background_alt_text ?? 'Banner image' }}" class="imgFluid lazy banner__bg">
            <div class="container">
                <div class="row">
                    @include('frontend.page-builder.sections.banners.content')
                    @include('frontend.page-builder.sections.banners.search')
                    @include('frontend.page-builder.sections.banners.review')
                </div>
            </div>
        </div>
    @elseif($content->background_type === 'slider_carousel')
        <div class="banner-slider-wrapper">
            <div class="banner-slider one-item-fade-slider">
                @for ($i = 0; $i < 4; $i++)
                    @php
                        $background_image =
                            isset($content->carousel_background_images) && !empty($content->carousel_background_images)
                                ? $content->carousel_background_images[$i]
                                : null;
                        $alt_text = isset($content->carousel_alt_text) ? $content->carousel_alt_text[$i] : null;
                    @endphp
                    <div
                        class="banner {{ isset($content->slider_carousel_is_banner_overlay_enabled) ? 'banner--overlay' : '' }}">
                        <img data-src="{{ asset($background_image ?? 'admin/assets/images/placeholder.png') }}"
                            alt="{{ $alt_text ?? 'Banner image' }}" class="imgFluid lazy banner__bg">
                    </div>
                @endfor
            </div>
            <div class="banner">
                @include('frontend.page-builder.sections.banners.moto')
                <div class="container">
                    <div class="row">
                        @include('frontend.page-builder.sections.banners.content')
                        @include('frontend.page-builder.sections.banners.search')
                        @include('frontend.page-builder.sections.banners.review')
                    </div>
                </div>
            </div>
        </div>
    @elseif($content->background_type === 'normal_wave_background')
        @php
            $normalWavebackgroundStyle = '';
            if (
                $content->normal_wave_background_type === 'background_color' &&
                isset($content->normal_wave_background_color)
            ) {
                $normalWavebackgroundStyle = "background-color: {$content->normal_wave_background_color};";
            } elseif (
                $content->normal_wave_background_type === 'background_image' &&
                isset($content->normal_wave_background_image)
            ) {
                $normalWavebackgroundStyle =
                    'background-image: url(' . asset($content->normal_wave_background_image) . ');';
            }
        @endphp
        <div class="banner banner--shape" style="{{ $normalWavebackgroundStyle }}">
            @include('frontend.page-builder.sections.banners.moto')
            <div class="banner-shape" style="--wave-color:{{ $content->normal_background_wave_color ?? '' }};">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
                    viewBox="0 0 1069 233" preserveAspectRatio="none">
                    <path
                        d="M0 0 C0.33 0 0.66 0 1 0 C1 76.23 1 152.46 1 231 C-351.77 231 -704.54 231 -1068 231 C-1068 189.75 -1068 148.5 -1068 106 C-1061.6258648 106 -1056.29609664 106.23000954 -1050.1875 107.5 C-1049.34131104 107.67378174 -1048.49512207 107.84756348 -1047.62329102 108.02661133 C-1011.20350672 115.84549292 -975.41064102 129.19461163 -940.95532227 143.23608398 C-935.68768486 145.3686494 -930.37956641 147.29526141 -925 149.125 C-915.32451729 152.42235862 -905.82681052 156.14475912 -896.3125 159.875 C-876.65711374 167.57160133 -856.94883311 175.03758396 -836.73779297 181.16503906 C-833.59274245 182.12420375 -830.45501943 183.10591885 -827.31640625 184.0859375 C-750.21397875 207.94385253 -668.1114977 219.67059264 -574 201 C-572.30603205 200.64172624 -570.61202602 200.28363244 -568.91796875 199.92578125 C-508.68853224 186.58005984 -453.40822958 155.86112275 -403.625 120.375 C-384.86988298 107.0253175 -369.04642027 101.72539598 -345.76833725 101.69454193 C-343.43951409 101.69130592 -341.1110112 101.67620194 -338.78225708 101.65826416 C-335.46289108 101.63334112 -332.14353877 101.61245296 -328.8241272 101.59463501 C-286.99144636 101.35267617 -246.3928417 99.07451082 -205.06134033 92.3359375 C-203.22746264 92.03706968 -201.39283417 91.74281699 -199.55810547 91.44921875 C-163.34771576 85.58788901 -127.99518174 75.70651988 -94 62 C-93.00822754 61.60135742 -92.01645508 61.20271484 -90.99462891 60.79199219 C-59.04658279 47.83125704 -23.71714223 29.64642779 -1.75 2.1875 C-1.1725 1.465625 -0.595 0.74375 0 0 Z "
                        fill="var(--wave-color)" transform="translate(1068,2)" />
                </svg>

            </div>
            <div class="container">
                <div class="row">
                    @include('frontend.page-builder.sections.banners.content')
                    @include('frontend.page-builder.sections.banners.search')
                    @include('frontend.page-builder.sections.banners.review')
                </div>
            </div>
        </div>
    @elseif ($content->background_type === 'right_image_wave_background')
        @php
            $rightImageWavebackgroundStyle = '';
            if (
                $content->right_image_wave_background_type === 'background_color' &&
                isset($content->right_image_wave_background_color)
            ) {
                $rightImageWavebackgroundStyle = "background-color: {$content->right_image_wave_background_color};";
            } elseif (
                $content->right_image_wave_background_type === 'background_image' &&
                isset($content->right_image_wave_background_image)
            ) {
                $rightImageWavebackgroundStyle =
                    'background-image: url(' . asset($content->right_image_wave_background_image) . ');';
            }
        @endphp

        <div class="banner banner--shape" style="{{ $rightImageWavebackgroundStyle }}">
            @include('frontend.page-builder.sections.banners.moto')
            <div class="banner-shape" style="--wave-color:{{ $content->right_image_background_wave_color ?? '' }};">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"
                    viewBox="0 0 1069 233" preserveAspectRatio="none">
                    <path
                        d="M0 0 C0.33 0 0.66 0 1 0 C1 76.23 1 152.46 1 231 C-351.77 231 -704.54 231 -1068 231 C-1068 189.75 -1068 148.5 -1068 106 C-1061.6258648 106 -1056.29609664 106.23000954 -1050.1875 107.5 C-1049.34131104 107.67378174 -1048.49512207 107.84756348 -1047.62329102 108.02661133 C-1011.20350672 115.84549292 -975.41064102 129.19461163 -940.95532227 143.23608398 C-935.68768486 145.3686494 -930.37956641 147.29526141 -925 149.125 C-915.32451729 152.42235862 -905.82681052 156.14475912 -896.3125 159.875 C-876.65711374 167.57160133 -856.94883311 175.03758396 -836.73779297 181.16503906 C-833.59274245 182.12420375 -830.45501943 183.10591885 -827.31640625 184.0859375 C-750.21397875 207.94385253 -668.1114977 219.67059264 -574 201 C-572.30603205 200.64172624 -570.61202602 200.28363244 -568.91796875 199.92578125 C-508.68853224 186.58005984 -453.40822958 155.86112275 -403.625 120.375 C-384.86988298 107.0253175 -369.04642027 101.72539598 -345.76833725 101.69454193 C-343.43951409 101.69130592 -341.1110112 101.67620194 -338.78225708 101.65826416 C-335.46289108 101.63334112 -332.14353877 101.61245296 -328.8241272 101.59463501 C-286.99144636 101.35267617 -246.3928417 99.07451082 -205.06134033 92.3359375 C-203.22746264 92.03706968 -201.39283417 91.74281699 -199.55810547 91.44921875 C-163.34771576 85.58788901 -127.99518174 75.70651988 -94 62 C-93.00822754 61.60135742 -92.01645508 61.20271484 -90.99462891 60.79199219 C-59.04658279 47.83125704 -23.71714223 29.64642779 -1.75 2.1875 C-1.1725 1.465625 -0.595 0.74375 0 0 Z "
                        fill="var(--wave-color)" transform="translate(1068,2)" />
                </svg>

            </div>
            <div class="container">
                <div class="row">
                    @include('frontend.page-builder.sections.banners.content')
                    <div class="col-md-5">
                        <div class="banner-img {{ $content->right_image_position_background ?? '' }}">
                            <img data-src="{{ asset($content->right_image_background ?? 'admin/assets/images/placeholder.png') }}"
                                alt="{{ $content->right_image_background_alt_text ?? 'Banner Right image' }}"
                                class="imgFluid lazy" width="345.89" height="186">
                        </div>
                    </div>
                    @include('frontend.page-builder.sections.banners.search')
                    @include('frontend.page-builder.sections.banners.review')
                </div>
            </div>
        </div>
    @endif
    @include('frontend.page-builder.sections.banners.destination')
@endif
