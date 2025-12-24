@extends('frontend.layouts.main')
@section('content')
    @php
        $jsonContent = json_decode($blog->json_content, true) ?? [];
        $is_enabled_blogs_you_may_also_like = $settings->get('is_enabled_blogs_you_may_also_like')
            ? (int) $settings->get('is_enabled_blogs_you_may_also_like') === 1
            : false;
        $seo = $blog->seo ?? null;
    @endphp

    <div class="mt-3">
        <div class="container">
            <nav aria-label="breadcrumb" class="mt-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Home</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                    <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @if ($blog->country)
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.country', $blog->country->iso_alpha2) }}">
                                {{ $blog->country->name ?? '' }}
                            </a>
                        </li>
                        <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @endif
                    @if ($blog->city)
                        <li class="breadcrumb-item">
                            <a href="{{ route('locations.city', [$blog->city->country->iso_alpha2, $blog->city->slug]) }}">
                                {{ $blog->city->name ?? '' }}
                            </a>
                        </li>
                        <li style="color: #6c757d;" class="breadcrumb-item">/</li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $blog->slug }}</li>
                </ol>
            </nav>
        </div>
        <div class="blog-details mt-4 pt-1 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="blog-details__mainHeading">
                            {{ $blog->title }}
                        </h1>

                        <div class="author-section-wrapper">
                            @php
                                $authorId = $jsonContent['author_id'] ?? null;
                                $author = $authors->where('id', $authorId)->first();
                            @endphp

                            <div class="author-section">
                                <div class="author-avatar">
                                    <img data-src="{{ asset($author->profile_image ?? 'frontend/assets/images/placeholder.png') }}"
                                        alt="{{ $author->profile_image_alt_text ?? '' }}" class="imgFluid lazy"
                                        loading="lazy">
                                </div>

                                <div class="author-info">
                                    <div class="author-name">{{ $author->name ?? '' }}</div>
                                    <div class="post-meta">
                                        <span>{{ formatDate($jsonContent['publish_date'] ?? '') }}</span>
                                        <span>
                                            ·</span> <span>{{ $jsonContent['reading_time'] ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                            <ul class="header-listGroup">
                                <li>
                                    <a href="javascript:void(0)" data-send-button="">
                                        <span class="header-listGroup faq-icon"> <i class="bx bx-share-alt"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="post-content">
                            <div class="post-content__img mb-4">
                                <img data-src="{{ asset($blog->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                    alt='{{ $blog->featured_image_alt_text }}' class='imgFluid lazy' loading='lazy'>
                            </div>
                            <div class="my-3">
                                <div class="tour-content__details " data-show-more>
                                    <div class="editor-content line-clamp" data-show-more-content
                                        @if ($blog->content_line_limit > 0) style="-webkit-line-clamp: {{ $blog->content_line_limit }};" @endif>
                                        {!! $blog->content !!}
                                    </div>
                                    @if ($blog->content_line_limit > 0)
                                        <a href="javascript:void(0)" class="loginBtn mt-1" data-show-more-btn
                                            more-text="Read more" less-text='Read less'>
                                            Read more</a>
                                    @endif
                                </div>
                            </div>
                            <div class="separator"></div>
                        </div>
                        <div class="helpful-section">
                            <p>Was this page helpful?</p>
                            <div class="feedback-buttons">
                                <div class="feedback-button {{ $reaction === 'like' ? 'active' : '' }}" id="like-button">
                                    <i class='bx bx-like'></i>
                                    <i class='bx bxs-like'></i>
                                </div>
                                <div class="feedback-button {{ $reaction === 'dislike' ? 'active' : '' }}"
                                    id="dislike-button">
                                    <i class='bx bx-dislike'></i>
                                    <i class='bx bxs-dislike'></i>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-md-4">
                        @php
                            $topFeaturedTourIds = $jsonContent['top_featured_tour_ids'] ?? [];
                            $topFeaturedTours = $tours->whereIn('id', $topFeaturedTourIds);
                        @endphp
                        @if ($topFeaturedTours->isNotEmpty())
                            <div class="one-tour-card-slider">
                                @foreach ($topFeaturedTours as $topFeaturedTour)
                                    <div class="availability-frame">
                                        <div class="availability-frame__deatils">
                                            <div class="availability-frame__img">
                                                <img data-src="{{ asset($topFeaturedTour->featured_image ?? 'frontend/assets/images/placeholder.png') }}"
                                                    alt="{{ $topFeaturedTour->featured_image_alt_text }}"
                                                    class="imgFluid lazy" loading="lazy">
                                            </div>
                                            <div class="availability-frame__content w-100">
                                                <div class="availability-title">
                                                    {{ $topFeaturedTour->title }}
                                                </div>
                                                <div class="card-rating">
                                                    <x-star-rating :rating="$topFeaturedTour->average_rating" />
                                                    @if ($topFeaturedTour->reviews->count() > 0)
                                                        <span>{{ $topFeaturedTour->reviews->count() }}
                                                            Review{{ $topFeaturedTour->reviews->count() > 1 ? 's' : '' }}</span>
                                                    @else
                                                        <span>No reviews yet</span>
                                                    @endif
                                                </div>

                                                @if ($topFeaturedTour->tour_lowest_price)
                                                    <div class="priceLabel__no-deal">
                                                        From {!! formatPrice($topFeaturedTour->tour_lowest_price) !!} per person
                                                    </div>
                                                @endif

                                                <input id="date" type="date" class="booking-assistant-dropdown">


                                                <div class="availability-frame__btn">
                                                    <a href="{{ buildTourDetailUrl($topFeaturedTour) }}"
                                                        class="w-100 app-btn themeBtn">Book
                                                        Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @php
                            $mayAlsoLike = json_decode($blog->may_also_like, true) ?? [];
                            $enabled = isset($mayAlsoLike['enabled']) ? (int) $mayAlsoLike['enabled'] : 0;
                            $type = $mayAlsoLike['type'] ?? 'category_based';
                            $customIds = $mayAlsoLike['custom_ids'] ?? [];
                        @endphp

                        @if ($mayAlsoLike && (int) $mayAlsoLike['enabled'] === 1)
                            @php
                                if ($type === 'custom') {
                                    $blogsToShow = $allBlogs->whereIn('id', $customIds);
                                } elseif ($type === 'latest') {
                                    $blogsToShow = $allBlogs->sortByDesc('created_at');
                                } else {
                                    $blogsToShow = $allBlogs->where('category_id', $blog->category_id);
                                }
                            @endphp
                            <div class="you-may-also-like mt-5">
                                <div class="section-content">
                                    <h2 class="subHeading block-heading">
                                        You may also like
                                    </h2>
                                </div>
                                @if ($blogsToShow->isNotEmpty())
                                    @foreach ($blogsToShow as $itemBlog)
                                        <div class="blogDet-card blogDet-card-like mt-4 mt-4">
                                            <a href="{{ buildBlogDetailUrl($itemBlog) }}" class="blogDet-card__img">
                                                <img data-src="{{ asset($itemBlog->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                    alt="{{ $itemBlog->feature_image_alt_text }}" class="imgFluid lazy"
                                                    loading="lazy">
                                            </a>

                                            <div class="blogDet-card__content">
                                                <a href="{{ buildBlogDetailUrl($itemBlog) }}"
                                                    class="blogDet-card__title line-clamp-4">
                                                    {{ $itemBlog->title }}
                                                </a>
                                                <div class="blogDet-card__pra line-clamp-7">
                                                    {{ $itemBlog->description }}
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="you-may-also-like mt-4">
                                        <div class="text-document ">
                                            <p>No blogs found</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $whatsappNumberDialCode = trim($settings->get('whatsapp_number_dial_code'));
        $whatsappNumberRaw = trim($settings->get('whatsapp_number'));
        $whatsappNumber = ltrim(preg_replace('/\D/', '', $whatsappNumberRaw), '0');
        $fullWhatsappNumber = $whatsappNumberDialCode . $whatsappNumber;
        $text = rawurlencode("Hi, I'm interested in this blog:\n{$blog->title}\n" . url()->current());
    @endphp
    @php
        $entityTitle = $blog->title;
        $shareUrl = urlencode(url()->current());
        $shareText = urlencode($entityTitle . ' - ' . url()->current());
    @endphp

    <div class="share-popup-wrapper" data-send-popup>
        <div class="share-popup light">
            <div class="share-popup__header">
                <div class="title">Share</div>
                <div class="popup-close-icon close-btn">
                    <i class='bx bx-x'></i>
                </div>
            </div>
            <div class="share-popup__body">
                <ul class="platforms">
                    <li class="platform">
                        <a href="https://wa.me/?text={{ $shareText }}" target="_blank">
                            <div class="icon" style="background: #27D469;">
                                <i class='bx bxl-whatsapp'></i>
                            </div>
                            <div class="title">WhatsApp</div>
                        </a>
                    </li>

                    <li class="platform">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}&quote={{ urlencode($entityTitle) }}"
                            target="_blank">
                            <div class="icon" style="background: #3D5A98;">
                                <i class='bx bxl-facebook'></i>
                            </div>
                            <div class="title">Facebook</div>
                        </a>
                    </li>

                    <li class="platform">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($entityTitle) }}&url={{ $shareUrl }}"
                            target="_blank">
                            <div class="icon" style="background: #000;">
                                <img src="https://imagecme.com/public/frontend/assets/images/x.png" alt="X">
                            </div>
                            <div class="title">X</div>
                        </a>
                    </li>

                    <li class="platform">
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ urlencode($entityTitle) }}"
                            target="_blank">
                            <div class="icon" style="background: #0A66C2;">
                                <i class='bx bxl-linkedin'></i>
                            </div>
                            <div class="title">LinkedIn</div>
                        </a>
                    </li>

                    <li class="platform">
                        @php
                            $companyEmail = $settings->get('company_email');
                            $subject = rawurlencode("Check this out: {$entityTitle}");
                            $body = rawurlencode("Hey,\nI thought you’d like this:\n\n{$entityTitle}\n{$shareUrl}");
                        @endphp
                        <a href="mailto:?subject={{ $subject }}&body={{ $body }}">
                            <div class="icon" style="background: grey;">
                                <i class='bx bxs-envelope'></i>
                            </div>
                            <div class="title">Email</div>
                        </a>
                    </li>
                </ul>

                <div class="copy-link">
                    <input type="text" readonly class="copy-link__input" value="{{ url()->current() }}">
                    <button type="button" class="copy-link__btn primary-btn" onclick="copyLink()">Copy</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $bottomFeaturedTourIds = $jsonContent['bottom_featured_tour_ids'] ?? [];
        $bottomFeaturedTours = $tours->whereIn('id', $bottomFeaturedTourIds);
    @endphp

    @if ($bottomFeaturedTours->isNotEmpty())
        <div class="one-tour-card-slider">
            @foreach ($bottomFeaturedTours as $bottomFeaturedTour)
                <div class="availability-frame my-5 pt-3 pb-4">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-md-3">
                            <div class="availability-frame__img availability-frame__img-ver">
                                <img data-src="{{ asset($bottomFeaturedTour->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                    alt="{{ $bottomFeaturedTour->featured_image_alt_text }}" class="imgFluid lazy"
                                    loading="lazy">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="availability-frame__content w-100">
                                <div class="availability-title">
                                    {{ $bottomFeaturedTour->title }}
                                </div>

                                <div class="card-rating">
                                    <x-star-rating :rating="$bottomFeaturedTour->average_rating" />
                                    @if ($bottomFeaturedTour->reviews->count() > 0)
                                        <span>{{ $bottomFeaturedTour->reviews->count() }}
                                            Review{{ $bottomFeaturedTour->reviews->count() > 1 ? 's' : '' }}</span>
                                    @else
                                        <span>No reviews yet</span>
                                    @endif
                                </div>
                                @if ($bottomFeaturedTour->tour_lowest_price)
                                    <div class="priceLabel__no-deal">
                                        From {!! formatPrice($bottomFeaturedTour->tour_lowest_price) !!} per person
                                    </div>
                                @endif
                                <input id="date" type="date" class="booking-assistant-dropdown">
                                <div class="availability-frame__btn">
                                    <a href="{{ buildTourDetailUrl($bottomFeaturedTour) }}"
                                        class="w-100 app-btn themeBtn">Book
                                        Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
