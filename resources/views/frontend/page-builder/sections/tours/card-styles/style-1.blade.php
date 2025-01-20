<div
    class="row {{ in_array($content->box_type, ['slider_carousel', 'slider_carousel_with_background_color']) ? 'five-items-slider' : 'row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-5' }}">
    @foreach ($tours as $tour)
        <div class="col">
            <x-tour-card :tour="$tour" style="style1" />
        </div>
    @endforeach
</div>
