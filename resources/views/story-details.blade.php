@extends('layouts.main')
@section('content')
    <div class="stories mar-y">
        <div class="container">
            <div class="stories-content">
                <!-- Display the story image -->
                <div class="stories-content__img">
                    <img src="{{ asset($story->img_path ?? 'frontend/assets/images/placeholder.png') }}" alt="Image"
                        class="imgFluid">
                </div>

                <ul class="stories-content__details">
                    <li>
                        <span><i class='bx bxs-map'></i></span>
                        <span>{{ $story->city->name ?? 'Unknown City' }}</span>
                    </li>
                    <li>
                        <span><i class='bx bxs-calendar'></i></span>
                        <span>{{ $story->created_at ? $story->created_at->format('d-M-Y') : 'Date not available' }}</span>
                    </li>
                    <li>
                        <span><i class='bx bxs-time'></i></span>
                        <span>{{ $story->estimated_read_time ? $story->estimated_read_time . ' min read' : 'Read time not available' }}</span>
                    </li>
                </ul>

                <!-- Display the story title -->
                <div class="stories-content__title">{{ $story->title ?? 'Title not available' }}</div>

                <!-- Display the short description -->
                <div class="stories-content__desc">{{ $story->short_desc ?? 'Short description not available' }}</div>

                <!-- Display the long description in the editor -->
                <div class="stories-content__editor">{!! $story->long_desc ?? 'Long description not available' !!}</div>
            </div>
        </div>
    </div>
@endsection
