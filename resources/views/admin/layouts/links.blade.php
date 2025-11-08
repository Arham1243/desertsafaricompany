@php
    $favicon = App\Models\Setting::where('key', 'favicon')->first()->value ?? null;
@endphp
@if ($favicon)
    <link rel="shortcut icon" href="{{ asset($favicon) }}" type="image/x-icon">
@endif
<script defer src="{{ asset('admin/assets/js/alpine.min.js') }}"></script>
<link href="{{ asset('admin/assets/css/dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('toast/css/jquery.toast.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/fancybox.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/fonts/boxicons/boxicons.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/responsive.css') }}" rel="stylesheet">
