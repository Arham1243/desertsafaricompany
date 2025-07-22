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
        $tour->promoPrices->map(function ($promoPrice) use ($discountPercent, $hoursLeft) {
            $original = (float) $promoPrice->original_price;
            $discounted = $original - $original * ($discountPercent / 100);

            return [
                'source' => 'promo',
                'title' => $promoPrice->promo_title,
                'slug' => $promoPrice->promo_slug,
                'promo_is_free' => $promoPrice->promo_is_free,
                'original_price' => number_format($original, 2),
                'discount_percent' => $discountPercent,
                'discounted_price' => number_format($discounted, 2),
                'quantity' => 0,
                'hours_left' => $hoursLeft,
            ];
        }),
    );

    $promoData = $promoData->concat(
        $tour->promoAddons->flatMap(function ($pricing) use ($discountPercent, $hoursLeft) {
            $addons = json_decode($pricing->promo_addons ?? '[]', true);

            return collect($addons)
                ->map(function ($addon) use ($discountPercent, $hoursLeft) {
                    if ($addon['type'] === 'simple') {
                        $original = floatval($addon['price']);
                        $discounted = $original - ($original * $discountPercent) / 100;

                        return [
                            'source' => 'addon',
                            'type' => 'simple',
                            'title' => $addon['title'],
                            'slug' => $addon['promo_slug'],
                            'original_price' => number_format($original, 2),
                            'discount_percent' => $discountPercent,
                            'discounted_price' => number_format($discounted, 2),
                            'quantity' => 0,
                            'hours_left' => $hoursLeft,
                        ];
                    }

                    if ($addon['type'] === 'timeslot') {
                        $slots = collect($addon['slots'] ?? []);

                        return [
                            'source' => 'addon',
                            'type' => 'timeslot',
                            'title' => $addon['title'],
                            'slug' => $addon['promo_slug'],
                            'discount_percent' => $discountPercent,
                            'hours_left' => $hoursLeft,
                            'quantity' => 0,
                            'selected_slots' => [],
                            'slots' => $slots
                                ->map(function ($slot) use ($discountPercent) {
                                    $price = floatval($slot['price']);
                                    $discounted = $price - ($price * $discountPercent) / 100;
                                    return [
                                        'time' => $slot['time'],
                                        'original_price' => number_format($price, 2),
                                        'discounted_price' => number_format($discounted, 2),
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

    $normalTourData = $tour->normalPrices->mapWithKeys(function ($price) {
        return [
            formatNameForInput($price->person_type) => [
                'price' => $price->price,
                'min' => $price->min_person,
                'max' => $price->max_person,
                'quantity' => 0,
            ],
        ];
    });
    $waterPricesTimeSlots = $tour->waterPrices->isNotEmpty() ? $tour->waterPrices->pluck('time') : null;

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

            const carQuantity = ref(0);
            const carPrice = parseFloat("{{ $tour->privatePrices->car_price ?? 0 }}");
            const carMax = parseInt("{{ $tour->privatePrices->max_person ?? 1 }}");

            const waterPrices = ref(@json($tour->waterPrices));
            const waterPricesTimeSlots = ref(@json($waterPricesTimeSlots));
            const timeSlot = ref("");
            const timeSlotQuantity = ref(0);
            const waterPriceMap = computed(() => {
                return waterPrices.value.reduce((map, price, index) => {
                    map[waterPricesTimeSlots.value[index]] = parseFloat(price.water_price);
                    return map;
                }, {});
            });

            const firstOrderCoupon = ref(@json($firstOrderCoupon));
            const tourId = ref(@json($tour->id))
            const cartData = ref(@json($cart));
            const initialTotalPrice = parseFloat("{{ $tour->initial_price ?? 0 }}");
            const normalTourData = ref(@json($normalTourData));
            const promoTourData = ref(@json($promoTourData));
            const isSubmitEnabled = ref(false);
            const showAllPromos = ref(false)
            const startDate = ref(null)
            const fetchingPromoPrices = ref(null)
            const isFirstOrderCouponrApplied = ref(false)

            const hasUsedFirstOrderCoupon = computed(() => {
                const coupons = cartData.value?.applied_coupons
                return Array.isArray(coupons) && coupons.some(c => c?.is_first_order_coupon == 1)
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
                isFirstOrderCouponrApplied.value = true
                const coupon = firstOrderCoupon.value

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

                updateTotalPrice()
            }

            const getTourPromoPricesByDay = async (tourId, isWeekend) => {
                try {
                    fetchingPromoPrices.value = true;
                    const route = `{{ route('tours.promo-prices-by-day') }}`
                    const payload = {
                        tour_id: tourId,
                        isWeekend: isWeekend
                    }
                    const response = await axios.post(route, payload)
                    promoTourData.value = response.data
                    isFirstOrderCouponrApplied.value = false
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
                startDate.value = e.target.value
                const day = new Date(startDate.value).getDay()
                const isWeekend = day === 5 || day === 6 || day === 0
                getTourPromoPricesByDay(tourId.value, isWeekend)
            }

            const visiblePromos = computed(() =>
                showAllPromos.value ? promoTourData.value : promoTourData.value.slice(0, 4)
            )

            const toggleShowAll = () => {
                showAllPromos.value = !showAllPromos.value
            }

            const updateTotalPrice = () => {
                @if (!Auth::check())
                    showToast('error', 'Please Login to continue.');
                @endif
                @if (isset($isTourInCart) && $isTourInCart)
                    showToast('error', 'Tour already added to cart.');
                @endif
                totalPrice.value = initialTotalPrice;

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
                            price,
                            quantity
                        }) => sum + price * quantity,
                        0
                    );
                }

                if (priceType === "water" && timeSlot.value && timeSlotQuantity.value > 0) {
                    const slotPrice = waterPriceMap.value[timeSlot.value] || 0;
                    totalPrice.value += slotPrice * timeSlotQuantity.value;
                }
                updateSubmitButtonState();

            };


            const updateSubmitButtonState = () => {
                isSubmitEnabled.value = (
                    timeSlotQuantity.value > 0 ||
                    Object.values(normalTourData.value).some(data => data.quantity > 0) ||
                    promoTourData.value.some(promo => promo.quantity > 0)
                );
            };

            const updatePrivateQuantity = (action) => {
                const previousCars = Math.ceil(carQuantity.value / carMax);
                if (action === "plus") carQuantity.value++;
                if (action === "minus" && carQuantity.value > 0) carQuantity.value--;

                const currentCars = Math.ceil(carQuantity.value / carMax);
                totalPrice.value += (currentCars > previousCars ? carPrice : (currentCars <
                    previousCars ? -
                    carPrice : 0));
            };

            const updateNormalQuantity = (action, personType) => {
                const personData = normalTourData.value[personType];
                if (!personData) return;

                personData.quantity += (action === "plus" ? 1 : (action === "minus" && personData
                    .quantity >
                    personData.min ? -1 : 0));
                personData.quantity = Math.max(personData.min, Math.min(personData.quantity, personData
                    .max));
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

            const updateWaterQuantity = (action) => {
                if (action === "plus") timeSlotQuantity.value++;
                if (action === "minus" && timeSlotQuantity.value > 0) timeSlotQuantity.value--;
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
                    updateWaterQuantity(action);
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

            watch(timeSlot, updateTotalPrice);

            return {
                carQuantity,
                totalPrice,
                updateQuantity,
                formatPrice,
                normalTourData,
                promoTourData,
                timeSlot,
                timeSlotQuantity,
                waterPrices,
                waterPricesTimeSlots,
                isSubmitEnabled,
                formatNameForInput,
                showAllPromos,
                visiblePromos,
                toggleShowAll,
                startDate,
                handleDateChange,
                fetchingPromoPrices,
                hasAnyPromoQuantity,
                promoAddOnsTourData,
                formatTimeLabel,
                handleSelectedSlotChange,
                firstOrderCoupon,
                isFirstOrderCouponrApplied,
                applyFirstOrderCoupon,
                hasUsedFirstOrderCoupon,
                isTourInCart,
            };
        },
    });
    PricingBox.mount('#tour-pricing');
</script>
