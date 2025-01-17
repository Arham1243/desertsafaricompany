@php
    $promoTourData = $tour->promoPrices->map(function ($promoPrice) {
        $discountPercent = 0;
        if ($promoPrice->original_price > 0) {
            $discountPercent =
                (($promoPrice->original_price - $promoPrice->promo_price) / $promoPrice->original_price) * 100;
        }
        $isNotExpired = \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($promoPrice->offer_expire_at));

        return [
            'promo_title' => $promoPrice->promo_title,
            'original_price' => $promoPrice->original_price,
            'discount_price' => $promoPrice->promo_price,
            'offer_expire_at' => $promoPrice->offer_expire_at,
            'is_not_expired' => $isNotExpired,
            'quantity' => 0,
        ];
    });

    $normalTourData = $tour->normalPrices->mapWithKeys(function ($price) {
        return [
            strtolower(str_replace(' ', '_', $price->person_type)) => [
                'price' => $price->price,
                'min' => $price->min_person,
                'max' => $price->max_person,
                'quantity' => 0,
            ],
        ];
    });
    $waterPricesTimeSlots = $tour->waterPrices->isNotEmpty() ? $tour->waterPrices->pluck('time') : null;
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

            const initialTotalPrice = parseFloat("{{ $tour->initial_price ?? 0 }}");
            const normalTourData = ref(@json($normalTourData));
            const promoTourData = ref(@json($promoTourData));
            const isSubmitEnabled = ref(false);

            const showToast = (type, message) => {
                if (type === 'error') {
                    $.toast({
                        heading: 'Error!',
                        position: 'bottom-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 5000,
                        text: message,
                        stack: 6
                    });
                } else {
                    $.toast({
                        text: message,
                        heading: 'Success!',
                        position: 'bottom-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 2000,
                        stack: 6
                    });
                }
            }

            const updateTotalPrice = () => {
                @if (!Auth::check())
                    showToast('error', 'Please Login to continue.');
                @endif
                @if (isset($isTourInCart) && $isTourInCart)
                    showToast('error', 'Tour already added to cart.');
                @endif
                totalPrice.value = initialTotalPrice;

                promoTourData.value.forEach((promo) => {
                    const applicablePrice = promo.is_not_expired ? promo.discount_price : promo
                        .original_price;
                    totalPrice.value += applicablePrice * promo.quantity;
                });

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
                const promoData = promoTourData.value.find(promo => promo.promo_title === personType);
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

            const formatPrice = computed(() => `{{ env('APP_CURRENCY') }} ${totalPrice.value.toFixed(2)}`);

            watch(timeSlot, updateTotalPrice);

            const getTimeLeft = (expireAt) => {
                const now = new Date();
                const expiry = new Date(expireAt);
                const diffMs = expiry - now;

                if (diffMs <= 0) return "expired";

                const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                if (diffDays > 0) return `${diffDays} day${diffDays > 1 ? "s" : ""}`;
                if (diffHours > 0) return `${diffHours} hour${diffHours > 1 ? "s" : ""}`;
                return `${diffMinutes} minute${diffMinutes > 1 ? "s" : ""}`;
            };

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
                getTimeLeft,
                isSubmitEnabled
            };
        },
    });
    PricingBox.mount('#tour-pricing');
</script>
