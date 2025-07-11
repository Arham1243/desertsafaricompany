@php
    use Carbon\Carbon;

    $cartTours = $tours->whereIn('id', array_keys($cart['tours']));
    $cartToursIds = $tours->whereIn('id', array_keys($cart['tours']))->pluck('id');
    $toursPrivatePrices = $tours
        ->whereIn('id', $cartToursIds)
        ->filter(function ($tour) {
            return $tour->price_type === 'private';
        })
        ->mapWithKeys(function ($tour) use ($cart) {
            $quantity = $cart['tours'][$tour->id]['data']['price']['persons']['quantity'] ?? 0;

            $price = $tour->privatePrices;

            if (!$price) {
                return [$tour->id => null];
            }

            return [
                $tour->id => [
                    'persons' => [
                        'car_price' => $price->car_price,
                        'min_person' => $price->min_person,
                        'max_person' => $price->max_person,
                        'quantity' => (int) $quantity,
                    ],
                ],
            ];
        });

    $toursNormalPrices = $tours->whereIn('id', $cartToursIds)->mapWithKeys(function ($tour) use ($cart) {
        return [
            $tour->id => $tour->normalPrices->mapWithKeys(function ($price) use ($tour, $cart) {
                $formattedKey = formatNameForInput($price->person_type);
                $quantity = $cart['tours'][$tour->id]['data']['price'][$formattedKey]['quantity'] ?? 0;

                return [
                    $formattedKey => [
                        'person_type' => $price->person_type,
                        'person_description' => $price->person_description,
                        'price' => $price->price,
                        'min' => $price->min_person,
                        'max' => $price->max_person,
                        'quantity' => (int) $quantity,
                    ],
                ];
            }),
        ];
    });

    $toursWaterPrices = $tours->whereIn('id', $cartToursIds)->mapWithKeys(function ($tour) use ($cart) {
        $waterPrices = $tour->waterPrices->map(function ($price) use ($tour, $cart) {
            $quantity = $cart['tours'][$tour->id]['data']['time_slot_quantity'] ?? 0;

            return [
                'time' => $price->time,
                'person_description' => $price->person_description,
                'water_price' => $price->water_price,
                'quantity' => (int) $quantity,
            ];
        });

        return [$tour->id => $waterPrices->toArray()];
    });

    $waterTourTimeSlots = $tours
        ->whereIn('id', $cartToursIds)
        ->filter(fn($tour) => $tour->waterPrices->isNotEmpty())
        ->mapWithKeys(function ($tour) {
            return [
                $tour->id => $tour->waterPrices
                    ->map(function ($price) {
                        return [
                            'time' => $price->time,
                            'water_price' => $price->water_price,
                        ];
                    })
                    ->toArray(),
            ];
        });

    $promoToursData = $tours
        ->whereIn('id', $cartToursIds)
        ->map(function ($tour) use ($cart) {
            return $tour->promoPrices->map(function ($promoPrice) use ($tour, $cart) {
                $now = Carbon::now();
                $hourOfDay = $now->hour;
                $today = strtolower(Carbon::now()->englishDayOfWeek);
                $hourOfDay = $now->hour;
                $originalPrice = (float) $promoPrice->original_price;
                $promoDiscountConfig =
                    isset($tour->promo_discount_config) && $tour->promo_discount_config
                        ? json_decode($tour->promo_discount_config, true)
                        : null;

                $isWeekend = in_array($today, ['friday', 'saturday', 'sunday']);
                $discountPercent = 0;
                if ($promoDiscountConfig) {
                    $discountPercent = $isWeekend
                        ? $promoDiscountConfig->weekend_discount_percent ?? 0
                        : $promoDiscountConfig->weekday_discount_percent ?? 0;
                }

                $discountedPrice = $originalPrice - $originalPrice * ($discountPercent / 100);

                $discountPercent = $isWeekend
                    ? $promoDiscountConfig['weekend_discount_percent'] ?? 0
                    : $promoDiscountConfig['weekday_discount_percent'] ?? 0;

                $timerHours = (int) ($isWeekend
                    ? $promoDiscountConfig['weekend_timer_hours'] ?? 0
                    : $promoDiscountConfig['weekday_timer_hours'] ?? 0);

                $hoursLeft = $timerHours > 0 ? $timerHours - ($hourOfDay % $timerHours) : 0;

                $promoTitle = formatNameForInput($promoPrice->promo_title);

                $quantity = $cart['tours'][$tour->id]['data']['price'][$promoTitle]['quantity'] ?? 0;

                return [
                    'tour_id' => $tour->id,
                    'promo_title' => $promoPrice->promo_title,
                    'original_price' => number_format($originalPrice, 2),
                    'discount_percent' => $discountPercent,
                    'discounted_price' => number_format($discountedPrice, 2),
                    'quantity' => (int) $quantity,
                    'hours_left' => $hoursLeft,
                ];
            });
        })
        ->flatten(1)
        ->groupBy('tour_id');
