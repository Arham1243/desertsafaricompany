<div id="{{ $appId }}">
    @include('frontend.vue.views.' . $appComponent)
</div>
@section('vue-js')
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@3.x/dist/vue.global.prod.js"></script> --}}
    <script src="{{ asset('frontend/assets/js/vue@3-local.js') }}"></script>
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
