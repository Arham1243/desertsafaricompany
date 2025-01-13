@extends('frontend.layouts.main')
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="banner-content text-center">
                        <div class="banner-heading">
                            <h1>
                                <span class="bannerMain-title">{{ $title }}</span>
                            </h1>
                            <p>
                                {!! $desc !!}
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
