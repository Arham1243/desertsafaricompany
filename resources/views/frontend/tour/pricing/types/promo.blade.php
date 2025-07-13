@if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
    <template v-if="!fetchingPromoPrices">
        <div v-for="(promo, index) in visiblePromos" :key="index" class="form-group form-guest-search">
            <div class="form-guest-search__details promo" v-if="promo.source === 'promo'">
                <div class="promo-title" v-html="highlightTitle(promo.title,'text-red')"></div>
                <div class="promo-price-wrapper">
                    <div class="promo-price cut"><span v-html="formatPrice(promo.original_price)"></span></div>
                    <div v-if="promo.original_discounted_price" class="promo-price green price-cut"><span
                            v-html="formatPrice(promo.original_discounted_price)"></span>
                    </div>
                    <div v-else class="promo-price green"><span v-html="formatPrice(promo.discounted_price)"></span>
                    </div>
                    <div v-if="promo.original_discounted_price" class="promo-price purple"><span
                            v-html="formatPrice(promo.discounted_price)"></span></div>
                    <span v-if="!promo.original_discounted_price" class="percent-off-tag">@{{ promo.discount_percent }}%
                        Off</span>
                </div>
                <div class="promo-og-offer purple">
                    <span class="offer" v-if="promo.original_discounted_price"><span
                            v-html="formatPrice(promo.original_discounted_price)"></span> with promo</span>
                    <span class="offer" v-else><span v-html="formatPrice(promo.discounted_price)"></span> with
                        promo</span>
                    <span :class="['time-left', promo.hours_left <= 2 ? 'blink-red' : '']">
                        @{{ promo.hours_left }} hour@{{ promo.hours_left === 1 ? '' : 's' }} left
                    </span>
                </div>
                <span v-if="isFirstOrderCouponrApplied" class="promo-applied purple  d-flex align-items-center mt-1"> <i
                        style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied</span>
                <div class="form-guest-search__items justify-content-between" style="margin-top: 0.25rem">
                    <div class="already-bought"></div>
                    <div class="quantity-counter">
                        <button class="quantity-counter__btn" type="button"
                            @click="updateQuantity('minus', formatNameForInput(promo.title))">
                            <i class="bx bx-chevron-down"></i>
                        </button>
                        <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                            min="0" v-model="promo.quantity"
                            :name="`price[${formatNameForInput(promo.title)}][quantity]`">

                        <button class="quantity-counter__btn" type="button"
                            @click="updateQuantity('plus', formatNameForInput(promo.title))">
                            <i class="bx bx-chevron-up"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <a v-if="promoTourData.filter(p => p.source === 'promo').length > 4" href="javascript:void(0)"
            @click="toggleShowAll" class="see-more-promo">
            @{{ showAllPromos ? 'Show Less' : `See All ${promoTourData.filter(p => p.source === 'promo').length} Options` }}
        </a>
        <div class="promo-addOns-wrapper" v-if="hasAnyPromoQuantity && promoAddOnsTourData.length > 0">
            <div class="form-guest-search__title"> Available Upgrades </div>
            <template v-for="(addOn, index) in promoAddOnsTourData" :key="index">
                <div class="form-group form-guest-search promo-addOn" v-if="addOn.type === 'simple'">
                    <div class="form-guest-search__details promo pt-3">
                        <div class="promo-info-wrapper">
                            <div class="promo-title" v-html="highlightTitle(addOn.title,'text-red')"></div>
                            <div class="promo-price-wrapper">
                                <div class="promo-price cut"><span v-html="formatPrice(addOn.original_price)"></span>
                                </div>
                                <div v-if="addOn.original_discounted_price" class="promo-price green price-cut">
                                    <span v-html="formatPrice(addOn.original_discounted_price)"></span>
                                </div>
                                <div v-else class="promo-price green"><span
                                        v-html="formatPrice(addOn.discounted_price)"></span></div>
                                <div v-if="addOn.original_discounted_price" class="promo-price purple">
                                    <span v-html="formatPrice(addOn.discounted_price)"></span>
                                </div>
                                <span v-if="!addOn.original_discounted_price"
                                    class="percent-off-tag">@{{ addOn.discount_percent }}% Off</span>
                            </div>
                            <div class="promo-og-offer purple">
                                <span class="offer" v-if="addOn.original_discounted_price"><span
                                        v-html="formatPrice(addOn.original_discounted_price)"></span> with
                                    promo</span>
                                <span class="offer" v-else><span v-html="formatPrice(addOn.discounted_price)"></span>
                                    with promo</span>
                                <span :class="['time-left', addOn.hours_left <= 2 ? 'blink-red' : '']">
                                    @{{ addOn.hours_left }} hour@{{ addOn.hours_left === 1 ? '' : 's' }} left
                                </span>
                            </div>
                            <span v-if="isFirstOrderCouponrApplied"
                                class="promo-applied purple  d-flex align-items-center mt-1"> <i
                                    style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied</span>
                            <div class="form-guest-search__items justify-content-between" style="margin-top: 0.25rem">
                                <div class="already-bought"></div>
                                <div class="quantity-counter">
                                    <button class="quantity-counter__btn" type="button"
                                        @click="updateQuantity('minus', formatNameForInput(addOn.title))">
                                        <i class="bx bx-chevron-down"></i>
                                    </button>
                                    <input readonly type="number"
                                        class="quantity-counter__btn quantity-counter__btn--quantity" min="0"
                                        v-model="addOn.quantity"
                                        :name="`price[${formatNameForInput(addOn.title)}][quantity]`">

                                    <button class="quantity-counter__btn" type="button"
                                        @click="updateQuantity('plus', formatNameForInput(addOn.title))">
                                        <i class="bx bx-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-guest-search promo-addOn"
                    v-else-if="addOn.type === 'timeslot' && addOn.slots?.length > 0">
                    <div class="form-guest-search__details promo pt-3">
                        <div class="promo-info-wrapper">
                            <div class="promo-title" v-html="highlightTitle(addOn.title,'text-red')"></div>
                            <div class="promo-price-wrapper">
                                <div class="promo-price cut"><span
                                        v-html="formatPrice(addOn.slots[0].original_price)"></span></div>
                                <div v-if="addOn.slots[0].original_discounted_price"
                                    class="promo-price green price-cut">
                                    <span v-html="formatPrice(addOn.slots[0].original_discounted_price)"></span>
                                </div>
                                <div v-else class="promo-price green"><span
                                        v-html="formatPrice(addOn.slots[0].discounted_price)"></span></div>
                                <div v-if="addOn.slots[0].original_discounted_price" class="promo-price purple">
                                    <span v-html="formatPrice(addOn.slots[0].discounted_price)"></span>
                                </div>
                                <span v-if="!addOn.slots[0].original_discounted_price"
                                    class="percent-off-tag">@{{ addOn.discount_percent }}% Off</span>
                            </div>

                            <div class="promo-og-offer purple">
                                <span class="offer" v-if="addOn.slots[0].original_discounted_price"><span
                                        v-html="formatPrice(addOn.slots[0].original_discounted_price)"></span> with
                                    promo</span>
                                <span class="offer" v-else><span
                                        v-html="formatPrice(addOn.slots[0].discounted_price)"></span> with promo</span>
                                <span :class="['time-left', addOn.hours_left <= 2 ? 'blink-red' : '']">
                                    @{{ addOn.hours_left }} hour@{{ addOn.hours_left === 1 ? '' : 's' }} left
                                </span>
                            </div>
                            <span v-if="isFirstOrderCouponrApplied"
                                class="promo-applied purple  d-flex align-items-center mt-1"> <i
                                    style="font-size:1.1rem;" class="bx bxs-check-circle"></i> Promo Applied</span>
                            <div class="form-guest-search__items justify-content-between" style="margin-top: 0.25rem">
                                <div class="already-bought"></div>
                                <div class="quantity-counter">
                                    <button class="quantity-counter__btn" type="button"
                                        @click="updateQuantity('minus', formatNameForInput(addOn.title))">
                                        <i class="bx bx-chevron-down"></i>
                                    </button>
                                    <input readonly type="number"
                                        class="quantity-counter__btn quantity-counter__btn--quantity" min="0"
                                        v-model="addOn.quantity"
                                        :name="`price[${formatNameForInput(addOn.title)}][quantity]`">

                                    <button class="quantity-counter__btn" type="button"
                                        @click="updateQuantity('plus', formatNameForInput(addOn.title))">
                                        <i class="bx bx-chevron-up"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="timeslot-group-wrapper" v-if="addOn.quantity > 0">
                                <div class="row" v-for="n in addOn.quantity" :key="n">
                                    <div class="col-12 mb-2">
                                        <label class="promo-title mb-0">#@{{ n }} Duration</label>
                                        <select class="select-field mt-1 dirham" v-model="addOn.selected_slots[n - 1]"
                                            @change="handleSelectedSlotChange(addOn)">
                                            <option v-for="slot in addOn.slots" :key="slot.time"
                                                :value="slot.time">
                                                <span v-html="formatTimeLabel(slot.time)"></span> — <span
                                                    v-html="formatPrice(slot.discounted_price)"></span>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
    <template v-else>
        <div v-for="i in 3" :key="i" class="form-group form-guest-search">
            <div class="form-guest-search__details promo">
                <div class="skeleton skeleton-body mb-2"></div>
                <div class="skeleton skeleton-title mb-2"></div>
                <div class="skeleton skeleton-subtitle"></div>
            </div>
        </div>
    </template>
@endif
@push('css')
    <style>
        .form-book__title {
            padding-bottom: 0 !important;
        }
    </style>
@endpush
