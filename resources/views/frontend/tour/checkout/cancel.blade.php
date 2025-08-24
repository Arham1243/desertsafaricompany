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
                        Payment Canceled
                    </div>
                </div>
                <p>Your payment was not completed. If you encountered any issues,<br>
                    please contact our support team at
                    <strong><a
                            href="mailto:{{ $settings->get('support_email') ?? 'support@desertsafaricompany.com' }}">{{ $settings->get('support_email') ?? 'support@desertsafaricompany.com' }}</a></strong>.
                </p>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        const url = new URL(window.location);
        url.search = '';
        window.history.replaceState({}, document.title, url);
    </script>
@endpush
