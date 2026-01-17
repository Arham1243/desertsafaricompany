@extends('frontend.layouts.main')
@section('content')
    <div class="section-padding">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-8">
                    <div class="text-document mb-4">
                        <h3 class="subHeading mb-0">Secure Checkout with PayPal</h3>
                        <p>
                            Fast, safe, and encrypted payment. Complete your order below.
                        </p>
                        <p class="fw-bold mt-2">
                            Amount to pay: <span class="text-primary">${{ number_format($usdAmount, 2, '.', '') }}</span>
                        </p>
                    </div>
                    <div id="paypal-button-container" class="w-100"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}"></script>
    <script>
        if (window.paypal) {
            paypal.Buttons({
                commit: true,
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{ number_format($usdAmount, 2, '.', '') }}'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        window.location.href =
                            '{{ route('checkout.paypal.success', ['order_id' => $order->id]) }}';
                    });
                },
                onCancel: function(data) {
                    window.location.href = '{{ route('checkout.cancel', ['order_id' => $order->id]) }}';
                }
            }).render('#paypal-button-container');
        }
    </script>
@endpush
