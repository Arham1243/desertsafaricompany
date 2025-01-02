@if (!$content)
    <div class="more-offers section-padding">
        <div class=container>
            <div class="section-content d-flex align-items-center justify-content-between">
                <h2 class=heading>
                    Top Water Activities in Dubai
                </h2>
                <div class=more-link>
                    <a href=#>More<i class="bx bx-right-arrow-alt"></i></a>
                </div>
            </div>
            <div class="row pt-3">
                <div class=col-lg-6>
                    <div class=more-offers__content>
                        <div class=more-offers__details>
                            <div class=featured-text>Special Offers</div>
                            <h2 class=more-offers-title>Private Arabic Desert Camp</h2>
                            <p class=more-offers-sub-title>A private camp with Arabian-style decor.<br>
                                Sit-out seating from two to fifty guests.<br>
                                Ambient music system for atmosphere.<br>
                                warm cozy bone fire will put charm in the environment.</p>
                            <div class=more-offers__img
                                style="background:url('{{ asset('frontend/assets/images/private-arabic-desert-camp.webp') }}')">
                            </div>
                        </div>
                    </div>
                </div>
                <div class=col-lg-3>
                    <div class=more-offers__content>
                        <div class=more-offers__details>
                            <div class=featured-icon><i class=icofont-ui-map></i></div>
                            <h2 class=more-offers-title>Beach Party</h2>
                            <p class=more-offers-sub-title>Beachside setup Decor<br>
                                Relish the live BBQ feasts<br>
                                Seating setup for 2 - 50 People.</p>
                            <div class=more-offers__img
                                style="background:url('{{ asset('frontend/assets/images/beach-party.webp') }}')"></div>
                        </div>
                    </div>
                </div>
                <div class=col-lg-3>
                    <div class=more-offers__content>
                        <div class=more-offers__details>
                            <div class=featured-icon><i class=icofont-island-alt></i></div>
                            <h2 class=more-offers-title>Private Dinner</h2>
                            <p class=more-offers-sub-title>Private setup Decor<br>
                                Relish the live BBQ feasts<br>
                                Seating setup for 2 - 50 People.</p>
                            <div class=more-offers__img
                                style="background:url('{{ asset('frontend/assets/images/private-dinner.webp') }}')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="more-offers section-padding">
        <div class=container>
            <div class="row w-100 align-items-center mb-4">
                <div class="col-md-9">
                    <div class="section-content d-flex align-items-center justify-content-between">
                        <div class=heading
                            style="color: {{ isset($content->title_color) ? $content->title_color : '' }};">
                            {{ $content->title }}
                        </div>
                    </div>
                </div>
                @if (isset($content->is_more_btn_enabled))
                    <div class="col-md-3">
                        <div class="d-flex justify-content-end">
                            <a href=# class="primary-btn"
                                style="background: {{ $content->see_more_background_color ?? 'transparent' }};color: {{ $content->see_more_text_color ?? 'var(--color-primary)' }};">{{ $content->see_more_text }}<i
                                    class="bx bx-right-arrow-alt"></i></a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                @foreach ($content->activities as $activity)
                    <div class="col-lg-{{ $loop->first ? '6' : '3' }}">
                        <div class=more-offers__content>
                            <div class=more-offers__details>
                                @if (isset($activity->is_special) && $activity->is_special == 1)
                                    <div class=featured-text>Special Offers</div>
                                @endif
                                <h2 class=more-offers-title>{{ $activity->title }}</h2>
                                <p class=more-offers-sub-title>{{ $activity->content }}</p>
                                <div class=more-offers__img
                                    style="background:url('{{ asset($activity->image ?? 'admin/assets/images/placeholder.png') }}')">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
