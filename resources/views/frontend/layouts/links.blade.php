@php
    $favicon = $settings->get('favicon') ?? null;
    $globalStyles = $settings->get('global_styles') ?? null;
@endphp
@if ($favicon)
    <link rel="shortcut icon" href="{{ asset($favicon) }}" type="image/x-icon">
@endif
<link href="{{ asset('frontend/assets/css/all.min.css') }}" rel="stylesheet">
<link rel="preload" href="{{ asset('frontend/assets/fonts/boxicons/boxicons.woff2') }}" as="font" type="font/woff2"
    crossorigin="anonymous">
<link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/responsive.css') }}" rel="stylesheet">
<link href="{{ asset('toast/css/jquery.toast.css') }}" rel="stylesheet">
@if ($globalStyles)
    <style>
        {!! $globalStyles !!}
    </style>
@endif
