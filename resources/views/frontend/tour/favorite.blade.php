@extends('layouts.main')
@section('content')
    <!-- Cart -->
    <div class="cart">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="cart__product">
                                <div class="cart__productImg">
                                    <img src="{{ asset('frontend/assets/images/Dubai-evening.webp') }}" alt="Product Image">
                                </div>
                                <div class="cart__productContent">
                                    <div>
                                        <div class="cart__productDescription">
                                            <h4>Lorem ipsum dolor</h4>
                                            <p>
                                                Lorem ipsum, dolor sit amet consectetur adipisicing
                                                elit.
                                            </p>
                                            <span class="cart__productPrice">$17.99</span>
                                            <div>
                                                <div class="cart__productOptions mt-4">
                                                    <button class="themeBtn-round active">
                                                        <i class='bx bx-trash'></i> Remove from Wishlist
                                                    </button>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-12">
                            <div class="cart__product">
                                <div class="cart__productImg">
                                    <img src="{{ asset('frontend/assets/images/Dubai-evening.webp') }}" alt="Product Image">
                                </div>
                                <div class="cart__productContent">
                                    <div>
                                        <div class="cart__productDescription">
                                            <h4>Lorem ipsum dolor</h4>
                                            <p>
                                                Lorem ipsum, dolor sit amet consectetur adipisicing
                                                elit.
                                            </p>
                                            <span class="cart__productPrice">$17.99</span>

                                            <div>
                                                <div class="cart__productOptions mt-4">
                                                    <button class="themeBtn-round active">
                                                        <i class='bx bx-trash'></i> Remove from Wishlist
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection
