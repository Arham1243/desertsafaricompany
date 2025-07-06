@if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
    <template v-if="!fetchingPromoPrices">
        <div v-for="(promo, index) in visiblePromos" :key="index" class="form-group form-guest-search">
            <div class="form-guest-search__details promo" v-if="promo.source === 'promo'">
                <div class="promo-title">@{{ promo.title }}</div>
                <div class="promo-price-wrapper">
                    <div class="promo-price cut">@{{ formatPrice(promo.original_price) }}</div>
                    <div class="promo-price green">@{{ formatPrice(promo.discounted_price) }}</div>
                    <span class="percent-off-tag">@{{ promo.discount_percent }}% Off</span>
                </div>
                <div class="promo-og-offer">
                    <span class="offer">@{{ formatPrice(promo.discounted_price) }} with promo</span>
                    <span :class="['time-left', promo.hours_left <= 2 ? 'blink-red' : '']">
                        @{{ promo.hours_left }} hour@{{ promo.hours_left === 1 ? '' : 's' }} left
                    </span>
                </div>
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
                        <input @change="handleAddonSelection(addOn)" type="checkbox" class="promo-addon-check"
                            v-model="addOn.is_selected" />

                        <div class="promo-info-wrapper">
                            <div class="promo-title">
                                @{{ addOn.title }}
                            </div>
                            <div class="promo-price-wrapper">
                                <div class="promo-price cut">@{{ formatPrice(addOn.original_price) }}</div>
                                <div class="promo-price green">@{{ formatPrice(addOn.discounted_price) }}</div>
                                <span class="percent-off-tag">@{{ addOn.discount_percent }}% Off</span>
                                <div class="text-muted small"style="margin-bottom: .15rem;">per person</div>
                            </div>
                            <div class="promo-og-offer">
                                <span class="offer">@{{ formatPrice(addOn.discounted_price) }} with promo</span>
                                <span :class="['time-left', addOn.hours_left <= 2 ? 'blink-red' : '']">
                                    @{{ addOn.hours_left }} hour@{{ addOn.hours_left === 1 ? '' : 's' }} left
                                </span>
                            </div>
                            <div v-if="addOn.is_selected" class="form-guest-search__items justify-content-between"
                                style="margin-top: 0.25rem">
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
                        <input @change="handleAddonSelection(addOn)" type="checkbox" class="promo-addon-check"
                            v-model="addOn.is_selected" />

                        <div class="promo-info-wrapper">
                            <div class="promo-title">@{{ addOn.title }}</div>

                            <div class="promo-price-wrapper">
                                <div class="promo-price cut">@{{ formatPrice(addOn.slots[0].original_price) }}</div>
                                <div class="promo-price green">@{{ formatPrice(addOn.slots[0].discounted_price) }}</div>
                                <span class="percent-off-tag">@{{ addOn.discount_percent }}% Off</span>
                                <div class="text-muted small">per person</div>
                            </div>

                            <div class="promo-og-offer">
                                <span class="offer">@{{ formatPrice(addOn.slots[0].discounted_price) }} with promo</span>
                                <span :class="['time-left', addOn.hours_left <= 2 ? 'blink-red' : '']">
                                    @{{ addOn.hours_left }} hour@{{ addOn.hours_left === 1 ? '' : 's' }} left
                                </span>
                            </div>

                            <div v-if="addOn.is_selected" class="form-guest-search__items justify-content-between"
                                style="margin-top: 0.25rem">
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
                            <div class="timeslot-group-wrapper" v-if="addOn.is_selected && addOn.quantity > 0">
                                <div class="row" v-for="n in addOn.quantity" :key="n">
                                    <div class="col-12 mb-2">
                                        <label class="promo-title mb-0">#@{{ n }} Duration</label>
                                        <select class="select-field mt-1" v-model="addOn.selected_slots[n - 1]"
                                            @change="handleSelectedSlotChange(addOn)">
                                            <option v-for="slot in addOn.slots" :key="slot.time"
                                                :value="slot.time">
                                                @{{ formatTimeLabel(slot.time) }} — @{{ formatPrice(slot.discounted_price) }}
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

    <div class="promo-code applied">
        <div class="promo-code__info">
            <div class="icon" style="rotate: 140deg;">
                <i class="bx bx-tag"></i>
            </div>
            <div class="content">
                <div class="title">
                    Extra 10% off
                </div>
                <div class="subtitle">Promo Code PROMO.</div>
            </div>
        </div>
        <button type="button" class='promo-code__apply'>Apply</button>
        {{-- <div class="promo-code__info">
            <div class="icon">
                <i class="bx bxs-check-circle"></i>
            </div>
            <div class="content">
                <div class="title">
                    Promo Applied
                </div>
                <div class="subtitle">Promo savings will be applied to eligible options at checkout.</div>
            </div>
        </div> --}}
    </div>
@endif
@push('css')
    <style>
        .form-book__title {
            padding-bottom: 0 !important;
        }
    </style>
@endpush
