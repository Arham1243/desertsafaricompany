@if (!$content)
    <div class=comment>
        <img data-src={{ asset('frontend/assets/images/comment.webp') }} alt=image class="peoples-img lazy imgFluid"
            loading="lazy">
        <div class=ocizgi_imgs>
            <img data-src={{ asset('frontend/assets/images/ocizgi.webp') }} alt=image class="ocizgi imgFluid lazy"
                loading="lazy">
        </div>
        <div class=container>
            <div class=section-content>
                <h2 class=subHeading>
                    Comment
                </h2>
                <p>What are our customers saying?</p>
            </div>
            <div class="row pt-3">
                <div class=col-md-3>
                    <div class=comment-card>
                        <div class="comment-card__img one-items-slider">
                            <img data-src={{ asset('frontend/assets/images/comment1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                            <img data-src={{ asset('frontend/assets/images/comment2.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=comment-card__content>
                            <div class=comment-details>
                                <div class=customer-name>
                                    neurontnP
                                </div>
                                <div class=card-rating>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star"></i>
                                </div>
                            </div>
                            <div class=comment-pra>
                                I am not sure where you’re getting your information, but great topic.
                            </div>
                            <button class="app-btn themeBtn">Read</button>
                        </div>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=comment-card>
                        <div class="comment-card__img one-items-slider">
                            <img data-src={{ asset('frontend/assets/images/comment2.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                            <img data-src={{ asset('frontend/assets/images/comment1.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=comment-card__content>
                            <div class=comment-details>
                                <div class=customer-name>
                                    neurontnP
                                </div>
                                <div class=card-rating>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star"></i>
                                </div>
                            </div>
                            <div class=comment-pra>
                                I am not sure where you’re getting your information, but great topic.
                            </div>
                            <button class="app-btn themeBtn">Read</button>
                        </div>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=comment-card>
                        <div class="comment-card__img one-items-slider">
                            <img data-src={{ asset('frontend/assets/images/comment3.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                            <img data-src={{ asset('frontend/assets/images/comment4.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=comment-card__content>
                            <div class=comment-details>
                                <div class=customer-name>
                                    neurontnP
                                </div>
                                <div class=card-rating>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star"></i>
                                </div>
                            </div>
                            <div class=comment-pra>
                                I am not sure where you’re getting your information, but great topic.
                            </div>
                            <button class="app-btn themeBtn">Read</button>
                        </div>
                    </div>
                </div>
                <div class=col-md-3>
                    <div class=comment-card>
                        <div class="comment-card__img one-items-slider">
                            <img data-src={{ asset('frontend/assets/images/comment4.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                            <img data-src={{ asset('frontend/assets/images/comment3.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=comment-card__content>
                            <div class=comment-details>
                                <div class=customer-name>
                                    neurontnP
                                </div>
                                <div class=card-rating>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star yellow-star"></i>
                                    <i class="bx bxs-star"></i>
                                </div>
                            </div>
                            <div class=comment-pra>
                                I am not sure where you’re getting your information, but great topic.
                            </div>
                            <button class="app-btn themeBtn">Read</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class=comment>
        <img data-src={{ asset('frontend/assets/images/comment.webp') }} alt=image class="peoples-img lazy imgFluid"
            loading="lazy">
        <div class=ocizgi_imgs>
            <img data-src={{ asset('frontend/assets/images/ocizgi.webp') }} alt=image class="ocizgi imgFluid lazy"
                loading="lazy">
        </div>
        <div class=container>
            <div class=section-content>
                <h2 class=subHeading style="color: {{ $content->title_text_color ?? '' }};">
                    {{ $content->title ?? '' }}
                </h2>
                <p style="color: {{ $content->subTitle_text_color ?? '' }};">{{ $content->subTitle ?? '' }}</p>
            </div>
            @php
                $testimonials = App\Models\Testimonial::whereIn('id', $content->testimonial_ids ?? [])
                    ->latest()
                    ->get();
            @endphp
            <div class="row pt-3">
                @if ($testimonials->isNotEmpty())
                    @foreach ($testimonials as $testimonial)
                        <div class=col-md-3>
                            <div class=comment-card>
                                <div class="comment-card__img one-items-slider">
                                    <img data-src="{{ asset($testimonial->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                        alt="{{ $testimonial->featured_image_alt_text }}" class="imgFluid lazy"
                                        loading="lazy">
                                    @if ($testimonial->media->isNotEmpty())
                                        @foreach ($testimonial->media as $media)
                                            <img data-src="{{ asset($media->file_path ?? 'admin/assets/images/placeholder.png') }}"
                                                alt="{{ $media->alt_text }}" class="imgFluid lazy" loading="lazy">
                                        @endforeach
                                    @endif
                                </div>
                                <div class=comment-card__content>
                                    <div class=comment-details>
                                        <div class="customer-name" title="{{ $testimonial->title ?? '' }}"
                                            @if (strlen($testimonial->title ?? '') > 19) data-tooltip="tooltip" @endif>
                                            {{ $testimonial->title ?? '' }}
                                        </div>
                                        <div class=card-rating>
                                            <x-star-rating :rating="$testimonial->rating" />
                                        </div>
                                    </div>
                                    <div class=comment-pra>
                                        {!! $testimonial->content ?? '' !!}
                                    </div>
                                    @if (isset($content->is_button_enabled))
                                        <a style="
                                        @if ($content->btn_background_color) background: {{ $content->btn_background_color }}; @else background: var(--color-primary); @endif
                                        @if ($content->btn_text_color) color: {{ $content->btn_text_color }}; @else color: #fff; @endif
                                    "
                                            href="javascript:void(0)" class="app-btn themeBtn">
                                            {{ $content->btn_text ?? 'Read' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="section-content">
                        <div class="heading">No Testimonials available.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
