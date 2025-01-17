<div id="app">
    @if (!Auth::check())
        @include('frontend.auth.src.popup')
    @else
        <a href="{{ route('auth.logout') }}" onclick="return confirm('Are you sure you want to Logout?')" title="Logout"
            class="item__become-supplier">
            <span><b>Logout</b></span>
        </a>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@3"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@if (!Auth::check())
    @section('js')
        @include('frontend.auth.src.js.popup')
    @endsection
@endif
