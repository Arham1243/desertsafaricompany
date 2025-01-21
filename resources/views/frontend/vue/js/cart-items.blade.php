@php
    $cartTours = $tours->whereIn('id', array_keys($cart['tours']));
    $toursPrivatePrices = $tours
        ->filter(function ($tour) {
            return $tour->price_type === 'private'; // Only process tours with price_type 'private'
        })
        ->mapWithKeys(function ($tour) use ($cart) {
            $quantity = $cart['tours'][$tour->id]['data']['price']['persons']['quantity'] ?? 0;

            $price = $tour->privatePrices; // Assuming privatePrices is a single model instance

            if (!$price) {
                return [$tour->id => null]; // Handle case where privatePrices is null
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

    $toursNormalPrices = $tours->mapWithKeys(function ($tour) use ($cart) {
        return [
            $tour->id => $tour->normalPrices->mapWithKeys(function ($price) use ($tour, $cart) {
                $formattedKey = formatNameForInput($price->person_type);
                $quantity = 0;

                if (isset($cart['tours'][$tour->id]['data']['price'][$formattedKey]['quantity'])) {
                    $quantity = $cart['tours'][$tour->id]['data']['price'][$formattedKey]['quantity'] ?? 0;
                }

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

    $promoToursData = $tours
        ->map(function ($tour) use ($cart) {
            return $tour->promoPrices->map(function ($promoPrice) use ($tour, $cart) {
                $discountPercent = 0;
                if ($promoPrice->original_price > 0) {
                    $discountPercent =
                        (($promoPrice->original_price - $promoPrice->promo_price) / $promoPrice->original_price) * 100;
                }
                $isNotExpired = \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($promoPrice->offer_expire_at));

                $promoTitle = formatNameForInput($promoPrice->promo_title);

                $quantity = 0;
                if (isset($cart['tours'][$tour->id]['data']['price'][formatNameForInput($promoTitle)]['quantity'])) {
                    $quantity =
                        $cart['tours'][$tour->id]['data']['price'][formatNameForInput($promoTitle)]['quantity'] ?? 0;
                }

                return [
                    'tour_id' => $tour->id,
                    'promo_title' => $promoPrice->promo_title,
                    'original_price' => $promoPrice->original_price,
                    'discount_price' => $promoPrice->promo_price,
                    'offer_expire_at' => getTimeLeft($promoPrice->offer_expire_at),
                    'is_not_expired' => $isNotExpired,
                    'quantity' => (int) $quantity,
                ];
            });
        })
        ->flatten(1)
        ->groupBy('tour_id');
@endphp
<script>
    const Cart = createApp({
        setup() {
            const cart = ref(@json($cart));
            const cartToursData = ref(@json($cartTours));
            const promoToursData = ref(@json($promoToursData));
            const toursNormalPrices = ref(@json($toursNormalPrices));
            const toursPrivatePrices = ref(@json($toursPrivatePrices));

            const totalPrice = ref(cart.value.total_price);

            const cartTours = computed(() =>
                cartToursData.value.filter(tour => cart.value.tours[tour.id])
            );
            const getPromoTourPricing = (tourId) => {
                return promoToursData.value[tourId] || [];
            }
            const getNormalTourPricing = (tourId) => {
                return toursNormalPrices.value[tourId] || [];
            }
            const getPrivateTourPricing = (tourId) => {
                return toursPrivatePrices.value[tourId] || {}
            };

            const removeTour = (id) => {
                const tour = cart.value.tours[id];
                if (tour) {
                    if (confirm('Are you sure you want to remove this tour?')) {

                        minusCartPrices(tour);
                        delete cart.value.tours[id];
                        cartToursData.value = cartToursData.value.filter(tour => tour.id !== id);
                    }
                } else {
                    showToast('error', 'Tour not found!');
                }
            };

            const minusCartPrices = (tour) => {
                cart.value.subtotal -= tour['data']['subtotal'];
                cart.value.service_fee -= tour['data']['service_fee'];
                cart.value.total_price -= tour['data']['total_price'];
                totalPrice.value = cart.value.total_price
            }

            const getImageUrl = (image) => {
                return image ? '{{ asset(':image') }}'.replace(":image", image) :
                    '{{ asset('admin/assets/images/placeholder.png') }}';
            }
            const formatPrice = (price) => {
                const formattedPrice = price.toLocaleString();
                return `{{ env('APP_CURRENCY') }} ${formattedPrice}`;
            };

            const updateNormalQuantity = (action, personType, tour, nomalIndex) => {
                const toursNormalPrice = getNormalTourPricing(tour.id);
                const personData = toursNormalPrice[formatNameForInput(personType)];
                if (!personData) return;

                updateTotalPrice(tour, action, nomalIndex);
            };

            const updatePromoQuantity = (action, personType, tour, promoIndex) => {
                const promoTourData = getPromoTourPricing(tour.id);
                const promoData = promoTourData.find(promo => formatNameForInput(promo.promo_title) ===
                    formatNameForInput(personType));
                if (!promoData) return;

                promoData.quantity += (action === "plus" ? 1 : (action === "minus" && promoData
                    .quantity > 0 ? -
                    1 : 0));
                updateTotalPrice(tour, action, promoIndex);
            };

            const updateQuantity = (action, personType = null, tour, index = null) => {
                if (tour.price_type === "private") {
                    updatePrivateQuantity(action);
                } else if (tour.price_type === "normal" && personType) {
                    updateNormalQuantity(action, personType, tour, index);
                } else if (tour.price_type === "promo" && personType) {
                    updatePromoQuantity(action, personType, tour, index);
                } else if (tour.price_type === "water") {
                    updateWaterQuantity(action);
                }
            };

            const updateTotalPrice = (tour, action, index = null) => {
                switch (tour.price_type) {
                    case 'normal':
                        const toursNormalPrice = getNormalTourPricing(tour.id);
                        const normalPrice = parseFloat(toursNormalPrice[index].price);


                        if (action === 'plus') {
                            if (toursNormalPrice[index].quantity < toursNormalPrice[index].max) {
                                totalPrice.value += normalPrice;
                                cart.value['tours'][tour.id]['data']['subtotal'] += normalPrice;
                                cart.value['tours'][tour.id]['data']['total_price'] += normalPrice;
                                cart.value['subtotal'] += normalPrice;
                                cart.value['total_price'] += normalPrice;
                                toursNormalPrices.value[tour.id][formatNameForInput(
                                    toursNormalPrice[index].person_type)].quantity++;
                            }
                        }

                        if (action === 'minus') {
                            if (toursNormalPrice[index].quantity > toursNormalPrice[index].min) {
                                totalPrice.value -= normalPrice;
                                cart.value['tours'][tour.id]['data']['subtotal'] -= normalPrice;
                                cart.value['tours'][tour.id]['data']['total_price'] -= normalPrice;
                                cart.value['subtotal'] -= normalPrice;
                                cart.value['total_price'] -= normalPrice;
                                toursNormalPrices.value[tour.id][formatNameForInput(
                                    toursNormalPrice[index].person_type)].quantity--;
                            }
                        }


                        break;
                    case 'promo':
                        const promoTourData = getPromoTourPricing(tour.id);
                        const promo = promoTourData[index]
                        const applicablePrice = promo.is_not_expired ? parseFloat(promo.discount_price) :
                            parseFloat(promo
                                .original_price);
                        if (action === 'plus') {
                            totalPrice.value += applicablePrice;
                        } else if (action === 'minus' && promo.quantity >= 0) {
                            totalPrice.value -= applicablePrice;
                            cart.value['tours'][tour.id]['data']['subtotal'] -= applicablePrice;
                            cart.value['tours'][tour.id]['data']['total_price'] -= applicablePrice;
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
                getPrivateTourPricing
            };
        },
    });
    Cart.mount('#cart-items');
</script>
