@if (isset($content->is_review_enabled))
    @if ($content->review_type === 'custom')
        <div class="col-md-12">
            <a href="{{ sanitizedLink($content->custom_review_link) }}"
                style="--review-color: {{ $content->review_text_color ?? '#333333a1' }};" class="banner-rating"
                target="_blank">
                <div class="banner-rating__custom">
                    <img src="{{ asset($content->custom_review_logo_image ?? 'frontend/assets/images/placeholder.png') }}"
                        alt="{{ $content->custom_review_logo_alt_text ?? 'Review Logo' }}"
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
                        <img src="https://i.pravatar.cc/150?img=51" alt="Reviewer 1" class="banner-rating__avatar">
                        <img src="https://i.pravatar.cc/150?img=52" alt="Reviewer 2" class="banner-rating__avatar">
                        <img src="https://i.pravatar.cc/150?img=53" alt="Reviewer 3" class="banner-rating__avatar">
                        <img src="https://i.pravatar.cc/150?img=54" alt="Reviewer 4" class="banner-rating__avatar">
                    </div>
                    <div class="banner-rating__info">{{ $content->custom_review_count }} Reviews
                    </div>
                </div>
            </a>
        </div>
    @else
        @php
            $review_link = null;
            $review_image = null;
            if ($content->review_type === 'google') {
                $review_link = $content->review_google_link ?? 'javascript:void(0);';
                $review_image = 'frontend/assets/images/google.png';
            } elseif ($content->review_type === 'trustpilot') {
                $review_link = $content->review_trustpilot_link ?? 'javascript:void(0);';
                $review_image = 'frontend/assets/images/trustpilot.png';
            } elseif ($content->review_type === 'tripadvisor') {
                $review_link = $content->review_tripadvisor_link ?? 'javascript:void(0);';
                $review_image = 'frontend/assets/images/tripadvisor.png';
            }
        @endphp
        <div class="col-md-12">
            <a href="{{ sanitizedLink($review_link) }}" class="banner-rating"
                style="--review-color: {{ $content->review_text_color ?? '#333333a1' }};" target="_blank">
                <div class="banner-rating__custom">
                    <img src="{{ asset($review_image) }}" alt="{{ $content->review_type }} Logo"
                        class="banner-rating__platform">
                    <div class="banner-rating__stars">
                        <x-star-rating :rating="$reviewDetails['averageRating'] ?? 0" />
                    </div>

                    <div class="banner-rating__info"><span>{{ $reviewDetails['averageRating'] ?? '' }} </span> Stars
                    </div>
                </div>
                <div class="banner-rating__reviews">
                    <div class="banner-rating__avatars">
                        @if (isset($reviewDetails['reviewers_type']) && $reviewDetails['reviewers_type'] === 'names')
                            @foreach ($reviewDetails['reviewers'] as $i => $reviewer)
                                @php
                                    $initials = urlencode($reviewer);
                                @endphp
                                <img src="https://ui-avatars.com/api/?name={{ $initials }}&size=80&rounded=true&background=random"
                                    alt="Reviewer {{ $i + 1 }}" class="banner-rating__avatar">
                            @endforeach
                        @else
                            <img src="https://i.pravatar.cc/150?img=52" alt="Reviewer 2" class="banner-rating__avatar">
                            <img src="https://i.pravatar.cc/150?img=53" alt="Reviewer 3" class="banner-rating__avatar">
                            <img src="https://i.pravatar.cc/150?img=54" alt="Reviewer 4" class="banner-rating__avatar">
                        @endif
                    </div>
                    <div class="banner-rating__info"><span>{{ $reviewDetails['totalReviews'] ?? '' }} </span> Reviews
                    </div>
                </div>
            </a>
        </div>
    @endif
@endif
