<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' | ' . env('APP_NAME') : env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('user.layouts.links')
    @yield('css')
    @stack('css')
</head>


<body class="responsive">
    <div class="dashboard" id="main-dashboard-wrapper">
        <div class="container-fluid p-0">
            <div class="layout-sidebar">
                @include('user.layouts.sidebar')
            </div>
            <div class="layout-content-wrapper">
                <div class="row g-0">
                    @include('user.layouts.header')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div class="loader-mask" id="loader">
        <div class="loader"></div>
    </div>

    @include('user.layouts.scripts')
    @yield('js')
    @stack('js')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const img = document.querySelectorAll('img');
            img.forEach(function(imgElement) {
                imgElement.onerror = function() {
                    imgElement.src = "{{ asset('user/assets/images/placeholder.png') }}";
                };
            });
        });
        (() => {
            @if (session('notify_success'))
                $.toast({
                    heading: 'Success!',
                    position: 'bottom-right',
                    text: '{{ session('notify_success') }}',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 2000,
                    stack: 6
                });
            @elseif (session('notify_error'))
                $.toast({
                    heading: 'Error!',
                    position: 'bottom-right',
                    text: '{{ session('notify_error') }}',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000,
                    stack: 6
                });
            @endif
        })()
    </script>
</body>

</html>
