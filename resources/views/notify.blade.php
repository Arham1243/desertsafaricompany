@extends('frontend.layouts.main')
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-content text-center">
                        <h1 class="subHeading">
                            {{ $title }}
                        </h1>
                        <p style="font-size:1rem ">
                            {!! $desc !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
