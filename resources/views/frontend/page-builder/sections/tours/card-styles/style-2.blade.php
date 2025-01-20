<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'three-items-slider' : 'row-cols-1 row-cols-lg-2 row-cols-xl-3' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <x-tour-card :tour="$tour" style="style2" />
        </div>
    @endforeach
</div>
