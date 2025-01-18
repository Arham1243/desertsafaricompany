@extends('frontend.layouts.main')
@section('content')
    <div class="cart section-padding">
        <div class="container">
            <div class="text-center">
                <div class="section-content">
                    <div class="check-icon red">
                        <i class='bx bxs-x-circle'></i>
                    </div>

                    <div class="heading">
                        Payment Cancel
                    </div>
                </div>
                <p>Explore more our exciting <strong><a class="link-primary"
                            href="{{ route('tours.index') }}">tours</a></strong></p>
            </div>
        </div>
    </div>
@endsection
