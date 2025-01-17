@php
    $normalTourData = $tour->normalPrices->mapWithKeys(function ($price) {
        return [
            strtolower(str_replace(' ', '_', $price->person_type)) => [
                'price' => $price->price,
                'quantity' => 0,
            ],
        ];
    });
    $waterPricesTimeSlots = $tour->waterPrices->isNotEmpty() ? $tour->waterPrices->pluck('time') : null;
@endphp
<script>
    const PricingBoxComponent = {
        setup() {
            const totalPrice = ref(parseFloat("{{ $total_price ?? 0 }}"));
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

            const initialTotalPrice = parseFloat("{{ $total_price ?? 0 }}");
            const normalTourData = ref(@json($normalTourData));

            const updateTotalPrice = () => {
                totalPrice.value = initialTotalPrice;

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
            };

            const updateQuantity = (action, personType = null) => {
                if (priceType === "private") {
                    const previousCars = Math.ceil(carQuantity.value / carMax);
                    if (action === "plus") carQuantity.value++;
                    if (action === "minus" && carQuantity.value > 0) carQuantity.value--;

                    const currentCars = Math.ceil(carQuantity.value / carMax);
                    totalPrice.value += (currentCars > previousCars ? carPrice : (currentCars < previousCars ? -
                        carPrice : 0));
                } else if (priceType === "normal" && personType) {
                    const personData = normalTourData.value[personType];
                    if (!personData) return;

                    personData.quantity += (action === "plus" ? 1 : (action === "minus" && personData.quantity >
                        0 ? -1 : 0));
                    updateTotalPrice();
                } else if (priceType === "water") {
                    if (action === "plus") timeSlotQuantity.value++;
                    if (action === "minus" && timeSlotQuantity.value > 0) timeSlotQuantity.value--;
                    updateTotalPrice();
                }
            };

            const formatPrice = computed(() => `{{ env('APP_CURRENCY') }} ${totalPrice.value.toFixed(2)}`);

            watch(timeSlot, updateTotalPrice);

            return {
                carQuantity,
                totalPrice,
                updateQuantity,
                formatPrice,
                normalTourData,
                timeSlot,
                timeSlotQuantity,
                waterPrices,
                waterPricesTimeSlots,
            };
        },
    };
    Vue.createApp(PricingBoxComponent).mount('#pricing-box');
</script>
