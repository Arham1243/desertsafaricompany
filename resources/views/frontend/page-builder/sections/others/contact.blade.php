@if (!$content)
    <div class="contact-sec">
        <div class="container">
            <div class="contact-sec__main">
                <div class="contact-sec__text">
                    <div class="heading">
                        Tripviz Contact
                    </div>
                    <div class="sm-text">
                        Got Questions ?
                        <span> Call us 24/7</span>
                    </div>
                </div>
                <div class="contact-sec__info">
                    <div class="info-content">
                        <a href="#">
                            <i class="bx bx-phone"></i>
                            +90 552 001 08 40
                        </a>
                    </div>
                    <div class="info-content">
                        <a href="#">
                            <i class="bx bx-envelope"></i>
                            contact@tripviz.com
                        </a>

                    </div>
                </div>
                <div class="contact-sec__social">
                    <div class="social-content">
                        <div class="heading">
                            Social Media
                        </div>
                        <div class="sm-text">
                            Follow Us <span class=thin><img alt="" class="darrow2 imgFluid lazy"
                                    data-src={{ asset('frontend/assets/images/darrow.webp') }} width=37.19
                                    height=7.56></span>
                        </div>
                    </div>
                    <ul class="icons">
                        <li><a target=_blank aria-label="facebook" href=javascript:void(0)><i
                                    class='bx bxl-facebook'></i></a></li>
                        <li><a target=_blank aria-label="instagram" href=javascript:void(0)><i
                                    class="bx bxl-instagram"></i></a></li>
                        <li><a target=_blank aria-label="twitter" href=javascript:void(0)><i
                                    class="bx bxl-twitter"></i></a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@else
    <div class="contact-sec">
        <div class="container">
            <div class="contact-sec__main">
                <div class="contact-sec__text">
                    <div class="heading">
                        {{ $content->title ?? '' }}
                    </div>
                    @php
                        $subTitle = $content->subTitle ?? '';
                        $words = explode(' ', $subTitle);
                        $lastThree = array_splice($words, -3);
                    @endphp

                    <div class="sm-text">
                        {!! implode(' ', $words) !!}
                        <span>{!! implode(' ', $lastThree) !!}</span>
                    </div>
                </div>
                <div class="contact-sec__info">
                    <div class="info-content">
                        <a href="tel:{{ $content->phone ?? '' }}">
                            <i class="bx bx-phone"></i>
                            {{ $content->phone ?? '' }}
                        </a>
                    </div>
                    <div class="info-content">
                        <a href="mailto:{{ $content->email ?? '' }}">
                            <i class="bx bx-envelope"></i>
                            {{ $content->email ?? '' }}
                        </a>

                    </div>
                </div>
                <div class="contact-sec__social">
                    <div class="social-content">
                        <div class="heading">
                            Social Media
                        </div>
                        <div class="sm-text">
                            Follow Us <span class=thin><img alt="" class="darrow2 imgFluid lazy"
                                    data-src={{ asset('frontend/assets/images/darrow.webp') }} width=37.19
                                    height=7.56></span>
                        </div>
                    </div>
                    <ul class="icons">
                        @foreach ($content->social->url as $i => $social)
                            @php
                                $icon = $content->social->icon[$i];
                                $url = $content->social->url[$i];
                            @endphp
                            <li><a target=_blank href="{{ sanitizedLink($url) }}"><i
                                        class='{{ $icon }}'></i></a></li>
                        @endforeach
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endif
