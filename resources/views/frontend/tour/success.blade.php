@extends('frontend.layouts.main')
@section('content')
    <div class="cart section-padding">
        <div class="container">
            <div class="text-center">
                <div class="section-content">
                    <div class="check-icon green"><i class='bx bxs-check-circle'></i></div>

                    <div class="heading">
                        Payment Successful!
                    </div>
                </div>
                <p>Thank you for your purchase! Your payment was processed successfully. <br> You can now explore our
                    exciting and plan your next
                    adventure.</p>
                <a href="{{ route('tours.index') }}" class="primary-btn mx-auto">Browse Tours</a>

            </div>
        </div>
    </div>
@endsection
