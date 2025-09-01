<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-seo-tags :seo="$seo ?? null" />
    @include('frontend.layouts.links')
    @yield('css')
    @stack('css')
    @if ($settings->get('header_scripts'))
        {!! $settings->get('header_scripts') !!}
    @endif
</head>

<body>

    @include('frontend.layouts.header')
    @yield('content')
    @include('frontend.layouts.footer')
    @if (isset($popup) && $popup)
        @include('frontend.layouts.popup', ['popup' => $popup])
    @endif

    @include('frontend.layouts.scripts')
    @yield('js')
    @yield('vue-js')
    @stack('js')

    <script type="text/javascript">
        const showMessage = (message, type, position = 'bottom-right') => {
            $.toast({
                heading: type === 'success' ? 'Success!' : 'Error!',
                position: position,
                text: message,
                loaderBg: type === 'success' ? '#ff6849' : '#ff6849',
                icon: type === 'success' ? 'success' : 'error',
                hideAfter: 3000,
                stack: 6
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const img = document.querySelectorAll('img');
            img.forEach(function(imgElement) {
                imgElement.onerror = function() {
                    imgElement.src = "{{ asset('frontend/assets/images/placeholder.png') }}";
                };
            });
        });
        (() => {

            @if (session('notify_success') || isset($_GET['notify_success']))
                $.toast({
                    heading: 'Success!',
                    position: 'bottom-right',
                    text: '{{ session('notify_success') ?? $_GET['notify_success'] }}',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 5000,
                    stack: 6
                });
            @elseif (session('notify_error') || isset($_GET['notify_error']))
                $.toast({
                    heading: 'Error!',
                    position: 'bottom-right',
                    text: '{{ session('notify_error') ?? $_GET['notify_error'] }}',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000,
                    stack: 6
                });
            @endif

        })()
    </script>

    @if ($settings->get('footer_scripts'))
        {!! $settings->get('footer_scripts') !!}
    @endif

    @if ($settings->get('online_chat'))
        {!! $settings->get('online_chat') !!}
    @endif
</body>

</html>
