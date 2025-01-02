@if (!$content)
    <div class="newsletter section-padding">
        <div class=container>
            <div class="row g-0">
                <div class=col-md-6>
                    <div class=newsletter__img>
                        <img data-src={{ asset('frontend/assets/images/newsletter-background.webp') }} alt=image
                            class="imgFluid lazy" loading="lazy">
                    </div>
                </div>
                <div class=col-md-6>
                    <div class=newsletter__content>
                        <div class=section-content>
                            <h2 class=subHeading>
                                Your travel journey starts here
                            </h2>
                        </div>
                        <p>Sign up now for travel tips, personalized itineraries, and vacation inspiration straight to
                            your
                            inbox.</p>
                        <form class=line-form method="POST" action="#">
                            <div class=line-form__input>
                                <input id=email type=email name=email placeholder="Email" required>
                                <i class="bx bx-envelope"></i>
                            </div>
                            <button type=submit class="primary-btn">Sign up</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=privacy-content>
                <p class="mb-0">By signing up, you agree to receive promotional emails on activities and insider tips.
                    You
                    can
                    unsubscribe or withdraw your consent at any time with future effect. For more information, read our
                    Privacy statement</p>
            </div>
        </div>
    </div>
@else
    <div class="newsletter section-padding">
        <div class=container>
            <div class="row g-0">
                <div class=col-md-6>
                    <div class=newsletter__img>
                        <img data-src="{{ asset($content->left_image ?? 'admin/assets/images/placeholder.png') }}"
                            alt="{{ $content->left_image_alt_text }}" class="imgFluid lazy" loading="lazy">
                    </div>
                </div>
                <div class=col-md-6>
                    <div class=newsletter__content
                        @if ($content->right_background_color) style="background-color: {{ $content->right_background_color }};" @endif>
                        <div class=section-content>
                            <h2 class=subHeading
                                @if ($content->title_text_color) style="color: {{ $content->title_text_color }};" @endif>
                                {{ $content->title ?? '' }}
                            </h2>
                        </div>
                        <p
                            @if ($content->description_text_color) style="color: {{ $content->description_text_color }};" @endif>
                            {{ $content->description ?? '' }}
                        </p>
                        <form class=line-form method="POST" action="{{ route('save-newsletter') }}">
                            @csrf
                            <div class=line-form__input>
                                <input id=email type=email name=email placeholder="Email" required>
                                <i class="bx bx-envelope"></i>
                            </div>
                            <button
                                style="
                            @if ($content->btn_background_color) background: {{ $content->btn_background_color }}; @else background: var(--color-primary); @endif
                            @if ($content->btn_text_color) color: {{ $content->btn_text_color }}; @else color: #fff; @endif
                        "
                                type=submit class="primary-btn">{{ $content->btn_text ?? 'Sign up' }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=privacy-content>
                <p class="mb-0"
                    @if ($content->privacy_statement_text_color) style="color: {{ $content->privacy_statement_text_color }};" @endif>
                    {{ $content->privacy_statement ?? '' }}
                </p>
            </div>
        </div>
    </div>
@endif
