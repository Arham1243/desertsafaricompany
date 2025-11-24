<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'))">
    <!-- Hidden field to store final @graph JSON - this is what gets saved to database -->
    <!-- The SchemaController should use this field for saving -->
    <input type="hidden" name="schema_graph" :value="jsonPreview()">

    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body form-fields">
                    <div class="row">
                        <div class="col-12 mb-3 title title--sm">@type Sports Activity Location</div>

                        <!-- Basic Fields -->
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.location['@id']" name="schema[location][@id]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.location.name" name="schema[location][name]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">alternateName</label>
                                <input type="text" x-model="schema.location.alternateName"
                                    name="schema[location][alternateName]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.location.description" name="schema[location][description]" class="field" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.location.url" name="schema[location][url]"
                                    class="field">
                            </div>
                        </div>

                        <!-- Image Repeater -->
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">image</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Image URL</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in schema.location.image"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input x-model="schema.location.image[index]"
                                                            :name="`schema[location][image][${index}]`" type="text"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('location.image', index)"
                                                                :disabled="schema.location.image.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('location.image', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('location.image')">Add Image</button>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="col-12 mb-3 title title--sm">@type PostalAddress</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">streetAddress</label>
                                <input type="text" x-model="schema.location.address.streetAddress"
                                    name="schema[location][address][streetAddress]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressCountry</label>
                                <select x-model="schema.location.address.addressCountry"
                                    name="schema[location][address][addressCountry]" class="field">
                                    <option value="">Select Country</option>
                                    <template x-for="country in getCountries()" :key="country.code">
                                        <option :value="country.code" x-text="country.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressLocality (City)</label>
                                <select x-model="schema.location.address.addressLocality"
                                    name="schema[location][address][addressLocality]" class="field"
                                    :disabled="!schema.location.address.addressCountry">
                                    <option value="">Select City</option>
                                    <template
                                        x-for="city in getCitiesForCountry(schema.location.address.addressCountry)"
                                        :key="city">
                                        <option :value="city" x-text="city"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressRegion</label>
                                <input type="text" x-model="schema.location.address.addressRegion"
                                    name="schema[location][address][addressRegion]" class="field">
                            </div>
                        </div>

                        <!-- Geo Coordinates -->
                        <div class="col-12 mb-3 title title--sm">@type GeoCoordinates</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">latitude</label>
                                <input type="number" step="0.0001" x-model="schema.location.geo.latitude"
                                    name="schema[location][geo][latitude]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">longitude</label>
                                <input type="number" step="0.0001" x-model="schema.location.geo.longitude"
                                    name="schema[location][geo][longitude]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">hasMap</label>
                                <input type="text" x-model="schema.location.hasMap"
                                    name="schema[location][hasMap]" class="field">
                            </div>
                        </div>

                        <!-- Opening Hours Repeater -->
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">openingHoursSpecification</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Days of Week</th>
                                                <th>Opens</th>
                                                <th>Closes</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template
                                                x-for="(item, index) in schema.location.openingHoursSpecification"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <select multiple
                                                            x-model="schema.location.openingHoursSpecification[index].dayOfWeek"
                                                            :name="`schema[location][openingHoursSpecification][${index}][dayOfWeek][]`"
                                                            class="field select2-select">
                                                            <option value="Monday">Monday</option>
                                                            <option value="Tuesday">Tuesday</option>
                                                            <option value="Wednesday">Wednesday</option>
                                                            <option value="Thursday">Thursday</option>
                                                            <option value="Friday">Friday</option>
                                                            <option value="Saturday">Saturday</option>
                                                            <option value="Sunday">Sunday</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="time"
                                                            x-model="schema.location.openingHoursSpecification[index].opens"
                                                            :name="`schema[location][openingHoursSpecification][${index}][opens]`"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <input type="time"
                                                            x-model="schema.location.openingHoursSpecification[index].closes"
                                                            :name="`schema[location][openingHoursSpecification][${index}][closes]`"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('location.openingHoursSpecification', index)"
                                                                :disabled="schema.location.openingHoursSpecification.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('location.openingHoursSpecification', index, {'@type': 'OpeningHoursSpecification', dayOfWeek: [], opens: '', closes: ''})">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('location.openingHoursSpecification', {'@type': 'OpeningHoursSpecification', dayOfWeek: [], opens: '', closes: ''})">Add
                                        Hours</button>
                                </div>
                            </div>
                        </div>

                        <!-- Price & Payment -->
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">priceRange</label>
                                <input type="text" x-model="schema.location.priceRange"
                                    name="schema[location][priceRange]" class="field" placeholder="AED 250–AED 450">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">paymentAccepted</label>
                                <select multiple x-model="schema.location.paymentAccepted"
                                    name="schema[location][paymentAccepted][]" class="field select2-payment-methods"
                                    data-field="location.paymentAccepted" style="width: 100%;">
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Online Payment">Online Payment</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Apple Pay">Apple Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                </select>
                            </div>
                        </div>

                        <!-- Aggregate Rating -->
                        <div class="col-12 mb-3 title title--sm">@type AggregateRating</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">ratingValue</label>
                                <input type="number" step="0.1"
                                    x-model="schema.location.aggregateRating.ratingValue"
                                    name="schema[location][aggregateRating][ratingValue]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">reviewCount</label>
                                <input type="number" x-model="schema.location.aggregateRating.reviewCount"
                                    name="schema[location][aggregateRating][reviewCount]" class="field">
                            </div>
                        </div>

                        <!-- Makes Offer Section -->
                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Offer (makesOffer)</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">priceCurrency</label>
                                <select multiple x-model="schema.location.makesOffer.priceCurrency"
                                    name="schema[location][makesOffer][priceCurrency][]" class="select2-select"
                                    data-field="location.makesOffer.priceCurrency">
                                    @foreach ($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">price</label>
                                <input type="text" x-model="schema.location.makesOffer.price"
                                    name="schema[location][makesOffer][price]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">availability</label>
                                <input type="text" x-model="schema.location.makesOffer.availability"
                                    name="schema[location][makesOffer][availability]" class="field"
                                    placeholder="https://schema.org/InStock">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.location.makesOffer.url"
                                    name="schema[location][makesOffer][url]" class="field">
                            </div>
                        </div>

                        <!-- Service (itemOffered) -->
                        <div class="col-12 mb-3 title title--sm">@type Service (itemOffered)</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.location.makesOffer.itemOffered.name"
                                    name="schema[location][makesOffer][itemOffered][name]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.location.makesOffer.itemOffered.description"
                                    name="schema[location][makesOffer][itemOffered][description]" class="field" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">audienceType</label>
                                <select multiple x-model="schema.location.makesOffer.itemOffered.audience.audienceType"
                                    name="schema[location][makesOffer][itemOffered][audience][audienceType][]"
                                    class="field select2-select"
                                    data-field="location.makesOffer.itemOffered.audience.audienceType"
                                    style="width: 100%;">
                                    <option value="Families">Families</option>
<option value="Solo Travelers">Solo Travelers</option>
<option value="Local Residents">Local Residents</option>
<option value="Adventure Seekers">Adventure Seekers</option>
<option value="Couples">Couples</option>
<option value="Corporate Groups">Corporate Groups</option>
<option value="Tourists">Tourists</option>
<option value="budget travel">budget travel</option>
<option value="Groups of Friends">Groups of Friends</option>
<option value="Honeymooners">Honeymooners</option>
<option value="less budget">less budget</option>
<option value="Cultural tours">Cultural tours</option>
<option value="Cultural Enthusiasts">Cultural Enthusiasts</option>
<option value="Seek exclusive">Seek exclusive</option>
<option value="private tours">private tours</option>
<option value="high-end experiences">high-end experiences</option>
<option value="local traditions">local traditions</option>
<option value="Interested in museums">Interested in museums</option>
<option value="Business Travelers">Business Travelers</option>
<option value="Nature Lovers">Nature Lovers</option>
<option value="Food Lovers">Food Lovers</option>
<option value="Photography Enthusiasts">Photography Enthusiasts</option>
<option value="Travel Agents">Travel Agents</option>
                                </select>
                            </div>
                        </div>

                        <!-- Provider (Organization) -->
                        <div class="col-12 mb-3 title title--sm">@type Organization (provider)</div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.location.makesOffer.itemOffered.provider.name"
                                    name="schema[location][makesOffer][itemOffered][provider][name]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.location.makesOffer.itemOffered.provider.url"
                                    name="schema[location][makesOffer][itemOffered][provider][url]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">logo</label>
                                <input type="text" x-model="schema.location.makesOffer.itemOffered.provider.logo"
                                    name="schema[location][makesOffer][itemOffered][provider][logo]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">sameAs (Social Links)</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>URL</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template
                                                x-for="(item, index) in schema.location.makesOffer.itemOffered.provider.sameAs"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input
                                                            x-model="schema.location.makesOffer.itemOffered.provider.sameAs[index]"
                                                            :name="`schema[location][makesOffer][itemOffered][provider][sameAs][${index}]`"
                                                            type="text" class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('location.makesOffer.itemOffered.provider.sameAs', index)"
                                                                :disabled="schema.location.makesOffer.itemOffered.provider.sameAs
                                                                    .length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('location.makesOffer.itemOffered.provider.sameAs', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('location.makesOffer.itemOffered.provider.sameAs')">Add
                                        Link</button>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Page (keep existing from bus.blade.php) -->
                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type FAQ Page</div>

                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="title mb-0">FAQ Section </label>
                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                    data-disabled-text="Disabled">
                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                        id="enable_faq_switch" x-model="faqEnabled" @change="toggleFaq()"
                                        name="enable_faq">
                                    <label class="form-check-label" for="enable_faq_switch">Disabled</label>
                                </div>
                            </div>
                        </div>

                        <div x-show="faqEnabled">
                            <div class="col-12 mb-3">
                                <div class="form-fields">
                                    <label class="title">@id</label>
                                    <input type="text" x-model="schema.faq['@id']" name="schema[faq][@id]"
                                        class="field">
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-fields">
                                    <label class="title">mainEntity (FAQ Items)</label>
                                    <div class="repeater-table">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(item, index) in schema.faq.mainEntity"
                                                    :key="index">
                                                    <tr>
                                                        <td><input type="text"
                                                                x-model="schema.faq.mainEntity[index].name"
                                                                :name="`schema[faq][mainEntity][${index}][name]`"
                                                                class="field"></td>
                                                        <td>
                                                            <textarea x-model="schema.faq.mainEntity[index].acceptedAnswer.text"
                                                                :name="`schema[faq][mainEntity][${index}][acceptedAnswer][text]`" class="field" rows="2"></textarea>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="button"
                                                                    class="delete-btn delete-btn--static"
                                                                    @click="removeFromNestedArray('faq.mainEntity', index)"
                                                                    :disabled="schema.faq.mainEntity.length === 1">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                                <button type="button" class="add-btn add-btn--static"
                                                                    @click="insertInNestedArray('faq.mainEntity', index, {'@type': 'Question', name: '', acceptedAnswer: {'@type': 'Answer', text: ''}})">
                                                                    <i class='bx bx-plus'></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <button type="button" class="themeBtn"
                                            @click="addToNestedArray('faq.mainEntity', {'@type': 'Question', name: '', acceptedAnswer: {'@type': 'Answer', text: ''}})">Add
                                            FAQ</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Breadcrumb (keep existing from bus.blade.php) -->
                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Breadcrumb List</div>

                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="title mb-0">Breadcrumb Navigation </label>
                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                    data-disabled-text="Disabled">
                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                        id="enable_breadcrumb_switch" x-model="breadcrumbEnabled"
                                        @change="toggleBreadcrumb()" name="enable_breadcrumb">
                                    <label class="form-check-label" for="enable_breadcrumb_switch">Disabled</label>
                                </div>
                            </div>
                        </div>

                        <div x-show="breadcrumbEnabled">
                            <div class="col-12 mb-3">
                                <div class="form-fields">
                                    <label class="title">@id</label>
                                    <input type="text" x-model="schema.breadcrumb['@id']"
                                        name="schema[breadcrumb][@id]" class="field">
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-fields">
                                    <label class="title">itemListElement (Breadcrumb Items)</label>
                                    <div class="repeater-table">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Position</th>
                                                    <th>Name</th>
                                                    <th>URL</th>
                                                    <th style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(item, index) in schema.breadcrumb.itemListElement"
                                                    :key="index">
                                                    <tr>
                                                        <td><input type="number"
                                                                x-model="schema.breadcrumb.itemListElement[index].position"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][position]`"
                                                                class="field"></td>
                                                        <td><input type="text"
                                                                x-model="schema.breadcrumb.itemListElement[index].name"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][name]`"
                                                                class="field"></td>
                                                        <td><input type="text"
                                                                x-model="schema.breadcrumb.itemListElement[index].item"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][item]`"
                                                                class="field"></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="button"
                                                                    class="delete-btn delete-btn--static"
                                                                    @click="removeFromNestedArray('breadcrumb.itemListElement', index)"
                                                                    :disabled="schema.breadcrumb.itemListElement.length === 1">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                                <button type="button" class="add-btn add-btn--static"
                                                                    @click="insertInNestedArray('breadcrumb.itemListElement', index, {'@type': 'ListItem', position: schema.breadcrumb.itemListElement.length + 1, name: '', item: ''})">
                                                                    <i class='bx bx-plus'></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <button type="button" class="themeBtn"
                                            @click="addToNestedArray('breadcrumb.itemListElement', {'@type': 'ListItem', position: schema.breadcrumb.itemListElement.length + 1, name: '', item: ''})">Add
                                            Breadcrumb</button>
                                    </div>
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
        // Countries and cities data from PHP
        const countriesCities = @json($countriesCities ?? []);

        function schemaManager() {
            const defaults = {
                '@context': 'https://schema.org',
                location: {
                    '@type': 'SportsActivityLocation',
                    '@id': '',
                    name: '',
                    alternateName: '',
                    description: '',
                    url: '',
                    image: [''],
                    address: {
                        '@type': 'PostalAddress',
                        streetAddress: '',
                        addressLocality: '',
                        addressRegion: '',
                        addressCountry: 'AE'
                    },
                    geo: {
                        '@type': 'GeoCoordinates',
                        latitude: '',
                        longitude: ''
                    },
                    hasMap: '',
                    openingHoursSpecification: [{
                        '@type': 'OpeningHoursSpecification',
                        dayOfWeek: [],
                        opens: '',
                        closes: ''
                    }],
                    priceRange: '',
                    paymentAccepted: [],
                    aggregateRating: {
                        '@type': 'AggregateRating',
                        ratingValue: '',
                        reviewCount: ''
                    },
                    makesOffer: {
                        '@type': 'Offer',
                        priceCurrency: [],
                        price: '',
                        availability: 'https://schema.org/InStock',
                        url: '',
                        itemOffered: {
                            '@type': 'Service',
                            name: '',
                            description: '',
                            audience: {
                                '@type': 'Audience',
                                audienceType: []
                            },
                            provider: {
                                '@type': 'Organization',
                                name: '',
                                url: '',
                                logo: '',
                                sameAs: ['']
                            }
                        }
                    }
                },
                faq: {
                    '@type': 'FAQPage',
                    '@id': '',
                    mainEntity: []
                },
                breadcrumb: {
                    '@type': 'BreadcrumbList',
                    '@id': '',
                    itemListElement: []
                }
            };

            return {
                schema: {
                    ...defaults
                },
                faqEnabled: false,
                breadcrumbEnabled: false,

                init(initialSchema = {}) {
                    // Check if initialSchema has @graph format
                    if (initialSchema['@graph']) {
                        // Load from @graph format
                        const graph = initialSchema['@graph'];

                        this.schema = {
                            '@context': initialSchema['@context'] || defaults['@context']
                        };

                        // Extract each object type from @graph
                        graph.forEach(item => {
                            if (item['@type'] === 'SportsActivityLocation') {
                                this.schema.location = {
                                    ...defaults.location,
                                    ...item,
                                    address: {
                                        ...defaults.location.address,
                                        ...(item.address || {})
                                    },
                                    geo: {
                                        ...defaults.location.geo,
                                        ...(item.geo || {})
                                    },
                                    aggregateRating: {
                                        ...defaults.location.aggregateRating,
                                        ...(item.aggregateRating || {})
                                    },
                                    makesOffer: {
                                        ...defaults.location.makesOffer,
                                        ...(item.makesOffer || {}),
                                        itemOffered: {
                                            ...defaults.location.makesOffer.itemOffered,
                                            ...((item.makesOffer && item.makesOffer.itemOffered) || {}),
                                            audience: {
                                                ...defaults.location.makesOffer.itemOffered.audience,
                                                ...((item.makesOffer && item.makesOffer.itemOffered && item.makesOffer.itemOffered.audience) || {})
                                            },
                                            provider: {
                                                ...defaults.location.makesOffer.itemOffered.provider,
                                                ...((item.makesOffer && item.makesOffer.itemOffered && item.makesOffer.itemOffered.provider) || {}),
                                                sameAs: ((item.makesOffer && item.makesOffer.itemOffered && item.makesOffer.itemOffered.provider && item.makesOffer.itemOffered.provider.sameAs) || defaults.location.makesOffer.itemOffered.provider.sameAs)
                                            }
                                        }
                                    },
                                    image: item.image || defaults.location.image,
                                    openingHoursSpecification: item.openingHoursSpecification || defaults.location.openingHoursSpecification,
                                    paymentAccepted: item.paymentAccepted || defaults.location.paymentAccepted
                                };
                            } else if (item['@type'] === 'FAQPage') {
                                this.schema.faq = {
                                    ...defaults.faq,
                                    ...item,
                                    mainEntity: item.mainEntity || []
                                };
                            } else if (item['@type'] === 'BreadcrumbList') {
                                this.schema.breadcrumb = {
                                    ...defaults.breadcrumb,
                                    ...item,
                                    itemListElement: item.itemListElement || []
                                };
                            }
                        });

                        // Fill in missing objects with defaults
                        if (!this.schema.location) this.schema.location = defaults.location;
                        if (!this.schema.faq) this.schema.faq = defaults.faq;
                        if (!this.schema.breadcrumb) this.schema.breadcrumb = defaults.breadcrumb;
                    } else {
                        // Load from flat format (backward compatibility)
                        this.schema = {
                            ...defaults,
                            ...initialSchema,
                            location: {
                                ...defaults.location,
                                ...(initialSchema.location || {}),
                                address: {
                                    ...defaults.location.address,
                                    ...((initialSchema.location && initialSchema.location.address) || {})
                                },
                                geo: {
                                    ...defaults.location.geo,
                                    ...((initialSchema.location && initialSchema.location.geo) || {})
                                },
                                aggregateRating: {
                                    ...defaults.location.aggregateRating,
                                    ...((initialSchema.location && initialSchema.location.aggregateRating) || {})
                                },
                                makesOffer: {
                                    ...defaults.location.makesOffer,
                                    ...((initialSchema.location && initialSchema.location.makesOffer) || {}),
                                    itemOffered: {
                                        ...defaults.location.makesOffer.itemOffered,
                                        ...((initialSchema.location && initialSchema.location.makesOffer && initialSchema.location.makesOffer.itemOffered) || {}),
                                        audience: {
                                            ...defaults.location.makesOffer.itemOffered.audience,
                                            ...((initialSchema.location && initialSchema.location.makesOffer && initialSchema.location.makesOffer.itemOffered && initialSchema.location.makesOffer.itemOffered.audience) || {})
                                        },
                                        provider: {
                                            ...defaults.location.makesOffer.itemOffered.provider,
                                            ...((initialSchema.location && initialSchema.location.makesOffer && initialSchema.location.makesOffer.itemOffered && initialSchema.location.makesOffer.itemOffered.provider) || {})
                                        }
                                    }
                                },
                                image: (initialSchema.location && initialSchema.location.image) || defaults.location.image,
                                openingHoursSpecification: (initialSchema.location && initialSchema.location.openingHoursSpecification) || defaults.location.openingHoursSpecification,
                                paymentAccepted: (initialSchema.location && initialSchema.location.paymentAccepted) || defaults.location.paymentAccepted
                            },
                            faq: {
                                ...defaults.faq,
                                ...(initialSchema.faq || {}),
                                mainEntity: (initialSchema.faq && initialSchema.faq.mainEntity) || []
                            },
                            breadcrumb: {
                                ...defaults.breadcrumb,
                                ...(initialSchema.breadcrumb || {}),
                                itemListElement: (initialSchema.breadcrumb && initialSchema.breadcrumb.itemListElement) || []
                            }
                        };
                    }

                    // Check if FAQ exists and enable the switch
                    if (this.schema.faq.mainEntity && this.schema.faq.mainEntity.length > 0) {
                        const hasContent = this.schema.faq.mainEntity.some(item => 
                            item.name || item.acceptedAnswer?.text
                        );
                        if (hasContent) {
                            this.faqEnabled = true;
                        }
                    }

                    // Check if Breadcrumb exists and enable the switch
                    if (this.schema.breadcrumb.itemListElement && this.schema.breadcrumb.itemListElement.length > 0) {
                        const hasContent = this.schema.breadcrumb.itemListElement.some(item => 
                            item.name || item.item
                        );
                        if (hasContent) {
                            this.breadcrumbEnabled = true;
                        }
                    }

                    // Ensure arrays
                    if (!Array.isArray(this.schema.location.image)) {
                        this.schema.location.image = this.schema.location.image ? [this.schema.location.image] : [''];
                    }
                    if (this.schema.location.image.length === 0) this.schema.location.image = [''];

                    if (!Array.isArray(this.schema.location.openingHoursSpecification)) {
                        this.schema.location.openingHoursSpecification = [defaults.location.openingHoursSpecification[0]];
                    }
                    if (this.schema.location.openingHoursSpecification.length === 0) {
                        this.schema.location.openingHoursSpecification = [defaults.location.openingHoursSpecification[0]];
                    }

                    if (!Array.isArray(this.schema.location.paymentAccepted)) {
                        this.schema.location.paymentAccepted = this.schema.location.paymentAccepted ? [this.schema.location.paymentAccepted] : [];
                    }

                    if (!Array.isArray(this.schema.location.makesOffer.itemOffered.audience.audienceType)) {
                        this.schema.location.makesOffer.itemOffered.audience.audienceType = this.schema.location.makesOffer.itemOffered.audience.audienceType ? [this.schema.location.makesOffer.itemOffered.audience.audienceType] : [];
                    }

                    if (!Array.isArray(this.schema.location.makesOffer.itemOffered.provider.sameAs)) {
                        this.schema.location.makesOffer.itemOffered.provider.sameAs = this.schema.location.makesOffer.itemOffered.provider.sameAs ? [this.schema.location.makesOffer.itemOffered.provider.sameAs] : [''];
                    }
                    if (this.schema.location.makesOffer.itemOffered.provider.sameAs.length === 0) {
                        this.schema.location.makesOffer.itemOffered.provider.sameAs = [''];
                    }

                    if (!Array.isArray(this.schema.faq.mainEntity)) {
                        this.schema.faq.mainEntity = [];
                    }
                    if (this.faqEnabled && this.schema.faq.mainEntity.length === 0) {
                        this.schema.faq.mainEntity = [{
                            '@type': 'Question',
                            name: '',
                            acceptedAnswer: {
                                '@type': 'Answer',
                                text: ''
                            }
                        }];
                    }

                    if (!Array.isArray(this.schema.breadcrumb.itemListElement)) {
                        this.schema.breadcrumb.itemListElement = [];
                    }
                    if (this.breadcrumbEnabled && this.schema.breadcrumb.itemListElement.length === 0) {
                        this.schema.breadcrumb.itemListElement = [{
                            '@type': 'ListItem',
                            position: 1,
                            name: '',
                            item: ''
                        }];
                    }

                    // Initialize Select2 selects
                    this.initializeSelect2();
                    
                    // Use $nextTick to ensure Alpine has rendered repeaters
                    this.$nextTick(() => {
                        this.initializeSelect2();
                    });
                },

                initializeSelect2() {
                    this.$el.querySelectorAll('.select2-select:not(.select2-hidden-accessible), .select2-payment-methods:not(.select2-hidden-accessible)').forEach((el) => {
                        const select = $(el);
                        select.select2({
                            placeholder: el.classList.contains('select2-payment-methods') ? 'Select payment methods' : el.name?.includes('dayOfWeek') ? 'Select days' : 'Select audience',
                            allowClear: true
                        });

                        // Handle nested fields (for non-repeater items with data-field)
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
                                target[keys[keys.length - 1]] = $(e.target).val() || [];
                            });
                        } else {
                            // For repeater items with x-model, sync Select2 changes back to Alpine
                            select.on('change', (e) => {
                                const event = new Event('change', { bubbles: true });
                                el.dispatchEvent(event);
                            });
                        }
                    });
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
                    // Reinitialize Select2 for new rows
                    this.$nextTick(() => {
                        this.initializeSelect2();
                    });
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
                    // Reinitialize Select2 for new rows
                    this.$nextTick(() => {
                        this.initializeSelect2();
                    });
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

                // Toggle FAQ on/off
                toggleFaq() {
                    if (this.faqEnabled) {
                        if (!this.schema.faq.mainEntity || this.schema.faq.mainEntity.length === 0) {
                            this.schema.faq.mainEntity = [{
                                '@type': 'Question',
                                name: '',
                                acceptedAnswer: {
                                    '@type': 'Answer',
                                    text: ''
                                }
                            }];
                        }
                    }
                },

                // Toggle breadcrumb on/off
                toggleBreadcrumb() {
                    if (this.breadcrumbEnabled) {
                        if (!this.schema.breadcrumb.itemListElement || this.schema.breadcrumb.itemListElement.length === 0) {
                            this.schema.breadcrumb.itemListElement = [{
                                '@type': 'ListItem',
                                position: 1,
                                name: '',
                                item: ''
                            }];
                        }
                    }
                },

                // Helper methods for countries and cities
                getCountries() {
                    return Object.keys(countriesCities).map(code => ({
                        code: code,
                        name: countriesCities[code].name
                    }));
                },
                getCitiesForCountry(countryCode) {
                    return countriesCities[countryCode]?.cities || [];
                },

                jsonPreview() {
                    // Build @graph array from all schema objects
                    const graph = [];

                    // Add SportsActivityLocation
                    if (this.schema.location) {
                        graph.push({
                            '@type': this.schema.location['@type'],
                            '@id': this.schema.location['@id'],
                            name: this.schema.location.name,
                            alternateName: this.schema.location.alternateName,
                            description: this.schema.location.description,
                            url: this.schema.location.url,
                            image: this.schema.location.image,
                            address: this.schema.location.address,
                            geo: this.schema.location.geo,
                            hasMap: this.schema.location.hasMap,
                            openingHoursSpecification: this.schema.location.openingHoursSpecification,
                            priceRange: this.schema.location.priceRange,
                            paymentAccepted: this.schema.location.paymentAccepted,
                            aggregateRating: this.schema.location.aggregateRating,
                            makesOffer: this.schema.location.makesOffer
                        });
                    }

                    // Add FAQPage (only if enabled)
                    if (this.faqEnabled && this.schema.faq) {
                        graph.push({
                            '@type': this.schema.faq['@type'],
                            '@id': this.schema.faq['@id'],
                            mainEntity: this.schema.faq.mainEntity
                        });
                    }

                    // Add BreadcrumbList (only if enabled)
                    if (this.breadcrumbEnabled && this.schema.breadcrumb) {
                        graph.push({
                            '@type': this.schema.breadcrumb['@type'],
                            '@id': this.schema.breadcrumb['@id'],
                            itemListElement: this.schema.breadcrumb.itemListElement
                        });
                    }

                    // Return final schema with @graph
                    return JSON.stringify({
                        '@context': this.schema['@context'],
                        '@graph': graph
                    }, null, 2);
                }
            }
        }
    </script>
@endpush
@push('css')
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
    </style>
@endpush
