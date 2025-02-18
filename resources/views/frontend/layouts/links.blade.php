@php
    $favicon = $settings->get('favicon');
@endphp;
@endphp
@if ($favicon)
    <link rel="shortcut icon" href="{{ asset($favicon) }}" type="image/x-icon">
@endif
<link href="{{ asset('frontend/assets/css/all.min.css?v=' . time()) }}" rel="stylesheet">
<link rel="preload" href="{{ asset('frontend/assets/fonts/boxicons/boxicons.woff2') }}" as="font" type="font/woff2"
    crossorigin="anonymous">
<link href="{{ asset('frontend/assets/css/style.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/css/responsive.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('toast/css/jquery.toast.css?v=' . time()) }}" rel="stylesheet">