@push('css')
    <style>
        ol.breadcrumb {
            font-weight: 600;
        }
    </style>
@endpush
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likeButton = document.getElementById('like-button');
            const dislikeButton = document.getElementById('dislike-button');
            const blogId = "{{ $blog->id }}";

            function sendReaction(reaction) {
                fetch(`/blogs/${blogId}/reaction`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            reaction
                        })
                    }).then(res => res.json())
                    .then(data => console.log(data));
            }

            likeButton.addEventListener('click', () => {
                const active = likeButton.classList.contains('active');
                likeButton.classList.toggle('active', !active);
                dislikeButton.classList.remove('active');
                sendReaction(!active ? 'like' : null);
            });

            dislikeButton.addEventListener('click', () => {
                const active = dislikeButton.classList.contains('active');
                dislikeButton.classList.toggle('active', !active);
                likeButton.classList.remove('active');
                sendReaction(!active ? 'dislike' : null);
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const sendPopupBtn = document.querySelectorAll('[data-send-button]');
            const popupWrapper = document.querySelector('[data-send-popup]');
            const copyLinkInput = popupWrapper.querySelector('.copy-link__input');
            const closeIcon = popupWrapper.querySelector('.close-btn');

            sendPopupBtn.forEach(btn => {
                btn?.addEventListener('click', function() {
                    popupWrapper.classList.add('open');
                });
            });

            closeIcon.addEventListener('click', function(e) {
                popupWrapper.classList.remove('open');
            });
        });
        const copyLink = () => {
            var copyText = document.querySelector('.copy-link__input');

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            document.execCommand('copy');

            showMessage('Link copied to clipboard!', 'success', 'top-right');
        }
    </script>
@endpush
