@php
    $normalTourData = $tour->normalPrices->mapWithKeys(function ($price) {
        return [
            strtolower(str_replace(' ', '_', $price->person_type)) => [
                'price' => $price->price,
                'quantity' => 0,
            ],
        ];
    });
@endphp
<script>
    const PricingBoxComponent = {
        setup() {

            const totalPrice = ref(parseFloat("{{ $total_price ?? 0 }}"));
            const priceType = "{{ $tour->price_type ?? 'simple' }}";

            const carQuantity = ref(0);
            const carPrice = parseFloat("{{ $tour->privatePrices->car_price ?? 0 }}");
            const carMax = parseInt("{{ $tour->privatePrices->max_person ?? 1 }}");

            const initialTotalPrice = parseFloat("{{ $total_price ?? 0 }}");
            const normalTourData = ref(@json($normalTourData));

            const updateTotalPrice = () => {
                totalPrice.value = initialTotalPrice + Object.values(normalTourData.value).reduce(
                    (sum, {
                        price,
                        quantity
                    }) => sum + price * quantity, 0
                );
            };

            const updateQuantity = (action, personType = null) => {
                if (priceType === 'private') {
                    const previousCars = Math.ceil(carQuantity.value / carMax);
                    if (action === "plus") carQuantity.value++;
                    if (action === "minus" && carQuantity.value > 0) carQuantity.value--;

                    const currentCars = Math.ceil(carQuantity.value / carMax);
                    totalPrice.value += (currentCars > previousCars ? carPrice : (currentCars < previousCars ? -
                        carPrice : 0));
                } else if (priceType === 'normal' && personType) {
                    const personData = normalTourData.value[personType];
                    if (!personData) return;

                    personData.quantity += (action === "plus" ? 1 : (action === "minus" && personData.quantity >
                        0 ? -1 : 0));
                    updateTotalPrice();
                }
            };

            const formatPrice = computed(() => `{{ env('APP_CURRENCY') }} ${totalPrice.value.toFixed(2)}`);

            return {
                carQuantity,
                totalPrice,
                updateQuantity,
                formatPrice,
                normalTourData,
            };
        }
    };
    Vue.createApp(PricingBoxComponent).mount('#pricing-box');
</script>