@endphp
<script>
    const Cart = createApp({
        setup() {
            const cartUpdateForm = ref(null);
            const submitButton = ref(null);
            const cart = ref(@json($cart));
            const cartToursData = ref(@json($cartTours));
            const promoToursData = ref(@json($promoToursData));
            const toursNormalPrices = ref(@json($toursNormalPrices));
            const toursPrivatePrices = ref(@json($toursPrivatePrices));
            const toursWaterPrices = ref(@json($toursWaterPrices));
            const waterTourTimeSlots = ref(@json($waterTourTimeSlots));
            const totalPrice = ref(cart.value.total_price);

            watch(totalPrice, async (newValue) => {
                const roundedValue = Math.round(newValue * 100) / 100;
                if (roundedValue === 0) {
                    submitButton.value.disabled = true;
                }
            });



            const cartTours = computed(() => {
                const toursArray = Object.values(cartToursData.value);
                return toursArray.filter(tour => cart.value.tours[tour.id]);
            });

            const getPromoTourPricing = (tourId) => {
                return promoToursData.value[tourId] || null;
            }
            const getNormalTourPricing = (tourId) => {
                return toursNormalPrices.value[tourId] || null;
            }
            const getPrivateTourPricing = (tourId) => {
                return toursPrivatePrices.value[tourId] || null
            };
            const getWaterTourPricing = (tourId) => {
                return toursWaterPrices.value[tourId] || null
            };
            const getWaterTourTimeSlots = (tourId) => {
                return waterTourTimeSlots.value[tourId] || null
            };
            const handleTimeSlotChange = (event, tourId) => {
                const selectedTime = event.target.value;
                const selectedPrice = event.target.options[event.target.selectedIndex]
                    .getAttribute(
                        'time-price')
                totalPrice.value -= cart.value['tours'][tourId]['data']['time_slot_price'] *
                    cart.value[
                        'tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ];

                cart.value['subtotal'] -= cart.value['tours'][tourId]['data'][
                        'time_slot_price'
                    ] * cart
                    .value['tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ];
                cart.value['total_price'] -= cart.value['tours'][tourId]['data'][
                        'time_slot_price'
                    ] * cart
                    .value['tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ];

                cart.value['tours'][tourId]['data']['subtotal'] -= cart.value['tours'][
                        tourId
                    ]['data'][
                        'time_slot_price'
                    ] * cart
                    .value['tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ];
                cart.value['tours'][tourId]['data']['total_price'] -= cart.value['tours'][
                        tourId
                    ]['data'][
                        'time_slot_price'
                    ] * cart
                    .value['tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ];

                cart.value['tours'][tourId]['data']['time_slot_price'] = selectedPrice

                if (cart.value['tours'][tourId]['data']['time_slot'] && cart.value['tours']
                    [tourId]['data'][
                        'time_slot_quantity'
                    ] > 0) {

                    totalPrice.value += selectedPrice * cart.value['tours']
                        [tourId]['data'][
                            'time_slot_quantity'
                        ];
                    cart.value['subtotal'] += selectedPrice * cart.value['tours']
                        [tourId]['data'][
                            'time_slot_quantity'
                        ];
                    cart.value['total_price'] += selectedPrice * cart.value['tours']
                        [tourId]['data'][
                            'time_slot_quantity'
                        ];
                    cart.value['tours'][tourId]['data']['subtotal'] += cart.value['tours'][
                            tourId
                        ]['data'][
                            'time_slot_price'
                        ] * cart
                        .value['tours']
                        [tourId]['data'][
                            'time_slot_quantity'
                        ];
                    cart.value['tours'][tourId]['data']['total_price'] += cart.value[
                            'tours'][tourId][
                            'data'
                        ][
                            'time_slot_price'
                        ] * cart
                        .value['tours']
                        [tourId]['data'][
                            'time_slot_quantity'
                        ];
                }
            }

            function getWaterPrice(tourId, time) {
                const tourSlots = waterTourTimeSlots.value[tourId];
                if (!tourSlots) return null;

                const slot = tourSlots.find(slot => slot.time === time);
                return slot ? slot.water_price : null;
            }

            const removeTour = (id) => {
                const tour = cart.value.tours[id];
                if (tour) {
                    if (confirm('Are you sure you want to remove this tour?')) {

                        minusCartPrices(tour);
                        delete cart.value.tours[id];
                        cartToursData.value = Object.fromEntries(
                            Object.entries(cartToursData.value).filter(([key, tour]) =>
                                tour
                                .id !== id)
                        );

                        setTimeout(() => {
                            cartUpdateForm.value.submit();
                        }, 1000);
                    }
                } else {
                    showToast('error', 'Tour not found!');
                }
            };

            const minusCartPrices = (tour) => {
                cart.value.subtotal = Math.max(0, cart.value.subtotal - tour.data.subtotal)
                cart.value.service_fee = Math.max(0, cart.value.service_fee - tour.data.service_fee)
                cart.value.total_price = Math.max(0, cart.value.total_price - tour.data.total_price)

                totalPrice.value = cart.value.total_price
            }

            const getImageUrl = (image) => {
                return image ? '{{ asset(':image') }}'.replace(":image", image) :
                    '{{ asset('admin/assets/images/placeholder.png') }}';
            }
            const formatPrice = (price) => {
                const formattedPrice = Number(price).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return `{{ env('APP_CURRENCY') }}${formattedPrice}`;
            };

            const updateNormalQuantity = (action, personType, tour, nomalIndex) => {
                const toursNormalPrice = getNormalTourPricing(tour.id);
                const personData = toursNormalPrice[formatNameForInput(personType)];
                if (!personData) return;

                updateTotalPrice(tour, action, nomalIndex);
            };

            const updatePromoQuantity = (action, personType, tour, promoIndex) => {
                const promoTourData = getPromoTourPricing(tour.id);
                const promoData = promoTourData.find(promo => formatNameForInput(promo
                        .promo_title) ===
                    formatNameForInput(personType));
                if (!promoData) return;

                updateTotalPrice(tour, action, promoIndex);
            };

            const updatePrivateQuantity = (action, tour) => {
                let carPrice = parseInt(getPrivateTourPricing(tour.id)['persons'][
                    'car_price'
                ]);
                let carMax = getPrivateTourPricing(tour.id)['persons']['max_person'];

                const previousCars = Math.ceil(getPrivateTourPricing(tour.id)['persons'][
                        'quantity'
                    ] /
                    carMax);
                if (action === "plus") {
                    getPrivateTourPricing(tour.id)['persons']['quantity']++;
                    cart.value['tours'][tour.id]['data']['price']['persons']['quantity']++
                }

                if (action === "minus" && getPrivateTourPricing(tour.id)['persons'][
                        'quantity'
                    ] >
                    0) {
                    getPrivateTourPricing(tour.id)['persons']['quantity']--;
                    cart.value['tours'][tour.id]['data']['price']['persons']['quantity']--
                };

                const currentCars = Math.ceil(getPrivateTourPricing(tour.id)['persons'][
                        'quantity'
                    ] /
                    carMax);
                totalPrice.value += (currentCars > previousCars ? carPrice : (currentCars <
                    previousCars ? -
                    carPrice : 0));
            };

            const updateWaterQuantity = (action, tour) => {
                const slotPrice = parseFloat(cart.value['tours'][tour.id]['data'][
                    'time_slot_price'
                ]);

                const initial_price = (cart.value['tours'][tour.id]['data'][
                    'extra_prices'
                ] ? Object.values(
                    cart.value['tours'][tour.id]['data']['extra_prices']).reduce((
                        acc, curr) =>
                    acc + parseFloat(curr), 0) : 0) + (cart.value['tours'][tour.id][
                    'data'
                ][
                    'service_fee'
                ] ? parseFloat(cart.value['tours'][tour.id]['data'][
                    'service_fee'
                ]) : 0);
                if (action === 'plus') {
                    cart.value['tours'][tour.id]['data']['time_slot_quantity']++
                    totalPrice.value += slotPrice
                    cart.value['subtotal'] += slotPrice;
                    cart.value['total_price'] += slotPrice;
                    cart.value['tours'][tour.id]['data']['subtotal'] =
                        initial_price - (cart.value['tours'][tour.id]['data'][
                            'service_fee'
                        ] ? cart.value[
                            'tours'][tour.id]['data']['service_fee'] : 0) + slotPrice *
                        cart.value['tours'][
                            tour.id
                        ]['data']['time_slot_quantity'];
                    cart.value['tours'][tour.id]['data']['total_price'] =
                        initial_price +
                        slotPrice * cart.value['tours'][tour.id]['data'][
                            'time_slot_quantity'
                        ];
                } else if (action === 'minus' && cart.value['tours'][tour.id]['data'][
                        'time_slot_quantity'
                    ] > 0) {
                    cart.value[

                        'tours'][tour.id]['data']['time_slot_quantity']--;

                    totalPrice.value -= slotPrice
                    cart.value['subtotal'] -= slotPrice;
                    cart.value['total_price'] -= slotPrice;
                    cart.value['tours'][tour.id]['data']['subtotal'] =
                        initial_price - (cart.value['tours'][tour.id]['data'][
                            'service_fee'
                        ] ? cart.value[
                            'tours'][tour.id]['data']['service_fee'] : 0) + slotPrice *
                        cart.value['tours'][
                            tour.id
                        ]['data']['time_slot_quantity'];
                    cart.value['tours'][tour.id]['data']['total_price'] =
                        initial_price +
                        slotPrice * cart.value['tours'][tour.id]['data'][
                            'time_slot_quantity'
                        ];

                }
            };


            const updateQuantity = (action, personType = null, tour, index = null) => {
                if (tour.price_type === "private") {
                    updatePrivateQuantity(action, tour);
                } else if (tour.price_type === "normal" && personType) {
                    updateNormalQuantity(action, personType, tour, index);
                } else if (tour.price_type === "promo" && personType) {
                    updatePromoQuantity(action, personType, tour, index);
                } else if (tour.price_type === "water") {
                    updateWaterQuantity(action, tour);
                }
            };

            const updateTotalPrice = (tour, action, index = null) => {
                switch (tour.price_type) {
                    case 'normal':
                        const toursNormalPrice = getNormalTourPricing(tour.id);
                        const normalPrice = parseFloat(toursNormalPrice[index].price);

                        if (action === 'plus') {
                            if (toursNormalPrice[index].quantity < toursNormalPrice[index]
                                .max) {
                                totalPrice.value += normalPrice;
                                cart.value['tours'][tour.id]['data']['subtotal'] =
                                    (parseFloat(cart.value['tours'][tour.id]['data'][
                                            'subtotal'
                                        ]) +
                                        normalPrice).toString();

                                cart.value['tours'][tour.id]['data']['total_price'] =
                                    (parseFloat(cart.value['tours'][tour.id]['data'][
                                            'total_price'
                                        ]) +
                                        normalPrice).toString();

                                cart.value['subtotal'] += normalPrice;
                                cart.value['total_price'] += normalPrice;
                                toursNormalPrices.value[tour.id][formatNameForInput(
                                    toursNormalPrice[index].person_type)].quantity++;
                                cart.value['tours'][tour.id]['data']['price'][
                                    formatNameForInput(
                                        toursNormalPrice[index].person_type)
                                ].quantity++
                            }
                        }
                        if (action === 'minus') {
                            if (toursNormalPrice[index].quantity > toursNormalPrice[index]
                                .min) {
                                totalPrice.value -= normalPrice;
                                cart.value['tours'][tour.id]['data']['subtotal'] -=
                                    normalPrice;
                                cart.value['tours'][tour.id]['data']['total_price'] -=
                                    normalPrice;
                                cart.value['subtotal'] -= normalPrice;
                                cart.value['total_price'] -= normalPrice;
                                toursNormalPrices.value[tour.id][formatNameForInput(
                                    toursNormalPrice[index].person_type)].quantity--;
                                cart.value['tours'][tour.id]['data']['price'][
                                    formatNameForInput(
                                        toursNormalPrice[index].person_type)
                                ].quantity--
                            }
                        }
                        break;
                    case 'promo':
                        const promoTourData = getPromoTourPricing(tour.id);
                        const promo = promoTourData[index]
                        const applicablePrice = parseFloat(promo.discounted_price);
                        if (action === 'plus') {
                            promo.quantity++;
                            totalPrice.value += applicablePrice;
                            cart.value['tours'][tour.id]['data']['subtotal'] +=
                                applicablePrice;
                            cart.value['tours'][tour.id]['data']['total_price'] +=
                                applicablePrice;
                            cart.value['subtotal'] += applicablePrice;
                            cart.value['total_price'] += applicablePrice;
                        } else if (action === 'minus' && promo.quantity > 0) {
                            promo.quantity--;
                            totalPrice.value -= applicablePrice;
                            cart.value['tours'][tour.id]['data']['subtotal'] -=
                                applicablePrice;
                            cart.value['tours'][tour.id]['data']['total_price'] -=
                                applicablePrice;
                            cart.value['subtotal'] -= applicablePrice;
                            cart.value['total_price'] -= applicablePrice;
                        }
                        break;
                }
            };


            const formatNameForInput = (name) => {
                return name.toLowerCase().replace(/ /g, '_');
            };

            return {
                cart,
                cartTours,
                getImageUrl,
                removeTour,
                formatPrice,
                totalPrice,
                updateQuantity,
                formatNameForInput,
                getPromoTourPricing,
                getNormalTourPricing,
                toursNormalPrices,
                getPrivateTourPricing,
                getWaterTourPricing,
                getWaterTourTimeSlots,
                handleTimeSlotChange,
                cartUpdateForm,
                submitButton,
            };
        },
    });
    Cart.mount('#cart-items');
</script>
