@switch($style)
    @case('style3')
        <div class="tour-card tour-card--category img-zoom-wrapper">
            <div class="tour-card__img">
                <a href={{ $detailUrl }} class="img-wrapper img-zoom">
                    <img data-src="={{ asset($category->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                        alt="{{ $category->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                </a>
            </div>
            <div class="tour-card__content">
                <a href="{{ $detailUrl }}" class="title line-clamp-1"
                    @if (strlen($category->name) > 20) data-tooltip="tooltip" @endif
                    title="{{ $category->name }}">{{ $category->name }}</a>
                <div class="description line-clamp-4">
                    {{ $category->short_description }}
                </div>
            </div>
        </div>
    @break
@endswitch
