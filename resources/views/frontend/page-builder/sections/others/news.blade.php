@if (!$content)
    <div class="latest-stories section-padding">
        <div class=container>
            <div class="section-content mb-4 pb-1">
                <div class=latest-stories__title>TRAVEL STORIES AND NEWS</div>
                <h2 class=subHeading>
                    Explore our latest stories
                </h2>
            </div>
            <div class="row">
                <div class=col-md-7>
                    <div class=Desti-Pract__details>
                        <div class=Desti-Pract__img>
                            <img data-src={{ asset('frontend/assets/images/lastest-main.webp') }} alt=image
                                class="imgFluid lazy" loading="lazy">
                        </div>
                        <div class=Desti-Pract__content>
                            <div class="sub-title">Destination Practicalities</div>
                            <h3 href="#" class="Desti-Pract__title">8 things to know before visiting Savannah</h3>
                            <div class="date">Jun 21, 2024 • 6 min read</div>
                            <p>With its grand leafy streets and beautiful architecture, Savannah is an incredible city
                                to
                                visit. Here's everything you need to know before you go.</p>
                        </div>
                    </div>
                </div>
                <div class=col-md-5>
                    <div class=Desti-Pract__activities>
                        <div class=activities-details>
                            <div class=activities-img>
                                <img data-src={{ asset('frontend/assets/images/GettyImages-1392454769.webp') }}
                                    alt=image class="imgFluid lazy" loading="lazy">
                            </div>
                            <div class=activities-content>
                                <p><b>Hiking</b></p>
                                <a href=#>9 best places to visit in Georgia </a>
                                <p>Jun 21, 2024 • 7 min read</p>
                            </div>
                        </div>
                    </div>
                    <div class=Desti-Pract__activities>
                        <div class=activities-details>
                            <div class=activities-img>
                                <img data-src={{ asset('frontend/assets/images/GettyImages-1392454769.webp') }}
                                    alt=image class="imgFluid lazy" loading="lazy">
                            </div>
                            <div class=activities-content>
                                <p><b>Hiking</b></p>
                                <a href=#>9 best places to visit in Georgia </a>
                                <p>Jun 21, 2024 • 7 min read</p>
                            </div>
                        </div>
                    </div>
                    <div class=Desti-Pract__activities>
                        <div class=activities-details>
                            <div class=activities-img>
                                <img data-src={{ asset('frontend/assets/images/GettyImages-1392454769.webp') }}
                                    alt=image class="imgFluid lazy" loading="lazy">
                            </div>
                            <div class=activities-content>
                                <p><b>Hiking</b></p>
                                <a href=#>9 best places to visit in Georgia </a>
                                <p>Jun 21, 2024 • 7 min read</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="primary-btn primary-btn--center mt-4">Read More</button>
        </div>
    </div>
@else
    <div class="latest-stories section-padding">
        <div class=container>
            <div class="section-content mb-4 pb-1">
                <div class=latest-stories__title style="color:{{ $content->title_text_color ?? '' }};">
                    {{ $content->title ?? '' }}</div>
                <h2 class=subHeading style="color:{{ $content->subTitle_text_color ?? '' }};">
                    {{ $content->subTitle ?? '' }}
                </h2>
            </div>
            @php
                $featured_news = App\Models\News::find($content->featured_news_id)->first();
                $news_list = App\Models\News::whereIn('id', $content->news_list_ids ?? [])->get();
            @endphp

            <div class="row">
                <div class=col-md-7>
                    @if ($featured_news)
                        <div class=Desti-Pract__details>
                            <a href="{{ $featured_news->slug }}" class=Desti-Pract__img>
                                <img data-src="{{ asset($featured_news->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                    alt="{{ $featured_news->feature_image_alt_text }}" class="imgFluid lazy"
                                    loading="lazy">
                            </a>
                            <div class=Desti-Pract__content>
                                <div class="sub-title">
                                    {{ $featured_news->category->name ?? '' }}
                                </div>
                                <a href="{{ $featured_news->slug }}"
                                    class="Desti-Pract__title">{{ $featured_news->title ?? '' }}</a>
                                <div class="date">{{ formatDate($featured_news->created_at) }}</div>
                                <div class="editor-content">
                                    {!! $featured_news->content ?? '' !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="section-content">
                            <div class="heading">No featured news available.</div>
                        </div>
                    @endif
                </div>
                <div class=col-md-5>
                    @if ($news_list->isNotEmpty())
                        @foreach ($news_list as $news)
                            <div class=Desti-Pract__activities>
                                <div class=activities-details>
                                    <a href="{{ $news->slug }}" class=activities-img>
                                        <img data-src="{{ asset($news->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                            alt="{{ $news->feature_image_alt_text }}" class="imgFluid lazy"
                                            loading="lazy">
                                    </a>
                                    <div class=activities-content>
                                        <p><b>{{ $news->category->name ?? '' }}</b></p>
                                        <a href="{{ $news->slug }}">{{ $news->title ?? '' }}</a>
                                        <p>{{ formatDate($news->created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="section-content">
                            <div class="heading">No news available.</div>
                        </div>
                    @endif
                </div>
            </div>
            <button
                style="background: {{ $content->btn_background_color ?? '' }};color:{{ $content->btn_text_color ?? '' }};"
                class="primary-btn primary-btn--center mt-4">{{ $content->btn_text ?? '' }}</button>
        </div>
    </div>
@endif
