<div id="{{ $appId }}">
    @include('frontend.vue.views.' . $appComponent)
</div>
@section('vue-js')
    @if (env('APP_MODE') && env('APP_MODE') === 'production')
        <script src="https://cdn.jsdelivr.net/npm/vue@3.x/dist/vue.global.prod.js"></script>
    @else
        <script src="{{ asset('frontend/assets/js/vue@3-local.js') }}"></script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const {
            createApp,
            ref,
            onMounted,
            computed,
            watch,
            nextTick
        } = Vue;

        const showToast = (type, message) => {
            if (type === "error") {
                $.toast({
                    heading: "Error!",
                    position: "top-right",
                    loaderBg: "#ff6849",
                    icon: "error",
                    hideAfter: 5000,
                    text: message,
                    stack: 6,
                });
            } else {
                $.toast({
                    text: message,
                    heading: "Success!",
                    position: "bottom-right",
                    loaderBg: "#ff6849",
                    icon: "success",
                    hideAfter: 2000,
                    stack: 6,
                });
            }
        };
    </script>
@endsection
@push('js')
    @include('frontend.vue.js.' . $appJs)
@endpush
