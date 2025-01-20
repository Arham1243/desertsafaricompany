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
                        Something went wrong during the process
                    </div>
                </div>
                @if (session('notify_error'))
                    <p class="my-2">
                        <strong>{{ session('notify_error') }}</strong>
                    </p>
                @endif
                <p> please contact our support at <strong><a
                            href="mailto:support@desertsafaricompany.com">support@desertsafaricompany.com</a></strong>.</p>
            </div>
        </div>
    </div>
@endsection
