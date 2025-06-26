@php
    use Carbon\Carbon;

    $promoTourData = $tour->promoPrices->map(function ($promoPrice) use ($tour) {
        $now = Carbon::now();
        $today = strtolower($now->englishDayOfWeek);
        $hourOfDay = $now->hour;

        $originalPrice = (float) $promoPrice->original_price;

        $promoDiscountConfig =
            isset($tour->promo_discount_config) && $tour->promo_discount_config
                ? json_decode($tour->promo_discount_config, true)
                : [];

        $isWeekend = in_array($today, ['friday', 'saturday', 'sunday']);

        $discountPercent = $isWeekend
            ? $promoDiscountConfig['weekend_discount_percent'] ?? 0
            : $promoDiscountConfig['weekday_discount_percent'] ?? 0;

        $timerHours = (int) ($isWeekend
            ? $promoDiscountConfig['weekend_timer_hours'] ?? 0
            : $promoDiscountConfig['weekday_timer_hours'] ?? 0);

        $hoursLeft = $timerHours > 0 ? $timerHours - ($hourOfDay % $timerHours) : 0;

        $discountedPrice = $originalPrice - $originalPrice * ($discountPercent / 100);

        return [
            'promo_title' => $promoPrice->promo_title,
            'original_price' => number_format($originalPrice, 2),
            'discount_percent' => $discountPercent,
            'discounted_price' => number_format($discountedPrice, 2),
            'quantity' => 0,
            'hours_left' => $hoursLeft,
        ];
    });

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
            const showAllPromos = ref(false)
            const startDate = ref(null)
            const tourId = ref(@json($tour->id))
            const fetchingPromoPrices = ref(null)

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
                } catch (error) {
                    showToast('error', error.response.data.message)
                } finally {
                    fetchingPromoPrices.value = false;
                }
            };

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

                promoTourData.value.forEach((promo) => {
                    const applicablePrice = promo.discounted_price;
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
                const promoData = promoTourData.value.find(promo => formatNameForInput(promo
                    .promo_title) === personType);
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
                const formattedPrice = Number(price).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return `{{ env('APP_CURRENCY') }}${formattedPrice}`;
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
                fetchingPromoPrices
            };
        },
    });
    PricingBox.mount('#tour-pricing');
</script>
