@switch($style)
    @case('style1')
        <div class="category-card img-zoom-wrapper">
            <div class="category-card__img">
                <a href={{ $detailUrl }} class="img-wrapper img-zoom">
                    <img data-src="{{ asset($category->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                        alt="{{ $category->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                </a>
            </div>
            <div class="category-card__content">
                <a href="{{ $detailUrl }}" class="title line-clamp-1"
                    @if (strlen($category->name) > 20) data-tooltip="tooltip" @endif
                    title="{{ $category->name }}">{{ $category->name }}</a>
                <div class="description">
                    <div class="line-clamp-3">
                        {{ $category->short_description }}
                    </div>
                    @if (strlen($category->short_description) > 180)
                        <a href="{{ $detailUrl }}" class="more">...More</a>
                    @endif
                </div>
            </div>
        </div>
    @break

    @case('style2')
        <div class="row row-category mb-4">
            <div class="col-md-6">
                <a href="{{ $detailUrl }}" class="highlight__image" target="_blank">
                    <img alt="{{ $category->name }}"
                        data-src="{{ asset($category->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                        class="imgFluid lazy" loading="lazy">
                </a>
            </div>
            <div class="col-md-6">
                <div class="highlight__text-content">
                    <div class="highlight__text">
                        <a @if (strlen($category->name) > 50) data-tooltip="tooltip" @endif title="{{ $category->name }}"
                            href="{{ $detailUrl }}" class="highlight__text-link line-clamp-2" target="_blank">
                            <p class="highlight__title">{{ $category->name }}</p>
                        </a>
                        <p class="highlight__description line-clamp-6">
                            {{ $category->short_description }}
                        </p>
                    </div>
                    @if ($category->short_description)
                        <div class="highlight__button-wrapper">
                            <a href="{{ $detailUrl }}">
                                See more
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @break

@endswitch
