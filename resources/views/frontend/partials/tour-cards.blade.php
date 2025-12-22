@foreach ($tours as $tour)
    <div class="{{ $colClass }}">
        <x-tour-card :tour="$tour" :style="$cardStyle" />
    </div>
@endforeach
