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
            watch
        } = Vue;
    </script>
@endsection
@push('js')
    @include('frontend.vue.js.' . $appJs)
@endpush
