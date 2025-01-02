@extends('layouts.main')
@section('content')
    <!-- Checkout -->
    <div class="checkout" id="checkout.php">
        <div class="container">
            <div class="checkout-Mainheading">
                <div class="checkout-heading">
                    <h4>Product Checkout</h4>
                </div>
                <div class="checkout__order-list">
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoIcon">
                            <i class='bx bx-check'></i>
                        </div>
                        <div class="checkout__order-list__infoTitle">
                            Information
                        </div>
                    </div>
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoNum">
                            2
                        </div>
                        <div class="checkout__order-list__infoTitle">
                            Payment Details
                        </div>
                    </div>
                    <div class="checkout__order-list__info">
                        <div class="checkout__order-list__infoNum">
                            3
                        </div>
                        <div class="checkout__order-list__infoTitle">
                            Complete Order
                        </div>
                    </div>
                </div>
            </div>

            <form action="#">
                <div class="row">
                    <div class="col-12 col-lg-7">
                        <div class="checkout__billingForm">

                            <div>
                                <label for="country">Country</label>
                                <select name="country" id="country">
                                    <option value="USA">USA</option>
                                </select>
                            </div>
                            <div>
                                <label for="firstName">First Name <span>*</span></label>
                                <input type="text" name="firstName" id="firstName">
                            </div>
                            <div>
                                <label for="lastName">Last Name <span>*</span></label>
                                <input type="text" name="lastName" id="lastName">
                            </div>
                            <div>
                                <label for="email">Email <span>*</span></label>
                                <input type="email" name="email" id="email">
                            </div>
                            <div>
                                <label for="phone">Phone <span>*</span></label>
                                <input type="text" name="phone" id="phone">
                            </div>
                            <div>
                                <label for="address">Address <span>*</span></label>
                                <input type="text" name="address" id="address">
                            </div>
                            <div>
                                <label for="postal">Postal Code <span>*</span></label>
                                <input type="text" name="postal" id="postal">
                            </div>
                            <div>
                                <label for="city">City</label>
                                <input type="text" name="city" id="city">
                            </div>

                        </div>
                        <div class="checkout__billingForm mt-4">
                            <div class="checkout-heading">
                                <h4>Booking Details</h4>
                            </div>
                            <div>
                                <label for="location"> Pickup Location <span>*</span></label>
                                <input type="text" name="location" id="location">
                            </div>
                            <div class="checkout__billingForm-wp">
                                <label for="Whatsapp Number">Whatsapp Number<span>*</span></label>
                                <input type="text" name="Whatsapp Number" id="Whatsapp Number">
                            </div>
                            <div>
                                <label for="Alternative Number ">Alternative Number <span>*</span></label>
                                <input type="text" name="Alternative Number" id="Alternative Number">
                            </div>


                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="checkout__order-cardMain">
                            <div class="checkout__order-card">
                                <div class="checkout__order-card__content">
                                    <div class="checkout__order-card__img">
                                        <img src="{{ asset('frontend/assets/images/dubai-palm-helicopter-tour-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <div class="checkout__order-card__deatils">
                                        <div class="checkout__order-card__heading">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum, quos excepturi
                                        </div>
                                        <div class="checkout__order-cardQuantity-btns">
                                            <button><i class="bx bx-minus"></i></button>
                                            <input type="text" value="1">
                                            <button><i class="bx bx-plus"></i></button>
                                        </div>
                                        <div class="checkout__order-card-EDP">
                                            <div class="checkout__order-card-ED">
                                                <div class="checkout-card__editIcon">
                                                    <i class='bx bxs-edit-alt'></i>
                                                </div>
                                                <div class="checkout-card__deleteIcon">
                                                    <i class='bx bx-trash'></i>
                                                </div>
                                            </div>
                                            <div class="checkout__order-cardPrice">
                                                AED : 200.00
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="checkout__order-card">
                                <div class="checkout__order-card__content">
                                    <div class="checkout__order-card__img">
                                        <img src="{{ asset('frontend/assets/images/dubai-palm-helicopter-tour-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <div class="checkout__order-card__deatils">
                                        <div class="checkout__order-card__heading">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum, quos excepturi
                                        </div>
                                        <div class="checkout__order-cardQuantity-btns">
                                            <button class="cardQuantity-btn"><i class="bx bx-minus"></i></button>
                                            <input type="text" value="1" class="cardQuantity-input">
                                            <button class="cardQuantity-btn"><i class="bx bx-plus"></i></button>
                                        </div>
                                        <div class="checkout__order-card-EDP">
                                            <div class="checkout__order-card-ED">
                                                <div class="checkout-card__editIcon">
                                                    <i class='bx bxs-edit-alt'></i>
                                                </div>
                                                <div class="checkout-card__deleteIcon">
                                                    <i class='bx bx-trash'></i>
                                                </div>
                                            </div>
                                            <div class="checkout__order-cardPrice">
                                                AED : 200.00
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-addMoreCards">
                            <div class="checkout-heading">
                                <h4>May you Like to Add</h4>
                            </div>

                            <div class="checkout__order-card">
                                <div class="checkout__order-card__content">
                                    <div class="checkout__order-card__img">
                                        <img src="{{ asset('frontend/assets/images/dubai-palm-helicopter-tour-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <div class="checkout__order-card__deatils">
                                        <div class="checkout__order-card__heading">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum, quos excepturi
                                        </div>
                                        <div class="checkout__order-cardQuantity-btns">
                                            <button class="cardQuantity-btn"><i class="bx bx-minus"></i></button>
                                            <input type="text" value="1" class="cardQuantity-input">
                                            <button class="cardQuantity-btn"><i class="bx bx-plus"></i></button>
                                        </div>
                                        <div class="checkout__order-card-EDP">
                                            <div class="checkout__order-card-ED">
                                                <div class="checkout-card__editIcon">
                                                    <i class='bx bxs-edit-alt'></i>
                                                </div>
                                                <div class="checkout-card__deleteIcon">
                                                    <i class='bx bx-trash'></i>
                                                </div>
                                            </div>
                                            <div class="checkout__order-cardAddcart">
                                                <button class="themeBtn-round">
                                                    Add to cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout__order-card">
                                <div class="checkout__order-card__content">
                                    <div class="checkout__order-card__img">
                                        <img src="{{ asset('frontend/assets/images/dubai-palm-helicopter-tour-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <div class="checkout__order-card__deatils">
                                        <div class="checkout__order-card__heading">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum, quos excepturi
                                        </div>
                                        <div class="checkout__order-cardQuantity-btns">
                                            <button class="cardQuantity-btn"><i class="bx bx-minus"></i></button>
                                            <input type="text" value="1" class="cardQuantity-input">
                                            <button class="cardQuantity-btn"><i class="bx bx-plus"></i></button>
                                        </div>
                                        <div class="checkout__order-card-EDP">
                                            <div class="checkout__order-card-ED">
                                                <div class="checkout-card__editIcon">
                                                    <i class='bx bxs-edit-alt'></i>
                                                </div>
                                                <div class="checkout-card__deleteIcon">
                                                    <i class='bx bx-trash'></i>
                                                </div>
                                            </div>
                                            <div class="checkout__order-cardTocart">
                                                <button class="themeBtn-round">
                                                    Add to cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="checkout__orderOverview">
                            <div class="checkout-heading">
                                <h4>Order Overview</h4>
                            </div>
                            <div>
                                <span>Sub Total</span>
                                <span>$53.97</span>
                            </div>
                            <div>
                                <span>Discount Vouchers</span>
                                <span>$0.00</span>
                            </div>
                            <div>
                                <span>Estimated VAT</span>
                                <span>$0.00</span>
                            </div>
                            <div class="checkout__orderOverviewTotal">
                                <span>Total</span>
                                <span>$53.97</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
