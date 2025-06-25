@if ($tour->is_person_type_enabled && $tour->promoPrices->isNotEmpty())
    <div v-for="(promo, index) in visiblePromos" :key="index" class="form-group form-guest-search">
        <div class="form-guest-search__details promo">
            <div class="promo-title">@{{ promo.promo_title }}</div>
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
                        @click="updateQuantity('minus', formatNameForInput(promo.promo_title))">
                        <i class="bx bx-chevron-down"></i>
                    </button>
                    <input readonly type="number" class="quantity-counter__btn quantity-counter__btn--quantity"
                        min="0" v-model="promo.quantity"
                        :name="`price[${formatNameForInput(promo.promo_title)}][quantity]`">

                    <button class="quantity-counter__btn" type="button"
                        @click="updateQuantity('plus', formatNameForInput(promo.promo_title))">
                        <i class="bx bx-chevron-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <a href="javascript:void(0)" @click="toggleShowAll" class="see-more-promo">
        @{{ showAllPromos ? 'Show Less' : `See All ${promoTourData.length} Options` }}
    </a>

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
