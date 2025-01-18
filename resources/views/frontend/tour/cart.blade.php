@extends('frontend.layouts.main')
@section('content')
    <div class="cart">
        <div class="container">
            <form action="" method="POST">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="cart__product">
                                    <div class="cart__productImg">
                                        <img src="{{ asset('frontend/assets/images/Dubai-evening.webp') }}"
                                            alt="Product Image" loading="lazy">
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
                                            </div>
                                            <div class="cart__productQuantity-main">
                                                <div class="cart__productQuantity">
                                                    <div class="cart__product-title">
                                                        For Childs:
                                                    </div>
                                                    <div class="cart__productQuantity-btns">
                                                        <button><i class='bx bx-minus'></i></i></button>
                                                        <input type="text" value="1">
                                                        <button><i class='bx bx-plus'></i></i></button>
                                                    </div>

                                                </div>

                                                <div class="cart__productQuantity mt-4 mb-4">
                                                    <div class="cart__product-title">
                                                        For Adults:
                                                    </div>
                                                    <div class="cart__productQuantity-btns">
                                                        <button><i class='bx bx-minus'></i></i></button>
                                                        <input type="text" value="1">
                                                        <button><i class='bx bx-plus'></i></i></button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div>
                                            <div class="cart__productOptions">
                                                <button class="themeBtn-round active">
                                                    <i class='bx bx-trash'></i> Remove from Cart
                                                </button>
                                                <button class="themeBtn-round">
                                                    <i class='bx bx-heart'></i> Move to Wishlist
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="cart__product">
                                    <div class="cart__productImg">
                                        <img src="{{ asset('frontend/assets/images/Dubai-desert-sky.webp') }}"
                                            alt="Product Image" loading="lazy">
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
                                            </div>

                                            <div class="cart__productQuantity-main">
                                                <div class="cart__productQuantity">
                                                    <div class="cart__product-title">
                                                        No of Carts:
                                                    </div>
                                                    <div class="cart__productQuantity-btns">
                                                        <button><i class='bx bx-minus'></i></i></button>
                                                        <input type="text" value="1">
                                                        <button><i class='bx bx-plus'></i></i></button>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                        <div class="mt-5">
                                            <div class="cart__productOptions">
                                                <button class="themeBtn-round active">
                                                    <i class='bx bx-trash'></i> Remove from Cart
                                                </button>
                                                <button class="themeBtn-round">
                                                    <i class='bx bx-heart'></i> Move to Wishlist
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row cartBottom mb-5">
                            <div class="col-md-6">
                                <a href="{{ route('tours.index') }}" class="app-btn themeBtn">Keep exploring</a>
                            </div>
                            <div class="col-md-6">
                                <button class="app-btn themeBtn pull-right">Proceed to Checkout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
