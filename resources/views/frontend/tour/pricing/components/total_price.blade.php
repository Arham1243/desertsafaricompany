@if ($firstOrderCoupon)
    <div class="promo-code applied" v-if="firstOrderCoupon && !hasUsedFirstOrderCoupon && !isTourInCart">
        <div class="promo-code__info" v-if="!isFirstOrderCouponApplied">
            <div class="icon" style="rotate: 140deg;">
                <i class="bx bx-tag"></i>
            </div>
            <div class="content">
                <div class="title">
                    @{{ firstOrderCoupon.name }}
                </div>
                <div class="subtitle">Promo Code @{{ firstOrderCoupon.code }}.</div>
            </div>
        </div>

        <button v-if="!isFirstOrderCouponApplied" type="button" class="promo-code__apply"
            @click="applyFirstOrderCoupon">
            Apply
        </button>

        <div class="promo-code__info" v-else>
            <div class="icon">
                <i class="bx bxs-check-circle"></i>
            </div>
            <div class="content">
                <div class="title">Promo Applied</div>
                <div class="subtitle">Promo savings will be applied at checkout.</div>
            </div>
        </div>

        <input type="hidden" name="applied_coupons[0][coupon]" :value="firstOrderCoupon.id"
            v-if="isFirstOrderCouponApplied">
        <input type="hidden" name="applied_coupons[0][code]" :value="firstOrderCoupon.code"
            v-if="isFirstOrderCouponApplied">
        <input type="hidden" name="applied_coupons[0][type]" :value="firstOrderCoupon.discount_type"
            v-if="isFirstOrderCouponApplied">
        <input type="hidden" name="applied_coupons[0][amount]" :value="firstOrderCoupon.amount"
            v-if="isFirstOrderCouponApplied">
        <input type="hidden" name="applied_coupons[0][is_first_order_coupon]"
            :value="firstOrderCoupon.is_first_order_coupon" v-if="isFirstOrderCouponApplied">
    </div>
@endif
<div class="form-group form-guest-search">
    <div class=form-guest-search__title>
        <div class="form-guest-search__items Service-fee__content mb-2 total-price-wrapper">
            <div class=form-guest-search__title>
                Total Price:
            </div>
            <div class="tour-content__pra form-book__pra total-price">
                <input type="hidden"
                    :value="totalPrice -
                        {{ $tour->enabled_custom_service_fee === 1 && $tour->service_fee_price ? $tour->service_fee_price : 0 }}"
                    name="subtotal" />
                <input type="hidden" :value="totalPrice" name="total_price" />
                <span class="green" style="font-weight:500;" v-html="formatPrice(totalPrice)"></span>
            </div>
        </div>
    </div>
</div>
