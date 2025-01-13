<div id="app">
    @include('frontend.auth.src.popup')
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@section('js')
    @include('frontend.auth.src.js.popup')
@endsection
