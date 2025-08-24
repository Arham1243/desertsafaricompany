<div class="row">
    <div class="section-content">
        <div class="heading">
            You have @{{ cartTours.length }} item@{{ cartTours.length > 1 ? 's' : '' }} in your cart
        </div>
    </div>
    <input type="hidden" name="cart" :value="JSON.stringify(cart)" ref="cartInputValue">
    <div class="col-md-8">
        <template v-for="tour in cartTours" :key="tour.id">
            <div class="cart__product">
                <a :href="tour.detail_url" class="cart__productImg">
                    <img :data-src="getImageUrl(tour.featured_image)" :alt="tour.featured_image_alt_text ?? 'image'"
                        class="imgFluid lazy" loading="lazy">
                </a>

                <div class="cart__productContent">
                    <div>
                        <div class="cart__productDescription">
                            <div class="tour-pricing__date"
                                v-if="cart.tours[tour.id] && cart.tours[tour.id].start_date">
                                <small>Start Date: @{{ formatDate(cart.tours[tour.id].start_date) }}</small>
                            </div>
                            <h4 class="mb-3">@{{ tour.title }}</h4>

                            <template v-if="tour.price_type === 'normal'">
                                @include('frontend.tour.cart.pricing.normal')
                            </template>
                            <template v-else-if="tour.price_type === 'water'">
                                @include('frontend.tour.cart.pricing.water')
                            </template>
                            <template v-else-if="tour.price_type === 'promo'">
                                @include('frontend.tour.cart.pricing.promo')
                            </template>
                            <template v-else-if="tour.price_type === 'private'">
                                @include('frontend.tour.cart.pricing.private')
                            </template>

                        </div>
                        <button type="button" @click="removeTour(tour.id)"
                            class="primary-btn p-2 align-self-start bg-danger">
                            <i class='bx bxs-trash'></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
    <div class="col-md-4">
        <div class="checkout-details__wapper" style="
        position: sticky;
        top: 1rem;
    ">
            <div class="checkout-details open">
                <div class="checkout-details__header">
                    <div class="title-content">
                        <i class="bx bx-box"></i>
                        <div class="heading">Total</div>
                    </div>
                </div>
                <div class="checkout-details__optional ">
                    <div class="optional-wrapper">
                        <div class="optional-wrapper-padding">
                            <div class="sub-total">
                                <div class="title">Subtotal</div>
                                <div class="price"><span v-html="formatPrice(totalPrice)"></span></div>
                            </div>
                            <hr>
                            <div class="sub-total total all-total">
                                <div class="title">Total Payable</div>
                                <div class="price"><span v-html="formatPrice(totalPrice)"></span></div>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="primary-btn w-100 mt-4">Proceed
                                to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
