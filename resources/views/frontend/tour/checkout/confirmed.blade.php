@extends('frontend.layouts.main')
@section('content')
    <div class="cart section-padding">
        <div class="container">
            <div class="text-center">
                <div class="section-content">
                    <div class="check-icon green"><i class='bx bxs-check-circle'></i></div>

                    <div class="heading">
                        Order Confirmed!
                    </div>
                </div>
                <p>Thank you for your purchase! Your order was processed successfully. <br>
                    Your booking is confirmed — get ready for an amazing adventure!</p>
                <a href="" class="primary-btn mx-auto">View My Bookings</a>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        const url = new URL(window.location);
        url.searchParams.delete('order_id');
        window.history.replaceState({}, document.title, url);
    </script>
@endpush
