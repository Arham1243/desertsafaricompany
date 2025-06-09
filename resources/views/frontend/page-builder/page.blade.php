@extends('frontend.layouts.main')
@php
    $seo = $page->seo ?? null;
@endphp
@section('content')
    @if ($page->show_page_builder_sections === 0)
        @foreach ($sections as $section)
            @include('frontend.page-builder.sections.' . $section->template_path, [
                'content' => json_decode($section->pivot->content),
            ])
        @endforeach
    @else
        @if ($page->banner_image)
            <div class="page-title">
                <img src="{{ asset($page->banner_image) }}" alt="{{ $page->banner_image_alt_text }}"
                    class="imgFluid page-title__bg">
            </div>
        @endif
        <div class="editor-section mar-y">
            <div class="container">
                <div class="editor-content">
                    {!! $page->content ?? '' !!}
                </div>
            </div>
        </div>
    @endif
@endsection
