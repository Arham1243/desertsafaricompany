<div class="tour-pricing-wrapper">
    <template v-for="(pkg, pkgIndex) in getTourPackages(tour.id)" :key="pkgIndex">
        <div class="tour-pricing">
            <div class="tour-pricing__header">
                <div class="tour-pricing__title">
                    <h5 v-html="pkg.promo_title || pkg.title"></h5>
                </div>
                <div class="tour-pricing__price">
                    <template v-if="pkg.promo_is_free == 1">
                        <div class="price-display">
                            <span class="final-price">FREE</span>
                        </div>
                    </template>
                    <template v-else-if="pkg.type === 'simple' || !pkg.type">
                        <div class="price-display">
                            <span class="final-price" v-html="formatPrice(getCorrectPrice(pkg))"></span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="tour-pricing__content">
                <!-- Simple Package or Main Promo -->
                <template v-if="pkg.type === 'simple' || !pkg.type">
                    <div class="tour-pricing__quantity">
                        
                        <div class="quantity-controls">
                            <button type="button" @click="updatePromoQuantity('minus', null, tour, pkgIndex)"
                                class="quantity-btn minus" :disabled="pkg.quantity <= (parseInt(pkg.min_person) || 0)">
                                <i class="bx bx-minus"></i>
                            </button>
                            <span class="quantity-display">@{{ pkg.quantity }}</span>
                            <button type="button" @click="updatePromoQuantity('plus', null, tour, pkgIndex)"
                                class="quantity-btn plus" :disabled="pkg.quantity >= (parseInt(pkg.max_person) || 999)">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                        <div class="tour-pricing__subtotal" v-if="pkg.quantity > 0">
                            <template v-if="pkg.promo_is_free == 1">
                                <small>Subtotal: FREE</small>
                            </template>
                            <template v-else>
                                <small>Subtotal: <span
                                        v-html="formatPrice((pkg.is_first_order_coupon_applied ? pkg.promo_discounted_price : pkg.discounted_price) * pkg.quantity)"></span></small>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Timeslot Package -->
                <template v-else-if="pkg.type === 'timeslot'">
                    <div class="timeslot-addon">
                        <div class="tour-pricing__quantity mb-4">
                            
                            <div class="quantity-controls">
                                <button type="button" @click="updatePromoQuantity('minus', null, tour, pkgIndex)"
                                    class="quantity-btn minus" :disabled="pkg.quantity <= (parseInt(pkg.min_person) || 0)">
                                    <i class="bx bx-minus"></i>
                                </button>
                                <span class="quantity-display">@{{ pkg.quantity }}</span>
                                <button type="button" @click="updatePromoQuantity('plus', null, tour, pkgIndex)"
                                    class="quantity-btn plus" :disabled="pkg.quantity >= (parseInt(pkg.max_person) || 999)">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            <div class="tour-pricing__subtotal mt-2"
                                v-if="pkg.quantity > 0 && pkg.selected_slots && pkg.selected_slots.filter(s => s).length > 0">
                                <small>Subtotal:
                                    <span
                                        v-html="formatPrice(pkg.selected_slots.reduce((total, slotTime) => {
                                        if (!slotTime) return total;
                                        const slot = pkg.slots.find(s => s.time === slotTime);
                                        return total + (slot ? parseFloat(slot.discounted_price || slot.promo_discounted_price || 0) : 0);
                                    }, 0))"></span>
                                </small>
                            </div>
                        </div>

                        <div class="timeslot-group-wrapper" v-if="pkg.quantity > 0">
                            <div class="row" v-for="n in pkg.quantity" :key="n">
                                <div class="col-12 mb-2">
                                    <label class="timeslot-label mb-1">#@{{ n }} Duration</label>
                                    <select class="form-select timeslot-select mb-2" v-model="pkg.selected_slots[n - 1]"
                                        @change="handleSelectedSlotChange(pkg)">
                                        <option value="">Select Duration</option>
                                        <option v-for="slot in pkg.slots" :key="slot.time" :value="slot.time">
                                            @{{ formatTimeLabel(slot.time) }} —
                                            <span
                                                v-html="formatPrice(slot.is_first_order_coupon_applied ? slot.promo_discounted_price : slot.discounted_price)"></span>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

    <!-- Tour Service Fee and Extra Prices -->
    <div class="tour-additional-costs" v-if="cart.tours[tour.id]">
        <template v-if="cart.tours[tour.id].service_fee && cart.tours[tour.id].service_fee > 0">
            <div class="additional-cost-item">
                <span class="cost-label">Service Fee:</span>
                <span class="cost-amount" v-html="formatPrice(cart.tours[tour.id].service_fee)"></span>
            </div>
        </template>

        <template v-if="cart.tours[tour.id].extra_prices && cart.tours[tour.id].extra_prices.length > 0">
            <div class="additional-cost-item" v-for="(extra, extraIndex) in cart.tours[tour.id].extra_prices"
                :key="extraIndex">
                <span class="cost-label" v-html="extra.name"></span>
                <span class="cost-amount" v-html="formatPrice(extra.price)"></span>
            </div>
        </template>
    </div>

    <!-- Tour Total -->
    <div class="tour-total" v-if="cart.tours[tour.id] && cart.tours[tour.id].total_price > 0">
        <div class="total-display">
            <span class="total-label">Tour Total:</span>
            <span class="total-amount" v-html="formatPrice(cart.tours[tour.id].total_price)"></span>
        </div>
    </div>
</div>

@push('css')
    <style>
        .tour-pricing-wrapper {
            margin-bottom: 20px;
        }

        .tour-pricing {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .tour-pricing__header {
            background: #f8f9fa;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .tour-pricing__title h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .addon-label {
            background: var(--color-primary);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .tour-pricing__date {
            margin-top: 5px;
        }

        .tour-pricing__date small {
            color: #666;
            font-size: 12px;
        }

        .price-display .final-price {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }

        .tour-pricing__content {
            padding: 15px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover:not(:disabled) {
            background: #f8f9fa;
            border-color: var(--color-primary);
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
        }

        .quantity-info small {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .tour-pricing__subtotal {
            border-top: 1px solid #f0f0f0;
        }

        .timeslot-group-wrapper {
            margin-top: 15px;
        }

        .timeslot-label {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .timeslot-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s;
        }

        .timeslot-select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .timeslot-select option {
            padding: 8px;
        }

        .tour-additional-costs {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
        }

        .additional-cost-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .additional-cost-item:last-child {
            margin-bottom: 0;
        }

        .cost-label {
            font-weight: 500;
            color: #666;
        }

        .cost-amount {
            font-weight: 600;
            color: #333;
        }

        .tour-total {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            border: 1px solid #28a745;
        }

        .total-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }

        .tour-pricing__quantity {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @media (max-width: 768px) {
            .tour-pricing__header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .timeslot-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
