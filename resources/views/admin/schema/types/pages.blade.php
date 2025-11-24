<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'))">
    <!-- Hidden field to store final JSON -->
    <input type="hidden" name="schema_json" :value="jsonPreview()">

    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body form-fields">
                    <div class="row">
                        <div class="col-12 mb-3 title title--sm">Local Business Schema</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@type</label>
                                <select x-model="schema['@type']" name="schema[@type][]" multiple class="select2-select"
                                    data-field="@type">
                                    <option value="LocalBusiness">LocalBusiness</option>
                                    <option value="TravelAgency">TravelAgency</option>
                                    <option value="TouristInformationCenter">TouristInformationCenter</option>
                                    <option value="LodgingBusiness">LodgingBusiness</option>
                                    <option value="Hotel">Hotel</option>
                                    <option value="Campground">Campground</option>
                                    <option value="SportsActivityLocation">SportsActivityLocation</option>
                                    <option value="EventVenue">EventVenue</option>
                                    <option value="EntertainmentBusiness">EntertainmentBusiness</option>
                                    <option value="TouristAttraction">TouristAttraction</option>
                                    <option value="TouristDestination">TouristDestination</option>
                                    <option value="Trip">Trip</option>
                                    <option value="TouristTrip">TouristTrip</option>
                                    <option value="Place">Place</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.name" name="schema[name]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">foundingDate (Year Only)</label>
                                <input type="text"
                                style="background-color: #fff !important; "
                                 class="yearpicker field" x-ref="year" readonly>
                                <input type="hidden" x-model="schema.foundingDate" name="schema[foundingDate]">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.url" name="schema[url]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">logo</label>
                                <input type="text" x-model="schema.logo" name="schema[logo]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">image</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Image URL</th>
                                                <th class="text-end" scope="col" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in schema.image" :key="index">
                                                <tr>
                                                    <td>
                                                        <input x-model="schema.image[index]"
                                                            :name="`schema[image][${index}]`" type="text"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="delete-btn delete-btn--static"
                                                                @click="removeFromArray('image', index)"
                                                                :disabled="schema.image.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button"
                                                                class="add-btn ms-auto add-btn--static"
                                                                @click="insertInArray('image', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn ms-auto" @click="addToArray('image')">Add
                                        <i class="bx bx-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.description" name="schema[description]" class="field" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">email</label>
                                <input type="email" x-model="schema.email" name="schema[email]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">telephone</label>
                                <div class="phone-input-wrapper" data-phone-wrapper>
                                    <input type="tel" x-model="schema.telephone" name="schema[telephone]"
                                        class="field phone-input" data-phone-input placeholder="Phone number">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">priceRange</label>
                                <input type="text" x-model="schema.priceRange" name="schema[priceRange]"
                                    class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">paymentAccepted</label>
                                <select multiple x-model="schema.paymentAccepted" name="schema[paymentAccepted][]"
                                    class="select2-select" data-field="paymentAccepted">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Visa">Visa</option>
                                    <option value="MasterCard">MasterCard</option>
                                    <option value="American Express">American Express</option>
                                    <option value="Discover">Discover</option>
                                    <option value="UnionPay">UnionPay</option>
                                    <option value="Diners Club">Diners Club</option>
                                    <option value="Mobile Payment">Mobile Payment</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Apple Pay">Apple Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                    <option value="Tabby">Tabby</option>
                                    <option value="Tamara">Tamara</option>
                                    <option value="Samsung Pay">Samsung Pay</option>
                                    <option value="Voucher">Voucher</option>
                                    <option value="Gift Card">Gift Card</option>
                                    <option value="Online Payment">Online Payment</option>
                                    <option value="On Arrival">On Arrival</option>
                                    <option value="Installment Payment">Installment Payment</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">openingHours</label>
                                <input type="text" x-model="schema.openingHours" name="schema[openingHours]"
                                    class="field" placeholder="Mo-Su 08:00-22:00">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Address</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">streetAddress</label>
                                <input type="text" x-model="schema.address.streetAddress"
                                    name="schema[address][streetAddress]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressLocality</label>
                                <input type="text" x-model="schema.address.addressLocality"
                                    name="schema[address][addressLocality]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressCountry</label>
                                <select x-model="schema.address.addressCountry" name="schema[address][addressCountry]"
                                    class="select2-select" data-field="address.addressCountry">
                                    <option value="">Select Country</option>
                                    @foreach ($countriesCities as $countryCode => $countryData)
                                        <option value="{{ $countryCode }}">{{ $countryData['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Geo Coordinates</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">latitude</label>
                                <input type="text" x-model="schema.geo.latitude" name="schema[geo][latitude]"
                                    class="field" placeholder="25.1606">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">longitude</label>
                                <input type="text" x-model="schema.geo.longitude" name="schema[geo][longitude]"
                                    class="field" placeholder="55.4142">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">hasMap</label>
                                <input type="text" x-model="schema.hasMap" name="schema[hasMap]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Area Served</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">City</label>
                                <select x-model="schema.areaServed.name" name="schema[areaServed][name]"
                                    class="field select2-select" data-field="areaServed.name">
                                    <option value="">Select City</option>
                                    @foreach ($countriesCities as $countryCode => $countryData)
                                        @foreach ($countryData['cities'] as $city)
                                            <option value="{{ $city }}">{{ $city }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Languages</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">knowsLanguage</label>
                                <select multiple x-model="schema.knowsLanguage" name="schema[knowsLanguage][]"
                                    class="select2-select" data-field="knowsLanguage">
                                    @foreach ($languages as $language)
                                        <option value="{{ $language }}">{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Knows About (Services/Topics)</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">knowsAbout</label>
                                <input type="text" x-ref="knowsAboutInput" class="field choices-tags"
                                    data-choices-field="knowsAbout">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Aggregate Rating</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">ratingValue</label>
                                <input type="number" step="0.1" x-model="schema.aggregateRating.ratingValue"
                                    name="schema[aggregateRating][ratingValue]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">reviewCount</label>
                                <input type="number" x-model="schema.aggregateRating.reviewCount"
                                    name="schema[aggregateRating][reviewCount]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Offer Catalog</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">Catalog Name</label>
                                <input type="text" x-model="schema.hasOfferCatalog.name"
                                    name="schema[hasOfferCatalog][name]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">Offers</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Offer Details</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(offer, index) in schema.hasOfferCatalog.itemListElement"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <label class="small mb-1">Offer Name</label>
                                                                <input type="text"
                                                                    x-model="schema.hasOfferCatalog.itemListElement[index].name"
                                                                    :name="`schema[hasOfferCatalog][itemListElement][${index}][name]`"
                                                                    class="field" placeholder="Offer name">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small mb-1">Price</label>
                                                                <input type="text"
                                                                    x-model="schema.hasOfferCatalog.itemListElement[index].price"
                                                                    :name="`schema[hasOfferCatalog][itemListElement][${index}][price]`"
                                                                    class="field" placeholder="Price">
                                                            </div>
                                                            <div class="col-4">
                                                                <label class="small mb-1">Currency</label>
                                                                <select
                                                                    x-model="schema.hasOfferCatalog.itemListElement[index].priceCurrency"
                                                                    :name="`schema[hasOfferCatalog][itemListElement][${index}][priceCurrency]`"
                                                                    class="field">
                                                                    @foreach ($currencies as $code => $name)
                                                                        <option value="{{ $code }}">
                                                                            {{ $code }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="small mb-1">URL</label>
                                                                <input type="text"
                                                                    x-model="schema.hasOfferCatalog.itemListElement[index].url"
                                                                    :name="`schema[hasOfferCatalog][itemListElement][${index}][url]`"
                                                                    class="field" placeholder="https://...">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('hasOfferCatalog.itemListElement', index)"
                                                                :disabled="schema.hasOfferCatalog.itemListElement.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('hasOfferCatalog.itemListElement', index, {'@type': 'Offer', name: '', price: '', priceCurrency: 'AED', url: ''})">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('hasOfferCatalog.itemListElement', {'@type': 'Offer', name: '', price: '', priceCurrency: 'AED', url: ''})">Add
                                        Offer</button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Social Links (sameAs)</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">Social Media URLs</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>URL</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in schema.sameAs" :key="index">
                                                <tr>
                                                    <td>
                                                        <input type="text" x-model="schema.sameAs[index]"
                                                            :name="`schema[sameAs][${index}]`" class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromArray('sameAs', index)"
                                                                :disabled="schema.sameAs.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInArray('sameAs', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn" @click="addToArray('sameAs')">Add Link <i
                                            class="bx bx-plus"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-box preview-box-wrapper">
                <div class="form-box__header">
                    <div class="title">JSON Preview</div>
                </div>
                <div class="form-box__body">
                    <div class="preview-box"
                        style="background: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto;">
                        <pre style="margin: 0; white-space: pre-wrap; word-wrap: break-word;" x-text="jsonPreview()"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script>
        function schemaManager() {
            const defaults = {
                '@context': 'https://schema.org',
                '@type': ['LocalBusiness'],
                name: '',
                foundingDate: '',
                url: '',
                logo: '',
                image: [''],
                description: '',
                email: '',
                telephone: '',
                priceRange: '',
                paymentAccepted: [],
                openingHours: '',
                address: {
                    '@type': 'PostalAddress',
                    streetAddress: '',
                    addressLocality: '',
                    addressCountry: 'AE'
                },
                geo: {
                    '@type': 'GeoCoordinates',
                    latitude: '',
                    longitude: ''
                },
                hasMap: '',
                areaServed: {
                    '@type': 'City',
                    name: ''
                },
                knowsLanguage: [],
                knowsAbout: [],
                aggregateRating: {
                    '@type': 'AggregateRating',
                    ratingValue: '',
                    reviewCount: ''
                },
                hasOfferCatalog: {
                    '@type': 'OfferCatalog',
                    name: '',
                    itemListElement: [{
                        '@type': 'Offer',
                        name: '',
                        price: '',
                        priceCurrency: 'AED',
                        url: ''
                    }]
                },
                sameAs: ['']
            };

            return {
                schema: {
                    ...defaults
                },
                choicesInstance: null,

                init(initialSchema = {}) {
                    // Merge with defaults
                    this.schema = {
                        ...defaults,
                        ...initialSchema,
                        '@type': initialSchema['@type'] || defaults['@type'],
                        image: initialSchema.image || defaults.image,
                        paymentAccepted: initialSchema.paymentAccepted || defaults.paymentAccepted,
                        address: {
                            ...defaults.address,
                            ...(initialSchema.address || {})
                        },
                        geo: {
                            ...defaults.geo,
                            ...(initialSchema.geo || {})
                        },
                        areaServed: {
                            ...defaults.areaServed,
                            ...(initialSchema.areaServed || {})
                        },
                        knowsLanguage: initialSchema.knowsLanguage || defaults.knowsLanguage,
                        knowsAbout: initialSchema.knowsAbout || defaults.knowsAbout,
                        aggregateRating: {
                            ...defaults.aggregateRating,
                            ...(initialSchema.aggregateRating || {})
                        },
                        hasOfferCatalog: {
                            ...defaults.hasOfferCatalog,
                            ...(initialSchema.hasOfferCatalog || {}),
                            itemListElement: (initialSchema.hasOfferCatalog && initialSchema.hasOfferCatalog
                                .itemListElement) || defaults.hasOfferCatalog.itemListElement
                        },
                        sameAs: initialSchema.sameAs || defaults.sameAs
                    };

                    // Ensure arrays
                    if (!Array.isArray(this.schema['@type'])) {
                        this.schema['@type'] = this.schema['@type'] ? [this.schema['@type']] : defaults['@type'];
                    }

                    if (!Array.isArray(this.schema.image)) {
                        this.schema.image = this.schema.image ? [this.schema.image] : [''];
                    }
                    if (this.schema.image.length === 0) this.schema.image = [''];

                    if (!Array.isArray(this.schema.paymentAccepted)) {
                        this.schema.paymentAccepted = this.schema.paymentAccepted ? [this.schema.paymentAccepted] : [];
                    }

                    if (!Array.isArray(this.schema.knowsLanguage)) {
                        this.schema.knowsLanguage = this.schema.knowsLanguage ? [this.schema.knowsLanguage] : [];
                    }

                    if (!Array.isArray(this.schema.knowsAbout)) {
                        this.schema.knowsAbout = this.schema.knowsAbout ? [this.schema.knowsAbout] : [];
                    }

                    if (!Array.isArray(this.schema.sameAs)) {
                        this.schema.sameAs = this.schema.sameAs ? [this.schema.sameAs] : [''];
                    }
                    if (this.schema.sameAs.length === 0) this.schema.sameAs = [''];

                    if (!Array.isArray(this.schema.hasOfferCatalog.itemListElement)) {
                        this.schema.hasOfferCatalog.itemListElement = defaults.hasOfferCatalog.itemListElement;
                    }

                    // Initialize Select2
                    this.$nextTick(() => {
                        this.$el.querySelectorAll('.select2-select').forEach((el) => {
                            const select = $(el);
                            select.select2({
                                placeholder: 'Select options',
                                allowClear: true
                            });

                            const dataField = el.getAttribute('data-field');
                            if (dataField) {
                                const keys = dataField.split('.');
                                let value = this.schema;
                                for (let key of keys) {
                                    value = value[key];
                                }
                                select.val(value || []).trigger('change');
                                select.on('change', (e) => {
                                    let target = this.schema;
                                    for (let i = 0; i < keys.length - 1; i++) {
                                        target = target[keys[i]];
                                    }
                                    target[keys[keys.length - 1]] = $(e.target).val() || (el
                                        .hasAttribute(
                                            'multiple') ? [] : '');
                                });
                            }
                        });

                        // Initialize Choices.js for all tag fields (reusable)
                        this.$el.querySelectorAll('[data-choices-field]').forEach((element) => {
                            if (!element.classList.contains('choices__input')) {
                                const fieldName = element.getAttribute('data-choices-field');
                                const choicesInstance = new Choices(element, {
                                    removeItemButton: true,
                                    addItems: true,
                                    duplicateItemsAllowed: false,
                                    editItems: true,
                                    placeholder: true,
                                    placeholderValue: 'Type and press Enter to add',
                                    delimiter: ',',
                                });

                                // Set initial values if any
                                if (this.schema[fieldName] && this.schema[fieldName].length > 0) {
                                    choicesInstance.setValue(this.schema[fieldName]);
                                }

                                // Listen for changes - update schema on add/remove
                                element.addEventListener('addItem', () => {
                                    setTimeout(() => {
                                        const items = choicesInstance.getValue(true);
                                        this.schema[fieldName] = Array.isArray(items) ?
                                            items : (items ? [items] : []);
                                    }, 10);
                                });

                                element.addEventListener('removeItem', () => {
                                    setTimeout(() => {
                                        const items = choicesInstance.getValue(true);
                                        this.schema[fieldName] = Array.isArray(items) ?
                                            items : [];
                                    }, 10);
                                });
                            }
                        });

                        this.$nextTick(() => {
                            const yearInput = this.$el.querySelector('[x-ref="year"]');
                            if (yearInput) {
                                $(yearInput).yearpicker({
                                    startYear: 1900,
                                    endYear: new Date().getFullYear(),
                                    onChange: (value) => {
                                        this.schema.foundingDate = value;
                                    }
                                });
                                if (this.schema.foundingDate) {
                                    $(yearInput).yearpicker('setYear', this.schema.foundingDate);
                                }
                            }
                        });

                    });
                },

                // Array helpers
                addToArray(field) {
                    if (!Array.isArray(this.schema[field])) {
                        this.schema[field] = [];
                    }
                    this.schema[field].push('');
                },

                insertInArray(field, index) {
                    if (!Array.isArray(this.schema[field])) {
                        this.schema[field] = [];
                    }
                    this.schema[field].splice(index + 1, 0, '');
                },

                removeFromArray(field, index) {
                    if (Array.isArray(this.schema[field]) && this.schema[field].length > 1) {
                        this.schema[field].splice(index, 1);
                    }
                },

                // Nested array helpers
                addToNestedArray(path, defaultValue = '') {
                    const keys = path.split('.');
                    let target = this.schema;
                    for (let i = 0; i < keys.length; i++) {
                        if (i === keys.length - 1) {
                            if (!Array.isArray(target[keys[i]])) target[keys[i]] = [];
                            target[keys[i]].push(defaultValue);
                        } else {
                            target = target[keys[i]];
                        }
                    }
                },

                insertInNestedArray(path, index, defaultValue = '') {
                    const keys = path.split('.');
                    let target = this.schema;
                    for (let i = 0; i < keys.length; i++) {
                        if (i === keys.length - 1) {
                            if (!Array.isArray(target[keys[i]])) target[keys[i]] = [];
                            target[keys[i]].splice(index + 1, 0, defaultValue);
                        } else {
                            target = target[keys[i]];
                        }
                    }
                },

                removeFromNestedArray(path, index) {
                    const keys = path.split('.');
                    let target = this.schema;
                    for (let i = 0; i < keys.length; i++) {
                        if (i === keys.length - 1) {
                            if (Array.isArray(target[keys[i]]) && target[keys[i]].length > 1) {
                                target[keys[i]].splice(index, 1);
                            }
                        } else {
                            target = target[keys[i]];
                        }
                    }
                },

                jsonPreview() {
                    return JSON.stringify(this.schema, null, 2);
                }
            }
        }
    </script>
    <script src="https://choices-js.github.io/Choices/assets/scripts/choices.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/yearpicker.js@1.0.1/dist/yearpicker.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/yearpicker.js@1.0.1/dist/yearpicker.min.js"></script>

    <script>
        // Initialize all phone inputs (reusable)
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                document.querySelectorAll('[data-phone-wrapper]').forEach(wrapper => {
                    const input = wrapper.querySelector('[data-phone-input]');
                    if (input) {
                        // Get existing value from Alpine
                        const alpineComponent = Alpine.$data(document.querySelector('[x-data]'));
                        const existingValue = alpineComponent?.schema?.telephone || '';

                        // Initialize intl-tel-input
                        const iti = window.intlTelInput(input, {
                            initialCountry: "ae",
                            separateDialCode: true,
                            preferredCountries: ["ae", "sa", "qa", "om", "kw", "bh"],
                            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
                        });

                        // Set existing number if available
                        if (existingValue) {
                            iti.setNumber(existingValue);
                        }

                        // Function to update full number
                        const updateFullNumber = () => {
                            const fullNumber = iti.getNumber();
                            if (fullNumber) {
                                if (alpineComponent && alpineComponent.schema) {
                                    alpineComponent.schema.telephone = fullNumber;
                                }
                            }
                        };

                        // Update on input
                        input.addEventListener('input', updateFullNumber);
                        input.addEventListener('blur', updateFullNumber);
                        input.addEventListener('countrychange', updateFullNumber);
                    }
                });
            }, 100);
        });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <link rel="stylesheet" href="https://choices-js.github.io/Choices/assets/styles/choices.min.css"
        crossorigin="anonymous" />
    <style>
        .preview-box-wrapper {
            position: sticky;
            top: 1rem;
        }

        .preview-box {
            font-size: 0.85rem;
        }

        body .form-fields .title {
            text-transform: initial
        }

        .choices__inner {
            min-height: 44px;
            padding: 7.5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }

        .choices__list--multiple .choices__item {
            background-color: #00376b;
            border: 1px solid #00376b;
        }

        /* Phone input styles */
        .phone-input-wrapper {
            position: relative;
        }

        .phone-input-wrapper .iti {
            width: 100%;
        }

        .phone-input-wrapper .iti__flag-container {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .phone-input-wrapper input.phone-input {
            padding-left: 52px;
        }
    </style>
@endpush
