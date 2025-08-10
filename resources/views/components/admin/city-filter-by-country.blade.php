@props([
    'countries' => [],
    'cities' => [],
    'selectedCountryId' => null,
    'selectedCityId' => null,
    'isCountryRequired' => true,
    'isCityRequired' => true,
    'wrapperClass' => 'row col-md-8',
    'countryColClass' => 'col-md-4 mb-4',
    'cityColClass' => 'col-md-4 mb-4',
    'countrySelectId' => 'country-select-' . \Illuminate\Support\Str::random(5),
    'citySelectId' => 'city-select-' . \Illuminate\Support\Str::random(5),
    'countryName' => 'country_id',
    'cityName' => 'city_id',
])

<div x-data="countryCityFilter()" x-init="init()" class="{{ $wrapperClass }}">
    <div class="{{ $countryColClass }}">
        <div class="form-fields">
            <label class="title">Country @if ($isCountryRequired)
                    <span class="text-danger"> *</span>
                @endif:</label>
            <select {{ $isCountryRequired ? 'data-required' : '' }} id="{{ $countrySelectId }}" name="{{ $countryName }}"
                class="select2-select" data-error="Country">
                <option value="">Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}"
                        {{ old($countryName, $selectedCountryId) == $country->id ? 'selected' : '' }}>
                        {{ $country->name }} - {{ $country->iso_alpha2 }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="{{ $cityColClass }}">
        <div class="form-fields">
            <label class="title">City @if ($isCityRequired)
                    <span class="text-danger"> *</span>
                @endif:</label>
            <select {{ $isCityRequired ? 'data-required' : '' }} id="{{ $citySelectId }}" name="{{ $cityName }}"
                class="select2-select" data-error="City">
                <option value="">Select City</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}"
                        {{ old($cityName, $selectedCityId) == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@push('js')
    <script>
        function countryCityFilter() {
            return {
                selectedCountry: '{{ $selectedCountryId ?? '' }}',
                selectedCity: '{{ $selectedCityId ?? '' }}',
                countrySelect: null,
                citySelect: null,
                suppressChange: false,

                init() {
                    this.countrySelect = document.getElementById('{{ $countrySelectId }}')
                    this.citySelect = document.getElementById('{{ $citySelectId }}')

                    $(this.countrySelect).off('change').on('change', (e) => {
                        if (this.suppressChange) return
                        this.selectedCountry = e.target.value
                        this.fetchCities()
                    })

                    if (this.selectedCountry) {
                        this.suppressChange = true
                        this.fetchCities(true).then(() => {
                            this.suppressChange = false
                        })
                    } else {
                        initializeSelect2()
                    }
                },

                fetchCities(isInit = false) {
                    if (!this.selectedCountry) {
                        this.selectedCity = ''
                        this.citySelect.innerHTML = `<option value="" selected disabled>Select City</option>`
                        initializeSelect2()
                        return Promise.resolve()
                    }

                    return fetch(`{{ url('admin/countries') }}/${this.selectedCountry}/cities`)
                        .then(res => res.json())
                        .then(data => {
                            let options = `<option value="" selected disabled>Select City</option>`
                            options += data.map(city => {
                                let selectedAttr = (isInit && '{{ $selectedCityId }}' == city.id) || (!
                                    isInit && this.selectedCity == city.id) ? 'selected' : ''
                                return `<option value="${city.id}" ${selectedAttr}>${city.name}</option>`
                            }).join('')
                            this.citySelect.innerHTML = options
                            initializeSelect2()
                        })
                }
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('countryCityFilter', countryCityFilter)
        })
    </script>
@endpush
