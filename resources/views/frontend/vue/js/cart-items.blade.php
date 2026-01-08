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

    // Use tourData from cart instead of generating from database
    $promoToursData = collect($cart['tours'] ?? [])->mapWithKeys(function ($tourCart, $tourId) {
        $tourData = $tourCart['tourData'] ?? [];
        return [$tourId => collect($tourData)];
    });
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
            const privateTourData = ref(@json($privateTourData));
            const waterTourTimeSlots = ref(@json($waterTourTimeSlots));
            const toursBookingAdditional = ref(@json($toursBookingAdditional));
            const totalPrice = ref(cart.value.total_price);

            // Initialize booking_additional_selections for each tour if not exists
            Object.keys(cart.value.tours || {}).forEach(tourId => {
                const bookingAdditional = toursBookingAdditional.value[tourId];
                if (!cart.value.tours[tourId].booking_additional_selections) {
                    cart.value.tours[tourId].booking_additional_selections = {
                        type: bookingAdditional?.additional_type || null,
                        selection: null
                    };
                } else if (!cart.value.tours[tourId].booking_additional_selections.type) {
                    // Migrate old structure to new structure
                    cart.value.tours[tourId].booking_additional_selections = {
                        type: bookingAdditional?.additional_type || null,
                        selection: null
                    };
                }
            });

            // Check if first order coupon is applied
            const hasUsedFirstOrderCoupon = computed(() => {
                const coupons = cart.value?.applied_coupons || [];
                return Array.isArray(coupons) && coupons.some(c => c?.is_first_order_coupon == 1);
            });

            // Get correct price based on coupon status
            const getCorrectPrice = (pkg) => {
                if (pkg.promo_is_free === 1) return 0;

                if (pkg.is_first_order_coupon_applied && pkg.promo_discounted_price) {
                    return parseFloat(pkg.promo_discounted_price);
                }

                return parseFloat(pkg.original_discounted_price || pkg.discounted_price || 0);
            };

            // Get correct slot price based on coupon status
            const getCorrectSlotPrice = (slot) => {
                if (slot.is_first_order_coupon_applied && slot.promo_discounted_price) {
                    return parseFloat(slot.promo_discounted_price);
                }

                return parseFloat(slot.original_discounted_price || slot.discounted_price || 0);
            };

            watch(totalPrice, async (newValue) => {
                const roundedValue = Math.round(newValue * 100) / 100;
                if (roundedValue === 0) {
                    submitButton.value.disabled = true;
                }
            });

            // Initialize cart sync on mount
            onMounted(() => {
                // Initial calculation
                recalculateCartTotals();
            });

            const ensureCartDataStructure = (tourId) => {
                if (!cart.value.tours) cart.value.tours = {};
                if (!cart.value.tours[tourId]) {
                    cart.value.tours[tourId] = {
                        data: {
                            price: {},
                            subtotal: 0,
                            total_price: 0,
                            service_fee: 0
                        }
                    };
                }
                if (!cart.value.tours[tourId].data) {
                    cart.value.tours[tourId].data = {
                        price: {},
                        subtotal: 0,
                        total_price: 0,
                        service_fee: 0
                    };
                }
                if (!cart.value.tours[tourId].data.price) {
                    cart.value.tours[tourId].data.price = {};
                }

                // Ensure cart-level totals exist
                if (!cart.value.subtotal) cart.value.subtotal = 0;
                if (!cart.value.total_price) cart.value.total_price = 0;
                if (!cart.value.service_fee) cart.value.service_fee = 0;
            };



            const cartTours = computed(() => {
                const toursArray = Object.values(cartToursData.value);
                return toursArray.filter(tour => cart.value.tours[tour.id]);
            });

            const getPromoTourPricing = (tourId) => {
                return promoToursData.value[tourId] || [];
            }

            const getTourPackages = (tourId) => {
                const tourData = promoToursData.value[tourId] || [];
                return tourData;
            };

            const hasAnyPromoQuantity = computed(() => {
                return Object.values(promoToursData.value).some(tourPromos =>
                    tourPromos.some(item => item.quantity > 0 && item.source === 'promo')
                );
            });

            const formatTimeLabel = (time) => {
                const [hours, minutes] = time.split(':').map(Number);
                if (hours && minutes) return `${hours} hr ${minutes} mins`;
                if (hours) return `${hours} hour`;
                return `${minutes} mins`;
            };

            const handleSelectedSlotChange = (addOn) => {
                if (!Array.isArray(addOn.selected_slots)) {
                    addOn.selected_slots = [];
                }

                // Limit selection to quantity
                if (addOn.selected_slots.length > addOn.quantity) {
                    addOn.selected_slots = addOn.selected_slots.slice(0, addOn.quantity);
                }

                // Recalculate totals
                recalculateCartTotals();

                // Sync with backend
                syncCartWithBackend();
            };

            const recalculateCartTotals = () => {
                let cartSubtotal = 0;
                let cartTotalPrice = 0;

                Object.keys(cart.value.tours).forEach(tourId => {
                    const tourCart = cart.value.tours[tourId];
                    const tourPackages = getTourPackages(tourId);
                    let tourSubtotal = 0;

                    // Calculate tour subtotal from packages
                    tourPackages.forEach(pkg => {
                        if (pkg.quantity > 0) {
                            if (pkg.promo_is_free === 1) {
                                // Free packages don't add to price
                                return;
                            }

                            if (pkg.type === 'timeslot' && pkg.selected_slots) {
                                // Calculate timeslot addon prices
                                pkg.selected_slots.slice(0, pkg.quantity).forEach(
                                    slotTime => {
                                        const slot = pkg.slots.find(s => s.time ===
                                            slotTime);
                                        if (slot) {
                                            tourSubtotal += getCorrectSlotPrice(slot);
                                        }
                                    });
                            } else {
                                const price = getCorrectPrice(pkg);
                                tourSubtotal += price * pkg.quantity;
                            }
                        }
                    });

                    // Add extra prices
                    const extraPrices = tourCart.extra_prices || [];
                    extraPrices.forEach(extra => {
                        tourSubtotal += parseFloat(extra.price || 0);
                    });

                    const serviceFee = parseFloat(tourCart.service_fee || 0);
                    const tourTotalPrice = tourSubtotal + serviceFee;

                    // Update tour cart data
                    tourCart.subtotal = tourSubtotal;
                    tourCart.total_price = tourTotalPrice;

                    cartSubtotal += tourSubtotal;
                    cartTotalPrice += tourTotalPrice;
                });

                // Update cart totals
                cart.value.subtotal = cartSubtotal;
                cart.value.total_price = cartTotalPrice;
                totalPrice.value = cartTotalPrice;
            };
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
                    cart.value['tours'][tourId]['data']['subtotal'] =
                        cart.value['tours'][tourId]['data']['subtotal'] + selectedPrice *
                        cart.value['tours'][
                            tourId
                        ]['data']['time_slot_quantity'];
                    cart.value['tours'][tourId]['data']['total_price'] =
                        cart.value['tours'][tourId]['data']['total_price'] + selectedPrice *
                        cart.value['tours']
                        [tourId]['data']['time_slot_quantity'];
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
                        // Remove tour from cart
                        delete cart.value.tours[id];

                        // Remove from tour data
                        cartToursData.value = Object.fromEntries(
                            Object.entries(cartToursData.value).filter(([key, tour]) =>
                                tour.id !== id)
                        );

                        // Remove from promo data
                        if (promoToursData.value[id]) {
                            delete promoToursData.value[id];
                        }

                        // Check if cart is empty and flush if needed
                        const remainingTours = Object.keys(cart.value.tours || {});
                        if (remainingTours.length === 0) {
                            // Flush entire cart
                            cart.value = {
                                tours: {},
                                subtotal: 0,
                                total_price: 0,
                                service_fee: 0,
                                applied_coupons: []
                            };
                            cartToursData.value = {};
                            promoToursData.value = {};
                            totalPrice.value = 0;

                            // Flush cart session on backend
                            flushCartSession();
                        } else {
                            // Recalculate totals for remaining tours
                            recalculateCartTotals();

                            // Sync with backend
                            syncCartWithBackend();
                        }
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
                const currencySymbolHtml = @json(currencySymbol()->toHtml());
                const formattedPrice = Number(price).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return `${currencySymbolHtml}${formattedPrice}`;
            };

            const formatDate = (dateString) => {
                if (!dateString) return '';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };

            // Debounce utility function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            const syncCartWithBackend = debounce(async () => {
                try {
                    // Update cart data with current tourData quantities
                    Object.keys(cart.value.tours).forEach(tourId => {
                        const tourPackages = getTourPackages(tourId);
                        const tourCart = cart.value.tours[tourId];

                        // Update tourData in cart
                        tourCart.tourData = tourPackages;

                        // Sync quantities to price structure for backend compatibility
                        if (!tourCart.data) tourCart.data = {};
                        if (!tourCart.data.price) tourCart.data.price = {};

                        tourPackages.forEach(pkg => {
                            const key = formatNameForInput(pkg.promo_title || pkg
                                .title);
                            tourCart.data.price[key] = {
                                quantity: pkg.quantity || 0,
                                price: getCorrectPrice(pkg),
                                selected_slots: pkg.selected_slots || []
                            };
                        });
                    });

                    const response = await fetch('/cart/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            cart: cart.value
                        })
                    });

                    if (!response.ok) {
                        console.error('Cart sync failed:', response.statusText);
                    }
                } catch (error) {
                    console.error('Cart sync error:', error);
                }
            }, 1000);

            const flushCartSession = async () => {
                try {
                    const response = await fetch('/cart/flush', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        console.log('Cart session flushed successfully');
                        // Optionally redirect to home or show empty cart message
                        window.location.href = '/cart';
                    } else {
                        console.error('Failed to flush cart session:', response.statusText);
                    }
                } catch (error) {
                    console.error('Cart flush error:', error);
                }
            };

            const updateNormalQuantity = (action, personType, tour, nomalIndex) => {
                const toursNormalPrice = getNormalTourPricing(tour.id);
                const personData = toursNormalPrice[formatNameForInput(personType)];
                if (!personData) return;

                updateTotalPrice(tour, action, nomalIndex);
            };

            const updatePromoQuantity = (action, personType, tour, promoIndex) => {
                const tourPackages = getTourPackages(tour.id);
                if (!tourPackages || !tourPackages[promoIndex]) return;

                const packageItem = tourPackages[promoIndex];
                const minPerson = parseInt(packageItem.min_person) || 0;
                const maxPerson = parseInt(packageItem.max_person) || 999;

                if (action === 'plus' && packageItem.quantity < maxPerson) {
                    packageItem.quantity = (packageItem.quantity || 0) + 1;
                } else if (action === 'minus' && packageItem.quantity > minPerson) {
                    packageItem.quantity--;
                }

                // Recalculate cart totals
                recalculateCartTotals();

                // Sync with backend
                syncCartWithBackend();
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
                ensureCartDataStructure(tour.id);

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
                                ].quantity--;
                            }
                        }
                        break;
                    case 'promo':
                        // Use the new updatePromoQuantity function
                        updatePromoQuantity(action, null, tour, index);
                        break;
                }
            };


            const formatNameForInput = (name) => {
                return name.toLowerCase().replace(/ /g, '_');
            };

            const getBookingAdditional = (tourId) => {
                return toursBookingAdditional.value[tourId] || null;
            };

            const formatTime = (time) => {
                if (!time) return null;

                const [hourStr, minute] = time.split(':');
                let hour = parseInt(hourStr, 10);
                const ampm = hour >= 12 ? 'PM' : 'AM';

                hour = hour % 12;
                if (hour === 0) hour = 12;

                return `${hour}:${minute} ${ampm}`;
            };
            const errors = ref({})
            const onTimeInput = (selection, tourId, from, to) => {
                if (!selection || !from || !to) {
                    errors.value[tourId] = null
                    return
                }

                // Convert "HH:mm" to minutes since midnight
                const toMinutes = (t) => {
                    const [h, m] = t.split(':').map(Number)
                    return h * 60 + m
                }

                let selMinutes = toMinutes(selection)
                let fromMinutes = toMinutes(from)
                let toMinutesVal = toMinutes(to)

                // If the range spans past midnight, adjust toMinutes
                if (fromMinutes > toMinutesVal) {
                    // Treat times after midnight as +1440 minutes
                    if (selMinutes < fromMinutes) {
                        selMinutes += 24 * 60
                    }
                    toMinutesVal += 24 * 60
                }

                if (selMinutes < fromMinutes || selMinutes > toMinutesVal) {
                    errors.value[tourId] =
                        `Please select a time between ${formatTime(from)} and ${formatTime(to)}.`
                } else {
                    errors.value[tourId] = null
                }
            }

            const formatAMPM = (time) => {
                if (!time) return '';
                let [hours, minutes] = time.split(':');
                hours = parseInt(hours);
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // 0 → 12
                return `${hours}:${minutes} ${ampm}`;
            }

            const handleBookingAdditionalChange = (tourId) => {
                // Sync with backend when booking additional selection changes
                syncCartWithBackend();
            };

            const isBookingAdditionalValid = (tourId) => {
                const bookingAdditional = getBookingAdditional(tourId);
                if (!bookingAdditional || bookingAdditional.enabled != 1) {
                    return true; // Not required if disabled
                }

                const bookingSelections = cart.value.tours[tourId]?.booking_additional_selections || {};
                const additionalType = bookingAdditional.additional_type;

                if (errors.value[tourId]) {
                    return false
                }

                if (additionalType === 'activities') {
                    const selectionType = bookingAdditional.activities?.selection_type;
                    if (selectionType === 'multiple_selection') {
                        const activity = bookingAdditional.activities?.multiple_selection?.activity || [];
                        const selections = bookingSelections.selection || {};
                        // Check if all required dropdowns have selections
                        for (const activityType of activity) {
                            if (!selections[activityType] || selections[activityType] === '') {
                                return false;
                            }
                        }
                    }
                    return true;
                } else if (additionalType === 'pickup_location') {
                    const selection = bookingSelections.selection || {};
                    // Both location_type and address must be filled
                    return selection.location_type && selection.location_type !== '' &&
                        selection.address && selection.address.trim() !== '';
                } else {
                    // For non-activities types, check if selection exists and is not empty
                    return bookingSelections.selection && bookingSelections.selection !== '';
                }
            };

            const canProceedToCheckout = computed(() => {
                // Check if all tours with booking_additional have valid selections
                for (const tourId of Object.keys(cart.value.tours || {})) {
                    if (!isBookingAdditionalValid(tourId)) {
                        return false;
                    }
                }
                return true;
            });

            return {
                cart,
                cartTours,
                getImageUrl,
                removeTour,
                formatPrice,
                formatDate,
                totalPrice,
                updateQuantity,
                updatePromoQuantity,
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
                formatTime,
                errors,
                onTimeInput,
                hasAnyPromoQuantity,
                getTourPackages,
                formatTimeLabel,
                formatAMPM,
                handleSelectedSlotChange,
                ensureCartDataStructure,
                recalculateCartTotals,
                syncCartWithBackend,
                getCorrectPrice,
                getCorrectSlotPrice,
                toursBookingAdditional,
                getBookingAdditional,
                handleBookingAdditionalChange,
                isBookingAdditionalValid,
                canProceedToCheckout,
            };
        },
    });
    Cart.mount('#cart-items');
</script>
