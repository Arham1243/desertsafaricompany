<div class="col-md-7">
    <div class="banner-content">
        <div class="banner-heading">
            <h1 class="bannerMain-title">
                <div class="title" style="color: {{ isset($content->title_color) ? $content->title_color : '' }};">
                    {{ $content->title }}</div>
                @if (isset($content->subtitle->title[0]))
                    <div class="subTitle subTitle--lg"
                        style="color: {{ isset($content->subtitle->text_color[0]) ? $content->subtitle->text_color[0] : '' }};">
                        {{ $content->subtitle->title[0] }}
                    </div>
                @endif

                @if (isset($content->subtitle->title[1]))
                    <div class="subTitle subTitle--sm"
                        style="color: {{ isset($content->subtitle->text_color[1]) ? $content->subtitle->text_color[1] : '' }};">
                        {{ $content->subtitle->title[1] }}
                    </div>
                @endif
            </h1>
            @if (isset($content->is_button_enabled))
                <a style="
                {{ $content->btn_background_color ? 'background: ' . $content->btn_background_color . ';' : '' }}
                {{ $content->btn_text_color ? 'color: ' . $content->btn_text_color . ';' : '' }}
            "
                    href="{{ sanitizedLink($content->btn_link) }}" class="primary-btn mt-3"
                    target="_blank">{{ $content->btn_text }} <i class='bx bx-right-arrow-alt'></i></a>
            @endif
        </div>
    </div>
</div>
