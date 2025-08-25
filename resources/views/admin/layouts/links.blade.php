@php
    $favicon = App\Models\Setting::where('key', 'favicon')->first()->value ?? null;
@endphp
@if ($favicon)
    <link rel="shortcut icon" href="{{ asset($favicon) }}" type="image/x-icon">
@endif
<script defer src="{{ asset('admin/assets/js/alpine.min.js') }}"></script>
<link href="{{ asset('admin/assets/css/dataTables.min.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/select2.min.css?v=' . time()) }}" rel="stylesheet" />
<script src="{{ asset('admin/assets/js/ckeditor.js?v=' . time()) }}"></script>
<link href="{{ asset('toast/css/jquery.toast.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/bootstrap.min.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/fancybox.min.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/fonts/boxicons/boxicons.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/style.css?v=' . time()) }}" rel="stylesheet">
<link href="{{ asset('admin/assets/css/responsive.css?v=' . time()) }}" rel="stylesheet">
