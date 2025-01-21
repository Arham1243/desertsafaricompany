<div class="row">
    <div class="section-content">
        <div class="heading">
            You have {{ count($cart['tours']) }} item{{ count($cart['tours']) > 1 ? 's' : '' }} in your cart
        </div>
    </div>
    <div class="col-md-8">
        @foreach ($cart['tours'] as $tourId => $item)
            @php
                $tour = $tours->where('id', $tourId)->first();
            @endphp
            <div class="cart__product">
                <div class="cart__productImg">
                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy" loading="lazy">
                </div>
                <div class="cart__productContent">
                    <div>
                        <div class="cart__productDescription">
                            <div class="tour-type mb-1">
                                <strong>{{ $tour->formated_price_type ?? formatPrice($tour->regular_price) . ' From' }}</strong>
                            </div>
                            <h4>{{ $tour->title }}</h4>
                        </div>
                        <a href="{{ route('cart.remove', $tour->id) }}"
                            onclick="return confirm('Are you sure you want to remove this item from your cart?')"
                            class="primary-btn p-2 align-self-start bg-danger">
                            <i class='bx bxs-trash'></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        <a href="{{ route('tours.index') }}" class="primary-btn mt-4"> Continue Shopping </a>

    </div>
    <div class="col-md-4">
        <div class="checkout-details__wapper">
            <div class="checkout-details open">
                <div class="checkout-details__header">
                    <i class="bx bx-box"></i>
                    <div class="heading">Total</div>
                </div>
                <div class="checkout-details__optional ">
                    <div class="optional-wrapper">
                        <div class="optional-wrapper-padding">
                            <div class="sub-total">
                                <div class="title">Subtotal</div>
                                <div class="price">{{ formatPrice($cart['subtotal']) }}</div>
                            </div>
                            <div class="sub-total">
                                <div class="title">Service Fee</div>
                                <div class="price">{{ formatPrice($cart['service_fee']) }}</div>
                            </div>
                            <hr>
                            <div class="sub-total total all-total">
                                <div class="title">Total Payable</div>
                                <div class="price">{{ formatPrice($cart['total_price']) }}</div>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="primary-btn w-100 mt-4">Proceed
                                to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
