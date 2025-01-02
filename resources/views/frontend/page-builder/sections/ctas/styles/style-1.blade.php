    @if (!$content)
        <div class="offers-section section-padding">
            <div class=container>
                <div class=offers-section__details>
                    <img data-src={{ asset('frontend/assets/images/group_tour_desktop_banner_image.webp') }} alt=image
                        class="imgFluid offers-section__img lazy" loading="lazy" height="200">
                    <div class=GroupTourCard_content>
                        <span class=GroupTourCard_title>Bigger Group? Get special offers up to 50% Off!</span>
                        <span class=GroupTourCard_subtitle>We create unforgettable adventures, customised for your
                            group.</span>
                        <div class="GroupTourCard_callBackButton pt-3">
                            <span class="GroupTourCard_text app-btn themeBtn">Get A Callback</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php
            $isBackgroundColor = isset($content->background_type)
                ? $content->background_type === 'background_color'
                : null;
            $isBackgroundImage = isset($content->background_type)
                ? $content->background_type === 'background_image'
                : null;
        @endphp
        <div class="offers-section section-padding">
            <div class=container>
                <div class=offers-section__details
                    style="{{ $isBackgroundColor && $content->background_color ? 'background-color: ' . $content->background_color : '' }}">
                    @if ($isBackgroundImage)
                        <img data-src="{{ asset($content->background_image ?? 'admin/assets/images/placeholder.png') }}"
                            alt="{{ $content->background_image_alt_text ?? 'Cta Background Image' }}"
                            class="imgFluid lazy offers-section__img" loading="lazy" height="200">
                    @endif
                    <div class=GroupTourCard_content>
                        <span class=GroupTourCard_title
                            @if ($content->title_color) style="color: {{ $content->title_color }};" @endif>{{ $content->title ?? '' }}</span>
                        <span class=GroupTourCard_subtitle
                            @if ($content->description_color) style="color: {{ $content->description_color }};" @endif>{{ $content->description ?? '' }}</span>
                        @if (isset($content->is_button_enabled))
                            <div class="GroupTourCard_callBackButton pt-3">
                                <a href="{{ sanitizedLink($content->btn_link) ?? '' }}"
                                    style="
    {{ $content->btn_background_color ? 'background-color: ' . $content->btn_background_color . ';' : '' }}
    {{ $content->btn_text_color ? 'color: ' . $content->btn_text_color . ';' : '' }}
"
                                    target="_blank"
                                    class="GroupTourCard_text app-btn themeBtn">{{ $content->btn_text ?? '' }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
