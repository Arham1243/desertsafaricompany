<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Default Title' }}</title>
    @include('admin.login_layouts.links')
    @yield('css')
</head>

<body class="responsive">
    @include('admin.login_layouts.header')
    @yield('content')
    @include('admin.login_layouts.footer')
    @include('admin.login_layouts.scripts')
    @yield('js')

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
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
                    hideAfter: 2000,
                    stack: 6
                });
            @endif
        });
    </script>
</body>

</html>
