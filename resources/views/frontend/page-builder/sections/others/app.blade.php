@if (!$content)
    <div class=download-app>
        <div class=container>
            <div class=row>
                <div class=col-md-6>
                    <div class=download-app__details>
                        <div class=section-content>
                            <h2 class=heading>
                                Download App
                            </h2>
                        </div>
                        <p>Plan your next adventure with Tripviz and experience hassle-free booking! Whether you prefer
                            to
                            book in advance or go for a last-minute getaway, Tripviz has you covered.</p>
                        <div class=app-details>
                            <div class=code-details>
                                <div class=qr-code>
                                    <img data-src={{ asset('frontend/assets/images/qr.webp') }} alt=image
                                        class="imgFluid lazy" loading="lazy">
                                </div>
                                <div class=app-search>
                                    <h3>Send a link to your email</h3>
                                    <form action=# class=input-details>
                                        <input name="" placeholder="Email" class="mobile-number-app app-input">
                                        <button class="app-btn themeBtn">SEND</button>
                                    </form>
                                </div>
                            </div>
                            <div class=download-details>
                                <h2 class=link-title>Download App</h2>
                                <div class=download-wrapper>
                                    <a href=# class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/apple.webp') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                    <a href=# class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/gp.webp') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                    <a href=# class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/huawei.png') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @php
        $isBackgroundColor = isset($content->background_type) ? $content->background_type === 'background_color' : null;
        $isBackgroundImage = isset($content->background_type) ? $content->background_type === 'background_image' : null;
    @endphp
    <div class=download-app
        @if ($isBackgroundColor && $content->background_color) style="background-color: {{ $content->background_color }}; background-image:none;" @endif
        @if ($isBackgroundImage && $content->background_image) style="background-image: url('{{ asset($content->background_image ?? 'admin/assets/images/placeholder.png') }}')" @endif>
        <div class=container>
            <div class=row>
                <div class=col-md-6>
                    <div class=download-app__details>
                        <div class=section-content>
                            <h2 class=heading
                                @if ($content->title_text_color) style="color: {{ $content->title_text_color }};" @endif>
                                {{ $content->title ?? '' }}
                            </h2>
                        </div>
                        <p
                            @if ($content->description_text_color) style="color: {{ $content->description_text_color }};" @endif>
                            {{ $content->description ?? '' }}
                        </p>
                        <div class=app-details>
                            <div class=code-details>
                                <div class=qr-code>
                                    <img data-src="{{ asset($content->qr_code_image ?? 'admin/assets/images/placeholder.png') }}"
                                        alt="{{ $content->qr_code_image_alt_text }}" class="imgFluid lazy"
                                        loading="lazy">
                                </div>
                                <div class=app-search>
                                    <h3
                                        @if ($content->label_text_color) style="color: {{ $content->label_text_color }};" @endif>
                                        {{ $content->label_title }}</h3>
                                    <form action=# class=input-details>
                                        <input name="" placeholder="Email" class="mobile-number-app app-input">
                                        <button
                                            style="
                                        @if ($content->btn_background_color) background: {{ $content->btn_background_color }}; @else background: var(--color-primary); @endif
                                        @if ($content->btn_text_color) color: {{ $content->btn_text_color }}; @else color: #fff; @endif
                                    "
                                            class="app-btn themeBtn">{{ $content->btn_text ?? 'Send' }}</button>
                                    </form>
                                </div>
                            </div>
                            <div class=download-details>
                                <h2 @if ($content->download_title_text_color) style="color: {{ $content->download_title_text_color }};" @endif
                                    class=link-title>{{ $content->download_title ?? 'Send' }}</h2>
                                <div class=download-wrapper>
                                    <a target="_blank" href="{{ sanitizedLink($content->app_store_link) ?? '' }}"
                                        target="_blank" class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/apple.webp') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                    <a target="_blank" href="{{ sanitizedLink($content->play_store_link) ?? '' }}"
                                        target="_blank" class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/gp.webp') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                    <a target="_blank" href="{{ sanitizedLink($content->huawei_link) ?? '' }}"
                                        class=download-details__btn>
                                        <img data-src={{ asset('frontend/assets/images/huawei.png') }} alt=image
                                            class="imgFluid lazy" loading="lazy">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
