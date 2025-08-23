@php
    $now = now();
    $today = strtolower($now->englishDayOfWeek);
    $hourOfDay = $now->hour;

    $isWeekend = in_array($today, ['friday', 'saturday', 'sunday']);

    $config = $tour->promo_discount_config ? json_decode($tour->promo_discount_config, true) : [];

    $discountPercent = $isWeekend ? $config['weekend_discount_percent'] ?? 0 : $config['weekday_discount_percent'] ?? 0;

    $timerHours = (int) ($isWeekend ? $config['weekend_timer_hours'] ?? 0 : $config['weekday_timer_hours'] ?? 0);
    $hoursLeft =
        $timerHours > 0 ? ($hourOfDay % $timerHours === 0 ? $timerHours : $timerHours - ($hourOfDay % $timerHours)) : 0;

    $promoData = collect();

    $promoData = $promoData->concat(
        $tour->promoPrices->map(function ($promoPrice) use ($firstOrderCoupon, $discountPercent, $hoursLeft) {
            $original = (float) $promoPrice->original_price;
            $discounted = $original - $original * ($discountPercent / 100);

            return [
                'source' => 'promo',
                'title' => $promoPrice->promo_title,
                'slug' => $promoPrice->promo_slug,
                'promo_is_free' => (int) $promoPrice->promo_is_free,
                'original_price' => number_format($original, 2),
                'discount_percent' => $discountPercent,
                'original_discounted_price' => number_format($discounted, 2),
                'discounted_price' => number_format($discounted, 2),
                'promo_discounted_price' => $firstOrderCoupon
                    ? applyPromoDiscount(
                        number_format($discounted, 2),
                        $firstOrderCoupon->discount_type,
                        $firstOrderCoupon->amount,
                    )
                    : null,
                'quantity' => 0,
                'hours_left' => $hoursLeft,
            ];
        }),
    );

    $promoData = $promoData->concat(
        $tour->promoAddons->flatMap(function ($pricing) use ($firstOrderCoupon, $hoursLeft) {
            $addons = json_decode($pricing->promo_addons ?? '[]', true);

            return collect($addons)
                ->map(function ($addon) use ($firstOrderCoupon, $hoursLeft) {
                    if ($addon['type'] === 'simple') {
                        $original = floatval($addon['price']);
                        $discountPercent = floatval($addon['discounted_percent'] ?? 0);
                        $discounted = $original - ($original * $discountPercent) / 100;
                        return [
                            'source' => 'addon',
                            'type' => 'simple',
                            'title' => $addon['title'],
                            'slug' => $addon['promo_slug'],
                            'original_price' => number_format($original, 2),
                            'discount_percent' => $discountPercent,
                            'original_discounted_price' => number_format($discounted, 2),
                            'discounted_price' => number_format($discounted, 2),
                            'promo_discounted_price' => $firstOrderCoupon
                                ? applyPromoDiscount(
                                    number_format($discounted, 2),
                                    $firstOrderCoupon->discount_type,
                                    $firstOrderCoupon->amount,
                                )
                                : null,
                            'quantity' => 0,
                            'hours_left' => $hoursLeft,
                        ];
                    }

                    if ($addon['type'] === 'timeslot') {
                        $slots = collect($addon['slots'] ?? []);
                        $firstSlotDiscount = floatval($slots[0]['discounted_percent'] ?? 0);

                        return [
                            'source' => 'addon',
                            'type' => 'timeslot',
                            'title' => $addon['title'],
                            'slug' => $addon['promo_slug'],
                            'original_discounted_price' => $firstSlotDiscount,
                            'discounted_price' => $firstSlotDiscount,
                            'promo_discounted_price' => $firstOrderCoupon
                                ? applyPromoDiscount(
                                    number_format($firstSlotDiscount, 2),
                                    $firstOrderCoupon->discount_type,
                                    $firstOrderCoupon->amount,
                                )
                                : null,
                            'hours_left' => $hoursLeft,
                            'quantity' => 0,
                            'selected_slots' => [],
                            'slots' => $slots
                                ->map(function ($slot) use ($firstOrderCoupon) {
                                    $price = floatval($slot['price']);
                                    $discountPercent = floatval($slot['discounted_percent'] ?? 0);
                                    $discounted = $price - ($price * $discountPercent) / 100;

                                    return [
                                        'time' => $slot['time'],
                                        'original_price' => number_format($price, 2),
                                        'discount_percent' => $discountPercent,
                                        'original_discounted_price' => number_format($discounted, 2),
                                        'discounted_price' => number_format($discounted, 2),
                                        'promo_discounted_price' => $firstOrderCoupon
                                            ? applyPromoDiscount(
                                                number_format($discounted, 2),
                                                $firstOrderCoupon->discount_type,
                                                $firstOrderCoupon->amount,
                                            )
                                            : null,
                                    ];
                                })
                                ->values(),
                        ];
                    }

                    return null;
                })
                ->filter();
        }),
    );

    $promoTourData = $promoData->values();

    $normalTourData = $tour->normalPrices->mapWithKeys(function ($price) use ($firstOrderCoupon) {
        $originalPrice = (float) $price->price;

        $promoDiscountedPrice = $firstOrderCoupon
            ? applyPromoDiscount($originalPrice, $firstOrderCoupon->discount_type, $firstOrderCoupon->amount)
            : null;

        return [
            formatNameForInput($price->person_type) => [
                'person_type' => $price->person_type,
                'person_description' => $price->person_description,
                'original_price' => $originalPrice,
                'promo_discounted_price' => $promoDiscountedPrice,
                'min' => (int) $price->min_person,
                'max' => (int) $price->max_person,
                'quantity' => 0,
            ],
        ];
    });

    $simpleTourData = [
        'regular_price' => $tour->regular_price,
        'sale_price' => $tour->sale_price,
        'original_price' => $tour->sale_price ?? $tour->regular_price,
        'promo_discounted_price' => applyPromoDiscount(
            $tour->sale_price ?? $tour->regular_price,
            $firstOrderCoupon->discount_type,
            $firstOrderCoupon->amount,
        ),
    ];

    $waterTourData = $tour->waterPrices->mapWithKeys(function ($waterPrice) use ($firstOrderCoupon) {
        return [
            $waterPrice->time => [
                'time' => $waterPrice->time,
                'original_price' => (int) $waterPrice->water_price,
                'promo_discounted_price' => applyPromoDiscount(
                    $waterPrice->water_price,
                    $firstOrderCoupon->discount_type,
                    $firstOrderCoupon->amount,
                ),
                'quantity' => 0,
            ],
        ];
    });

    $privateTourData = $tour->privatePrices
        ? [
            'min_person' => (int) $tour->privatePrices->min_person,
            'max_person' => (int) $tour->privatePrices->max_person,
            'car_price' => (int) $tour->privatePrices->car_price,
            'original_price' => (int) $tour->privatePrices->car_price,
            'promo_discounted_price' => applyPromoDiscount(
                $tour->privatePrices->car_price,
                $firstOrderCoupon->discount_type,
                $firstOrderCoupon->amount,
            ),
            'quantity' => 0,
        ]
        : null;

    $waterPricesTimeSlots = $waterTourData->isNotEmpty() ? array_keys($waterTourData->toArray()) : [];

    $promoDiscountConfig =
        isset($tour->promo_discount_config) && $tour->promo_discount_config
            ? json_decode($tour->promo_discount_config, true)
            : [];
    $weekend_discount_percent = $promoDiscountConfig['weekend_discount_percent'] ?? 0;
    $weekday_discount_percent = $promoDiscountConfig['weekday_discount_percent'] ?? 0;
@endphp
<script>
    const PricingBox = createApp({
        setup() {
            const totalPrice = ref(parseFloat("{{ $tour->initial_price ?? 0 }}"));
            const priceType = "{{ $tour->price_type ?? 'simple' }}";

            const waterTourData = ref(@json($waterTourData));
            const waterPricesTimeSlots = ref(@json($waterPricesTimeSlots));
            const timeSlot = ref("");

            const firstOrderCoupon = ref(@json($firstOrderCoupon));
            const tourId = ref(@json($tour->id))
            const cartData = ref(@json($cart));
            const initialTotalPrice = parseFloat("{{ $tour->initial_price ?? 0 }}");
            const normalTourData = ref(@json($normalTourData));
            const privateTourData = ref(@json($privateTourData));
            const promoTourData = ref(@json($promoTourData));
            const simpleTourData = ref(@json($simpleTourData));
            const isSubmitEnabled = ref(false);
            const showAllPromos = ref(false)
            const startDateInput = ref(null)
            const fetchingPromoPrices = ref(null)
            const isFirstOrderCouponApplied = ref(false)

            const hasUsedFirstOrderCoupon = computed(() => {
                const coupons = cartData.value?.applied_coupons
                const used = Array.isArray(coupons) && coupons.some(c => c
                    ?.is_first_order_coupon == 1)
                return used
            })

            const isTourInCart = computed(() => {
                return !!cartData.value?.tours?.[tourId.value]
            })

            const hasAnyPromoQuantity = computed(() =>
                promoTourData.value.some(item => item.quantity > 0 && item.source === 'promo')
            )

            const promoAddOnsTourData = computed(() =>
                promoTourData.value.filter(item => item.source === 'addon')
            )

            const lowestPromoOriginalPrice = computed(() => {
                if (!promoTourData.value?.length) return null;

                const filtered = promoTourData.value.filter(item => parseFloat(item.original_price) >
                    0);
                if (!filtered.length) return null;

                const lowest = filtered.reduce((min, item) =>
                    parseFloat(item.discounted_price) < parseFloat(min.discounted_price) ? item :
                    min
                );

                return parseFloat(lowest.original_price);
            });

            const weekdayDiscountPercent = {{ $weekday_discount_percent }}
            const weekendDiscountPercent = {{ $weekend_discount_percent }}
            const lowestPromoWeekdayDiscountPrice = computed(() => {
                if (!lowestPromoOriginalPrice.value) return 'null';
                return Math.floor(lowestPromoOriginalPrice.value * (1 - (weekdayDiscountPercent /
                    100)));
            });

            const lowestPromoWeekendDiscountPrice = computed(() => {
                if (!lowestPromoOriginalPrice.value) return null;
                return Math.floor(lowestPromoOriginalPrice.value * (1 - (weekendDiscountPercent /
                    100)));
            });

            window.lowestPromoWeekdayDiscountPrice = lowestPromoWeekdayDiscountPrice.value
            window.lowestPromoWeekendDiscountPrice = lowestPromoWeekendDiscountPrice.value

            const applyFirstOrderCoupon = () => {
                isFirstOrderCouponApplied.value = true
                const coupon = firstOrderCoupon.value

                if (priceType === "normal") {
                    const updated = {
                        ...normalTourData.value
                    }

                    Object.keys(updated).forEach(key => {
                        const item = updated[key]

                        if (!item.original_price) {
                            item.original_price = parseFloat(item.price)
                        }

                        let discounted = parseFloat(item.original_price)

                        if (coupon.discount_type === 'percentage') {
                            discounted -= discounted * (parseFloat(coupon.amount) / 100)
                        } else {
                            discounted -= parseFloat(coupon.amount)
                        }

                        item.promo_discounted_price = discounted > 0 ? discounted.toFixed(
                            2) : "0.00"
                    })

                    normalTourData.value = updated
                }

                if (priceType === "promo") {
                    promoTourData.value = promoTourData.value.map(item => {
                        if (item.source === 'addon' && item.type === 'timeslot') {
                            item.slots = item.slots.map(slot => {
                                if (!slot.original_discounted_price) {
                                    slot.original_discounted_price = slot.discounted_price
                                }
                                let price = parseFloat(slot.discounted_price)
                                if (coupon.discount_type === 'percentage') {
                                    price -= price * (parseFloat(coupon.amount) / 100)
                                } else {
                                    price -= parseFloat(coupon.amount)
                                }
                                return {
                                    ...slot,
                                    discounted_price: price.toFixed(2)
                                }
                            })
                        } else if (item.discounted_price) {
                            if (!item.original_discounted_price) {
                                item.original_discounted_price = item.discounted_price
                            }
                            let price = parseFloat(item.discounted_price)
                            if (coupon.discount_type === 'percentage') {
                                price -= price * (parseFloat(coupon.amount) / 100)
                            } else {
                                price -= parseFloat(coupon.amount)
                            }
                            item.discounted_price = price.toFixed(2)
                        }
                        return item
                    })
                }

                updateTotalPrice()
            }

            const getTourPromoPricesByDay = async () => {
                if (priceType !== "promo") return
                try {
                    const day = new Date(startDateInput.value?.value).getDay()
                    const isWeekend = day === 5 || day === 6 || day === 0
                    fetchingPromoPrices.value = true;
                    const route = `{{ route('tours.promo-prices-by-day') }}`
                    const payload = {
                        tour_id: tourId.value,
                        isWeekend: isWeekend
                    }
                    const response = await axios.post(route, payload)
                    promoTourData.value = response.data
                    isFirstOrderCouponApplied.value = false
                    updateTotalPrice()
                } catch (error) {
                    showToast('error', error.response.data.message)
                } finally {
                    fetchingPromoPrices.value = false;
                }
            };

            const handleSelectedSlotChange = (addOn) => {
                if (!Array.isArray(addOn.selected_slots)) return

                addOn.selected_slots.forEach((time) => {
                    const slot = addOn.slots.find(s => s.time === time)
                    if (slot) {
                        updateTotalPrice()
                    }
                })
            }

            const formatTimeLabel = (time) => {
                const [hours, minutes] = time.split(':').map(Number)
                if (hours && minutes) return `${hours} hr ${minutes} mins`
                if (hours) return `${hours} hour`
                return `${minutes} mins`
            }

            const handleDateChange = (e) => {
                updateSubmitButtonState()
                getTourPromoPricesByDay()
            }

            const visiblePromos = computed(() =>
                showAllPromos.value ? promoTourData.value : promoTourData.value.slice(0, 4)
            )

            const toggleShowAll = () => {
                showAllPromos.value = !showAllPromos.value
            }

            const updateTotalPrice = () => {
                @if (!$tour->availability_status['available'])
                    showToast('error', '{{ $tour->availability_status['user_message'] }}');
                @endif
                @if (!Auth::check())
                    showToast('error', 'Please Login to continue.');
                @endif
                @if (isset($isTourInCart) && $isTourInCart)
                    showToast('error', 'Tour already added to cart.');
                @endif
                totalPrice.value = initialTotalPrice;

                if (priceType === "simple") {
                    totalPrice.value = isFirstOrderCouponApplied.value ?
                        simpleTourData.value.promo_discounted_price :
                        simpleTourData.value.sale_price
                }

                if (priceType === "promo") {
                    const totalPromoQty = promoTourData.value
                        .filter(item => item.source === 'promo')
                        .reduce((sum, item) => sum + item.quantity, 0)

                    if (totalPromoQty === 0) {
                        promoTourData.value.forEach((item) => {
                            if (item.source === 'addon') {
                                item.quantity = 0
                                if (item.type === 'timeslot') item.selected_slots = []
                            }
                        })
                    }

                    promoTourData.value.forEach((item) => {
                        const price = parseFloat(item.discounted_price) || 0

                        if (item.source === 'promo') {
                            totalPrice.value += price * item.quantity
                        }

                        if (item.source === 'addon') {
                            if (item.type === 'simple') {
                                totalPrice.value += price * item.quantity
                            }

                            if (item.type === 'timeslot' && Array.isArray(item
                                    .selected_slots)) {
                                if (item.quantity === 0) {
                                    item.selected_slots = []
                                }
                                item.selected_slots.slice(0, item.quantity).forEach((slotTime) => {
                                    const slot = item.slots.find((s) => s.time === slotTime)
                                    if (slot) {
                                        totalPrice.value += parseFloat(slot
                                            .discounted_price) || 0
                                    }
                                })
                            }
                        }
                    })
                }

                if (priceType === "normal") {
                    totalPrice.value += Object.values(normalTourData.value).reduce(
                        (sum, {
                            original_price,
                            promo_discounted_price,
                            quantity
                        }) => {
                            const effectivePrice = isFirstOrderCouponApplied.value ?
                                parseFloat(promo_discounted_price ?? original_price) :
                                parseFloat(original_price)
                            return sum + effectivePrice * quantity
                        },
                        0
                    )
                }

                if (priceType === "water") {
                    Object.entries(waterTourData.value).forEach(([slot, data]) => {
                        if (data.quantity > 0) {
                            const price = isFirstOrderCouponApplied.value ?
                                data.promo_discounted_price :
                                data.original_price
                            totalPrice.value += price * data.quantity
                        }
                    })
                }

                if (priceType === "private") {
                    const qty = privateTourData.value.quantity
                    const maxPerCar = privateTourData.value.max_person
                    const price = isFirstOrderCouponApplied.value ?
                        privateTourData.value.promo_discounted_price :
                        privateTourData.value.original_price
                    const carsNeeded = Math.ceil(qty / maxPerCar)
                    totalPrice.value = carsNeeded * price
                }
                updateSubmitButtonState();

            };

            const updateSubmitButtonState = () => {
                if (!startDateInput.value?.value) {
                    isSubmitEnabled.value = false
                    return
                }

                switch (priceType) {
                    case "simple":
                        isSubmitEnabled.value = true
                        break
                    case "water":
                        isSubmitEnabled.value = timeSlot.value
                        break
                    case "normal":
                        isSubmitEnabled.value = Object.values(normalTourData.value).some(d => d
                            .quantity >
                            0)
                        break
                    case "promo":
                        isSubmitEnabled.value = promoTourData.value?.some(p => p.quantity > 0)
                        break
                    case "private":
                        isSubmitEnabled.value = privateTourData.value?.quantity > 0
                        break
                }
            }

            const updatePrivateQuantity = (action) => {
                if (action === 'minus' && privateTourData.value.quantity > privateTourData.value
                    .min_person) {
                    privateTourData.value.quantity--
                }
                if (action === 'plus') {
                    privateTourData.value.quantity++
                }
                updateTotalPrice();
            };

            const updateNormalQuantity = (action, personType) => {
                const personData = normalTourData.value[personType];
                if (!personData) return;

                personData.quantity += action === "plus" ? 1 : (action === "minus" && personData.quantity >
                    0 ? -1 : 0);
                personData.quantity = Math.max(0, Math.min(personData.quantity, personData.max));
                updateTotalPrice();
            };

            const updateSimpleQuantity = (action) => {
                if (!simpleTourData.value) return;

                simpleTourData.value.quantity += action === "plus" ? 1 : (action === "minus" &&
                    simpleTourData.value.quantity >
                    0 ? -1 : 0);
                simpleTourData.value.quantity = Math.max(0, Math.min(simpleTourData.value.quantity,
                    simpleTourData.value.max));
                updateTotalPrice();
            };

            const updatePromoQuantity = (action, personType) => {
                const promoData = promoTourData.value.find(promo => formatNameForInput(promo
                    .slug) === personType);
                if (!promoData) return;

                promoData.quantity += (action === "plus" ? 1 : (action === "minus" && promoData
                    .quantity > 0 ? -
                    1 : 0));
                updateTotalPrice();
            };

            const updateWaterQuantity = (action, slot) => {
                if (!waterTourData.value[slot]) return;

                if (action === "plus") {
                    waterTourData.value[slot].quantity++;
                }

                if (action === "minus" && waterTourData.value[slot].quantity > 0) {
                    waterTourData.value[slot].quantity--;
                }

                updateTotalPrice();
            };

            const updateQuantity = (action, personType = null) => {
                if (priceType === "private") {
                    updatePrivateQuantity(action);
                } else if (priceType === "normal" && personType) {
                    updateNormalQuantity(action, personType);
                } else if (priceType === "promo" && personType) {
                    updatePromoQuantity(action, personType);
                } else if (priceType === "water") {
                    updateWaterQuantity(action, personType);
                }
            };

            const formatPrice = (price) => {
                const currencySymbolHtml = @json(currencySymbol()->toHtml());
                const formattedPrice = Number(price).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return `${currencySymbolHtml}${formattedPrice}`;
            };

            const formatNameForInput = (name) => {
                return name.toLowerCase().replace(/ /g, '_');
            };

            watch(timeSlot, () => {
                Object.values(waterTourData.value).forEach(item => {
                    item.quantity = 0
                })
                updateTotalPrice()
            })

            return {
                totalPrice,
                updateQuantity,
                formatPrice,
                normalTourData,
                promoTourData,
                simpleTourData,
                privateTourData,
                timeSlot,
                waterTourData,
                waterPricesTimeSlots,
                isSubmitEnabled,
                formatNameForInput,
                showAllPromos,
                visiblePromos,
                toggleShowAll,
                startDateInput,
                handleDateChange,
                fetchingPromoPrices,
                hasAnyPromoQuantity,
                promoAddOnsTourData,
                formatTimeLabel,
                handleSelectedSlotChange,
                firstOrderCoupon,
                isFirstOrderCouponApplied,
                applyFirstOrderCoupon,
                hasUsedFirstOrderCoupon,
                isTourInCart,
            };
        },
    });
    PricingBox.mount('#tour-pricing');
</script>
