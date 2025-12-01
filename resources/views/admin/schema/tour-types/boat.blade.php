<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'))">
    <!-- Hidden field to store final @graph JSON - this is what gets saved to database -->
    <!-- The SchemaController should use this field for saving -->
    <input type="hidden" name="schema_graph" :value="jsonPreview()">
    <input type="hidden" name="type" value="boat-trip">

    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body form-fields">
                    <div class="row">
                        <div class="col-12 mb-3 title title--sm">@type Boat Trip</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.boatTrip['@id']" name="schema[boatTrip][@id]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.boatTrip.name" name="schema[boatTrip][name]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">alternateName</label>
                                <input type="text" x-model="schema.boatTrip.alternateName"
                                    name="schema[boatTrip][alternateName]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.boatTrip.description" name="schema[boatTrip][description]" class="field" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.boatTrip.url" name="schema[boatTrip][url]"
                                    class="field">
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
                                            <template x-for="(item, index) in schema.boatTrip.image"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input x-model="schema.boatTrip.image[index]"
                                                            :name="`schema[boatTrip][image][${index}]`" type="text"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('boatTrip.image', index)"
                                                                :disabled="schema.boatTrip.image.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button"
                                                                class="add-btn ms-auto add-btn--static"
                                                                @click="insertInNestedArray('boatTrip.image', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn ms-auto"
                                        @click="addToNestedArray('boatTrip.image')">Add <i
                                            class="bx bx-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">departureBoatTerminal @id</label>
                                <input type="text" x-model="schema.boatTrip.departureBoatTerminal['@id']"
                                    name="schema[boatTrip][departureBoatTerminal][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">arrivalBoatTerminal @id</label>
                                <input type="text" x-model="schema.boatTrip.arrivalBoatTerminal['@id']"
                                    name="schema[boatTrip][arrivalBoatTerminal][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">departureTime</label>
                                <input type="datetime-local" x-model="schema.boatTrip.departureTime"
                                    name="schema[boatTrip][departureTime]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">arrivalTime</label>
                                <input type="datetime-local" x-model="schema.boatTrip.arrivalTime"
                                    name="schema[boatTrip][arrivalTime]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">offers @id</label>
                                <input type="text" x-model="schema.boatTrip.offers['@id']"
                                    name="schema[boatTrip][offers][@id]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Departure Bus Station</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.departureBoatTerminal['@id']"
                                    name="schema[departureBoatTerminal][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.departureBoatTerminal.name"
                                    name="schema[departureBoatTerminal][name]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">streetAddress</label>
                                <input type="text" x-model="schema.departureBoatTerminal.address.streetAddress"
                                    name="schema[departureBoatTerminal][address][streetAddress]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressCountry</label>
                                <select x-model="schema.departureBoatTerminal.address.addressCountry"
                                    name="schema[departureBoatTerminal][address][addressCountry]" class="field">
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
                                <select x-model="schema.departureBoatTerminal.address.addressLocality"
                                    name="schema[departureBoatTerminal][address][addressLocality]" class="field"
                                    :disabled="!schema.departureBoatTerminal.address.addressCountry">
                                    <option value="">Select City</option>
                                    <template
                                        x-for="city in getCitiesForCountry(schema.departureBoatTerminal.address.addressCountry)"
                                        :key="city">
                                        <option :value="city" x-text="city"></option>
                                    </template>
                                </select>
                                <small class="text-muted"
                                    x-show="!schema.departureBoatTerminal.address.addressCountry">Please select a
                                    country
                                    first</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">hasMap</label>
                                <input type="text" x-model="schema.departureBoatTerminal.hasMap"
                                    name="schema[departureBoatTerminal][hasMap]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Arrival Bus Station</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.arrivalBoatTerminal['@id']"
                                    name="schema[arrivalBoatTerminal][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.arrivalBoatTerminal.name"
                                    name="schema[arrivalBoatTerminal][name]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">streetAddress</label>
                                <input type="text" x-model="schema.arrivalBoatTerminal.address.streetAddress"
                                    name="schema[arrivalBoatTerminal][address][streetAddress]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">addressCountry</label>
                                <select x-model="schema.arrivalBoatTerminal.address.addressCountry"
                                    name="schema[arrivalBoatTerminal][address][addressCountry]" class="field">
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
                                <select x-model="schema.arrivalBoatTerminal.address.addressLocality"
                                    name="schema[arrivalBoatTerminal][address][addressLocality]" class="field"
                                    :disabled="!schema.arrivalBoatTerminal.address.addressCountry">
                                    <option value="">Select City</option>
                                    <template
                                        x-for="city in getCitiesForCountry(schema.arrivalBoatTerminal.address.addressCountry)"
                                        :key="city">
                                        <option :value="city" x-text="city"></option>
                                    </template>
                                </select>
                                <small class="text-muted"
                                    x-show="!schema.arrivalBoatTerminal.address.addressCountry">Please select a country
                                    first</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">hasMap</label>
                                <input type="text" x-model="schema.arrivalBoatTerminal.hasMap"
                                    name="schema[arrivalBoatTerminal][hasMap]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Offer</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.offer['@id']" name="schema[offer][@id]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">priceCurrency</label>
                                <select multiple x-model="schema.offer.priceCurrency"
                                    name="schema[offer][priceCurrency][]" class="select2-select"
                                    data-field="offer.priceCurrency">
                                    @foreach ($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">price</label>
                                <input type="text" x-model="schema.offer.price" name="schema[offer][price]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">availability</label>
                                <select x-model="schema.offer.availability" name="schema[offer][availability]"
                                    class="field">
                                    <option value="">Select Availability</option>
                                    <option value="https://schema.org/InStock">In stock</option>
                                    <option value="https://schema.org/OutOfStock">Out of stock</option>
                                    <option value="https://schema.org/PreOrder">Preorder</option>
                                    <option value="https://schema.org/LimitedAvailability">Limited availability
                                    </option>
                                    <option value="https://schema.org/SoldOut">Sold out</option>
                                    <option value="https://schema.org/OnlineOnly">Online only</option>
                                    <option value="https://schema.org/PreSale">Pre sale</option>
                                    <option value="https://schema.org/Discontinued">Discontinued</option>
                                    <option value="https://schema.org/BackOrder">Backorder</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">validFrom</label>
                                <input type="date" x-model="schema.offer.validFrom"
                                    name="schema[offer][validFrom]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.offer.url" name="schema[offer][url]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">itemOffered @id</label>
                                <input type="text" x-model="schema.offer.itemOffered['@id']"
                                    name="schema[offer][itemOffered][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">seller @id</label>
                                <input type="text" x-model="schema.offer.seller['@id']"
                                    name="schema[offer][seller][@id]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Service</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.service['@id']" name="schema[service][@id]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.service.name" name="schema[service][name]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.service.description" name="schema[service][description]" class="field" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">audienceType</label>
                                <select multiple x-model="schema.service.audience.audienceType"
                                    name="schema[service][audience][audienceType][]" class="select2-select"
                                    data-field="service.audience.audienceType">
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
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">provider @id</label>
                                <input type="text" x-model="schema.service.provider['@id']"
                                    name="schema[service][provider][@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="mb-3 title title--sm">@type AggregateRating</div>
                            <div class="form-fields">
                                <label class="title">ratingValue</label>
                                <input type="number" step="0.1"
                                    x-model="schema.service.aggregateRating.ratingValue"
                                    name="schema[service][aggregateRating][ratingValue]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">bestRating</label>
                                <input type="number" x-model="schema.service.aggregateRating.bestRating"
                                    name="schema[service][aggregateRating][bestRating]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">ratingCount</label>
                                <input type="number" x-model="schema.service.aggregateRating.ratingCount"
                                    name="schema[service][aggregateRating][ratingCount]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">reviewCount</label>
                                <input type="number" x-model="schema.service.aggregateRating.reviewCount"
                                    name="schema[service][aggregateRating][reviewCount]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="title mb-0">Reviews</label>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input data-toggle-switch class="form-check-input" type="checkbox"
                                            id="enable_reviews_switch" x-model="reviewsEnabled"
                                            @change="toggleReviews()" name="enable_reviews">
                                        <label class="form-check-label" for="enable_reviews_switch">Disabled</label>
                                    </div>
                                </div>

                                <div class="repeater-table" x-show="reviewsEnabled">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Review Details</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(review, index) in schema.service.review"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column gap-3">

                                                            <label class="title ">Author</label>
                                                            <input type="text"
                                                                x-model="schema.service.review[index].author.name"
                                                                :name="`schema[service][review][${index}][author][name]`"
                                                                class="field">

                                                            <label class="title ">Date Published</label>
                                                            <input type="date"
                                                                x-model="schema.service.review[index].datePublished"
                                                                :name="`schema[service][review][${index}][datePublished]`"
                                                                class="field">

                                                            <label class="title ">Review Body</label>
                                                            <textarea x-model="schema.service.review[index].reviewBody" :name="`schema[service][review][${index}][reviewBody]`"
                                                                class="field" rows="2"></textarea>

                                                            <label class="title ">Rating Value</label>
                                                            <input type="number" step="0.1"
                                                                x-model="schema.service.review[index].reviewRating.ratingValue"
                                                                :name="`schema[service][review][${index}][reviewRating][ratingValue]`"
                                                                class="field">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex flex-column gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('service.review', index)"
                                                                :disabled="schema.service.review.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>

                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('service.review', index, {'@type': 'Review', author: {'@type': 'Person', name: ''}, datePublished: '', reviewBody: '', reviewRating: {'@type': 'Rating', ratingValue: '', bestRating: '5'}})">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('service.review', {'@type': 'Review', author: {'@type': 'Person', name: ''}, datePublished: '', reviewBody: '', reviewRating: {'@type': 'Rating', ratingValue: '', bestRating: '5'}})">Add
                                        Review</button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Local Business </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema.localBusiness['@id']"
                                    name="schema[localBusiness][@id]" class="field mb-3">

                                <label class="title">name</label>
                                <input type="text" x-model="schema.localBusiness.name"
                                    name="schema[localBusiness][name]" class="field mb-3">

                                <label class="title">url</label>
                                <input type="text" x-model="schema.localBusiness.url"
                                    name="schema[localBusiness][url]" class="field mb-3">

                                <label class="title">logo</label>
                                <input type="text" x-model="schema.localBusiness.logo"
                                    name="schema[localBusiness][logo]" class="field mb-3">

                                <label class="title">paymentAccepted </label>
                                <select multiple x-model="schema.localBusiness.paymentAccepted"
                                    name="schema[localBusiness][paymentAccepted][]" class="field select2-select"
                                    data-field="localBusiness.paymentAccepted" style="width: 100%;">
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
                                            <template x-for="(item, index) in schema.localBusiness.sameAs"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input type="text"
                                                            x-model="schema.localBusiness.sameAs[index]"
                                                            :name="`schema[localBusiness][sameAs][${index}]`"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('localBusiness.sameAs', index)"
                                                                :disabled="schema.localBusiness.sameAs.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('localBusiness.sameAs', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('localBusiness.sameAs')">Add Link</button>
                                </div>
                            </div>
                        </div>

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
                                                    <th>FAQ</th>
                                                    <th style="width: 100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(item, index) in schema.faq.mainEntity"
                                                    :key="index">
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex flex-column gap-2">
                                                                <div class="form-group">
                                                                    <label :for="`question-${index}`">Question</label>
                                                                    <input type="text"
                                                                        x-model="schema.faq.mainEntity[index].name"
                                                                        :name="`schema[faq][mainEntity][${index}][name]`"
                                                                        :id="`question-${index}`" class="field"
                                                                        placeholder="Enter question">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label :for="`answer-${index}`">Answer</label>
                                                                    <textarea x-model="schema.faq.mainEntity[index].acceptedAnswer.text"
                                                                        :name="`schema[faq][mainEntity][${index}][acceptedAnswer][text]`" :id="`answer-${index}`" class="field"
                                                                        rows="2" placeholder="Enter answer"></textarea>
                                                                </div>
                                                            </div>
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
                <div class="form-box__header d-flex justify-content-between align-items-center"style="line-height: 1;">
                    <div class="title">JSON Preview</div>
                    <button type="button" class="themeBtn" @click="copyJsonToClipboard()"
                        style="padding:0.5rem; font-size: 0.75rem;">
                        <i style="font-size: 0.75rem;" class='bx bx-copy'></i> Copy
                    </button>
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
                boatTrip: {
                    '@type': 'boatTrip',
                    '@id': '',
                    name: '',
                    alternateName: '',
                    description: '',
                    url: '',
                    image: [''],
                    departureBoatTerminal: {
                        '@id': ''
                    },
                    arrivalBoatTerminal: {
                        '@id': ''
                    },
                    departureTime: '',
                    arrivalTime: '',
                    offers: {
                        '@id': ''
                    }
                },
                departureBoatTerminal: {
                    '@type': 'BoatTerminal',
                    '@id': '',
                    name: '',
                    address: {
                        '@type': 'PostalAddress',
                        streetAddress: '',
                        addressLocality: '',
                        addressCountry: 'AE'
                    },
                    hasMap: ''
                },
                arrivalBoatTerminal: {
                    '@type': 'BoatTerminal',
                    '@id': '',
                    name: '',
                    address: {
                        '@type': 'PostalAddress',
                        streetAddress: '',
                        addressLocality: '',
                        addressCountry: 'AE'
                    },
                    hasMap: ''
                },
                offer: {
                    '@type': 'Offer',
                    '@id': '',
                    priceCurrency: [],
                    price: '',
                    availability: 'https://schema.org/InStock',
                    validFrom: '',
                    url: '',
                    itemOffered: {
                        '@id': ''
                    },
                    seller: {
                        '@id': ''
                    }
                },
                service: {
                    '@type': 'Service',
                    '@id': '',
                    name: '',
                    description: '',
                    audience: {
                        '@type': 'Audience',
                        audienceType: []
                    },
                    provider: {
                        '@id': ''
                    },
                    aggregateRating: {
                        '@type': 'AggregateRating',
                        ratingValue: '',
                        bestRating: '5',
                        ratingCount: '',
                        reviewCount: ''
                    },
                    review: []
                },
                localBusiness: {
                    '@type': 'LocalBusiness',
                    '@id': '',
                    name: '',
                    url: '',
                    logo: '',
                    paymentAccepted: [''],
                    sameAs: ['']
                },
                webPage: {
                    '@type': 'WebPage',
                    '@id': '',
                    url: '',
                    name: '',
                    isPartOf: {
                        '@type': 'WebSite',
                        name: '',
                        url: ''
                    },
                    mainEntity: {
                        '@id': ''
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
                reviewsEnabled: false,
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
                            if (item['@type'] === 'boatTrip') {
                                this.schema.boatTrip = {
                                    ...defaults.boatTrip,
                                    ...item,
                                    departureBoatTerminal: item.departureBoatTerminal || defaults.boatTrip
                                        .departureBoatTerminal,
                                    arrivalBoatTerminal: item.arrivalBoatTerminal || defaults.boatTrip
                                        .arrivalBoatTerminal,
                                    offers: item.offers || defaults.boatTrip.offers
                                };
                            } else if (item['@type'] === 'BoatTerminal') {
                                // Determine if departure or arrival by @id
                                if (this.schema.boatTrip && this.schema.boatTrip.departureBoatTerminal && item[
                                        '@id'] ===
                                    this.schema.boatTrip.departureBoatTerminal['@id']) {
                                    this.schema.departureBoatTerminal = {
                                        ...defaults.departureBoatTerminal,
                                        ...item,
                                        address: {
                                            ...defaults.departureBoatTerminal.address,
                                            ...(item.address || {})
                                        }
                                    };
                                } else {
                                    this.schema.arrivalBoatTerminal = {
                                        ...defaults.arrivalBoatTerminal,
                                        ...item,
                                        address: {
                                            ...defaults.arrivalBoatTerminal.address,
                                            ...(item.address || {})
                                        }
                                    };
                                }
                            } else if (item['@type'] === 'Offer') {
                                this.schema.offer = {
                                    ...defaults.offer,
                                    ...item,
                                    itemOffered: item.itemOffered || defaults.offer.itemOffered,
                                    seller: item.seller || defaults.offer.seller
                                };
                            } else if (item['@type'] === 'Service') {
                                this.schema.service = {
                                    ...defaults.service,
                                    ...item,
                                    audience: {
                                        ...defaults.service.audience,
                                        ...(item.audience || {}),
                                        audienceType: (item.audience && item.audience.audienceType) || defaults
                                            .service.audience.audienceType
                                    },
                                    provider: item.provider || defaults.service.provider,
                                    aggregateRating: {
                                        ...defaults.service.aggregateRating,
                                        ...(item.aggregateRating || {})
                                    },
                                    review: item.review || []
                                };
                            } else if (item['@type'] === 'LocalBusiness') {
                                this.schema.localBusiness = {
                                    ...defaults.localBusiness,
                                    ...item,
                                    paymentAccepted: (item.paymentAccepted && item.paymentAccepted.length > 0) ?
                                        item.paymentAccepted : defaults.localBusiness.paymentAccepted,
                                    sameAs: (item.sameAs && item.sameAs.length > 0) ? item.sameAs : defaults
                                        .localBusiness.sameAs
                                };
                            } else if (item['@type'] === 'WebPage') {
                                this.schema.webPage = {
                                    ...defaults.webPage,
                                    ...item,
                                    isPartOf: {
                                        ...defaults.webPage.isPartOf,
                                        ...(item.isPartOf || {})
                                    },
                                    mainEntity: item.mainEntity || defaults.webPage.mainEntity
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
                        if (!this.schema.boatTrip) this.schema.boatTrip = defaults.boatTrip;
                        if (!this.schema.departureBoatTerminal) this.schema.departureBoatTerminal = defaults
                            .departureBoatTerminal;
                        if (!this.schema.arrivalBoatTerminal) this.schema.arrivalBoatTerminal = defaults
                            .arrivalBoatTerminal;
                        if (!this.schema.offer) this.schema.offer = defaults.offer;
                        if (!this.schema.service) this.schema.service = defaults.service;
                        if (!this.schema.localBusiness) this.schema.localBusiness = defaults.localBusiness;
                        if (!this.schema.webPage) this.schema.webPage = defaults.webPage;
                        if (!this.schema.faq) this.schema.faq = defaults.faq;
                        if (!this.schema.breadcrumb) this.schema.breadcrumb = defaults.breadcrumb;
                    } else {
                        // Load from flat format (backward compatibility)
                        this.schema = {
                            ...defaults,
                            ...initialSchema,
                            boatTrip: {
                                ...defaults.boatTrip,
                                ...(initialSchema.boatTrip || {}),
                                departureBoatTerminal: {
                                    ...defaults.boatTrip.departureBoatTerminal,
                                    ...((initialSchema.boatTrip && initialSchema.boatTrip.departureBoatTerminal) || {})
                                },
                                arrivalBoatTerminal: {
                                    ...defaults.boatTrip.arrivalBoatTerminal,
                                    ...((initialSchema.boatTrip && initialSchema.boatTrip.arrivalBoatTerminal) || {})
                                },
                                offers: {
                                    ...defaults.boatTrip.offers,
                                    ...((initialSchema.boatTrip && initialSchema.boatTrip.offers) || {})
                                },
                                image: (initialSchema.boatTrip && initialSchema.boatTrip.image) || defaults.boatTrip
                                    .image
                            },
                            departureBoatTerminal: {
                                ...defaults.departureBoatTerminal,
                                ...(initialSchema.departureBoatTerminal || {}),
                                address: {
                                    ...defaults.departureBoatTerminal.address,
                                    ...((initialSchema.departureBoatTerminal && initialSchema.departureBoatTerminal
                                        .address) || {})
                                }
                            },
                            arrivalBoatTerminal: {
                                ...defaults.arrivalBoatTerminal,
                                ...(initialSchema.arrivalBoatTerminal || {}),
                                address: {
                                    ...defaults.arrivalBoatTerminal.address,
                                    ...((initialSchema.arrivalBoatTerminal && initialSchema.arrivalBoatTerminal
                                        .address) || {})
                                }
                            },
                            offer: {
                                ...defaults.offer,
                                ...(initialSchema.offer || {}),
                                itemOffered: {
                                    ...defaults.offer.itemOffered,
                                    ...((initialSchema.offer && initialSchema.offer.itemOffered) || {})
                                },
                                seller: {
                                    ...defaults.offer.seller,
                                    ...((initialSchema.offer && initialSchema.offer.seller) || {})
                                }
                            },
                            service: {
                                ...defaults.service,
                                ...(initialSchema.service || {}),
                                audience: {
                                    ...defaults.service.audience,
                                    ...((initialSchema.service && initialSchema.service.audience) || {}),
                                    audienceType: (initialSchema.service && initialSchema.service.audience &&
                                            initialSchema.service.audience.audienceType) || defaults.service.audience
                                        .audienceType
                                },
                                provider: {
                                    ...defaults.service.provider,
                                    ...((initialSchema.service && initialSchema.service.provider) || {})
                                },
                                aggregateRating: {
                                    ...defaults.service.aggregateRating,
                                    ...((initialSchema.service && initialSchema.service.aggregateRating) || {})
                                },
                                review: (initialSchema.service && initialSchema.service.review) || []
                            },
                            // LocalBusiness is always loaded from global settings (passed from controller)
                            localBusiness: initialSchema.localBusiness || defaults.localBusiness,
                            webPage: {
                                ...defaults.webPage,
                                ...(initialSchema.webPage || {}),
                                isPartOf: {
                                    ...defaults.webPage.isPartOf,
                                    ...((initialSchema.webPage && initialSchema.webPage.isPartOf) || {})
                                },
                                mainEntity: (initialSchema.webPage && initialSchema.webPage.mainEntity) || defaults
                                    .webPage.mainEntity
                            },
                            faq: {
                                ...defaults.faq,
                                ...(initialSchema.faq || {}),
                                mainEntity: (initialSchema.faq && initialSchema.faq.mainEntity) || []
                            },
                            breadcrumb: {
                                ...defaults.breadcrumb,
                                ...(initialSchema.breadcrumb || {}),
                                itemListElement: (initialSchema.breadcrumb && initialSchema.breadcrumb
                                    .itemListElement) || []
                            }
                        };
                    }

                    // Ensure arrays FIRST before checking switches
                    if (!Array.isArray(this.schema.boatTrip.image)) {
                        this.schema.boatTrip.image = this.schema.boatTrip.image ? [this.schema.boatTrip.image] : [''];
                    }
                    if (this.schema.boatTrip.image.length === 0) this.schema.boatTrip.image = [''];

                    if (!Array.isArray(this.schema.service.audience.audienceType)) {
                        this.schema.service.audience.audienceType = this.schema.service.audience.audienceType ? [this.schema
                            .service.audience.audienceType
                        ] : [];
                    }

                    if (!Array.isArray(this.schema.service.review)) {
                        this.schema.service.review = [];
                    }

                    if (!Array.isArray(this.schema.localBusiness.paymentAccepted)) {
                        this.schema.localBusiness.paymentAccepted = this.schema.localBusiness.paymentAccepted ? [this.schema
                            .localBusiness.paymentAccepted
                        ] : [];
                    }

                    if (!Array.isArray(this.schema.localBusiness.sameAs)) {
                        this.schema.localBusiness.sameAs = this.schema.localBusiness.sameAs ? [this.schema.localBusiness
                            .sameAs
                        ] : [''];
                    }
                    if (this.schema.localBusiness.sameAs.length === 0) this.schema.localBusiness.sameAs = [''];

                    if (!Array.isArray(this.schema.faq.mainEntity)) {
                        this.schema.faq.mainEntity = [];
                    }

                    if (!Array.isArray(this.schema.breadcrumb.itemListElement)) {
                        this.schema.breadcrumb.itemListElement = [];
                    }

                    // NOW check if content exists and enable switches
                    // Check if reviews exist and enable the switch
                    if (this.schema.service.review && this.schema.service.review.length > 0) {
                        const hasContent = this.schema.service.review.some(review =>
                            review.author?.name || review.reviewBody || review.reviewRating?.ratingValue
                        );
                        if (hasContent) {
                            this.reviewsEnabled = true;
                        }
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

                    // Only add default items if switches are enabled but arrays are empty
                    if (this.reviewsEnabled && this.schema.service.review.length === 0) {
                        this.schema.service.review = [defaults.service.review[0]];
                    }
                    if (this.faqEnabled && this.schema.faq.mainEntity.length === 0) {
                        this.schema.faq.mainEntity = [defaults.faq.mainEntity[0]];
                    }
                    if (this.breadcrumbEnabled && this.schema.breadcrumb.itemListElement.length === 0) {
                        this.schema.breadcrumb.itemListElement = [defaults.breadcrumb.itemListElement[0]];
                    }

                    // Initialize Select2 selects
                    this.$el.querySelectorAll('.select2-select, .select2-payment-methods').forEach((el) => {
                        const select = $(el);
                        select.select2({
                            placeholder: el.classList.contains('select2-payment-methods') ?
                                'Select payment methods' : 'Select options',
                            allowClear: true
                        });

                        // Handle nested fields (e.g., service.audience.audienceType, localBusiness.paymentAccepted)
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

                // Toggle reviews on/off
                toggleReviews() {
                    if (this.reviewsEnabled) {
                        // Enabled - ensure at least one review exists
                        if (!this.schema.service.review || this.schema.service.review.length === 0) {
                            this.schema.service.review = [{
                                '@type': 'Review',
                                author: {
                                    '@type': 'Person',
                                    name: ''
                                },
                                datePublished: '',
                                reviewBody: '',
                                reviewRating: {
                                    '@type': 'Rating',
                                    ratingValue: '',
                                    bestRating: '5'
                                }
                            }];
                        }
                    }
                    // When disabled, we keep the reviews but hide them
                    // They will be excluded from JSON output if reviewsEnabled is false
                },

                // Toggle FAQ on/off
                toggleFaq() {
                    if (this.faqEnabled) {
                        // Enabled - ensure at least one FAQ exists
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
                        // Enabled - ensure at least one breadcrumb exists
                        if (!this.schema.breadcrumb.itemListElement || this.schema.breadcrumb.itemListElement.length ===
                            0) {
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

                    // Add boatTrip
                    if (this.schema.boatTrip) {
                        graph.push({
                            '@type': this.schema.boatTrip['@type'],
                            '@id': this.schema.boatTrip['@id'],
                            name: this.schema.boatTrip.name,
                            alternateName: this.schema.boatTrip.alternateName,
                            description: this.schema.boatTrip.description,
                            url: this.schema.boatTrip.url,
                            image: this.schema.boatTrip.image,
                            departureBoatTerminal: this.schema.boatTrip.departureBoatTerminal,
                            arrivalBoatTerminal: this.schema.boatTrip.arrivalBoatTerminal,
                            departureTime: this.schema.boatTrip.departureTime,
                            arrivalTime: this.schema.boatTrip.arrivalTime,
                            offers: this.schema.boatTrip.offers
                        });
                    }

                    // Add Departure BoatTerminal
                    if (this.schema.departureBoatTerminal) {
                        graph.push({
                            '@type': this.schema.departureBoatTerminal['@type'],
                            '@id': this.schema.departureBoatTerminal['@id'],
                            name: this.schema.departureBoatTerminal.name,
                            address: this.schema.departureBoatTerminal.address,
                            hasMap: this.schema.departureBoatTerminal.hasMap
                        });
                    }

                    // Add Arrival BoatTerminal
                    if (this.schema.arrivalBoatTerminal) {
                        graph.push({
                            '@type': this.schema.arrivalBoatTerminal['@type'],
                            '@id': this.schema.arrivalBoatTerminal['@id'],
                            name: this.schema.arrivalBoatTerminal.name,
                            address: this.schema.arrivalBoatTerminal.address,
                            hasMap: this.schema.arrivalBoatTerminal.hasMap
                        });
                    }

                    // Add Offer
                    if (this.schema.offer) {
                        graph.push({
                            '@type': this.schema.offer['@type'],
                            '@id': this.schema.offer['@id'],
                            priceCurrency: this.schema.offer.priceCurrency,
                            price: this.schema.offer.price,
                            availability: this.schema.offer.availability,
                            validFrom: this.schema.offer.validFrom,
                            url: this.schema.offer.url,
                            itemOffered: this.schema.offer.itemOffered,
                            seller: this.schema.offer.seller
                        });
                    }

                    // Add Service
                    if (this.schema.service) {
                        const serviceObj = {
                            '@type': this.schema.service['@type'],
                            '@id': this.schema.service['@id'],
                            name: this.schema.service.name,
                            description: this.schema.service.description,
                            audience: this.schema.service.audience,
                            provider: this.schema.service.provider,
                            aggregateRating: this.schema.service.aggregateRating
                        };

                        // Only include review if enabled
                        if (this.reviewsEnabled) {
                            serviceObj.review = this.schema.service.review;
                        }

                        graph.push(serviceObj);
                    }

                    // Add LocalBusiness
                    if (this.schema.localBusiness) {
                        graph.push({
                            '@type': this.schema.localBusiness['@type'],
                            '@id': this.schema.localBusiness['@id'],
                            name: this.schema.localBusiness.name,
                            url: this.schema.localBusiness.url,
                            logo: this.schema.localBusiness.logo,
                            paymentAccepted: this.schema.localBusiness.paymentAccepted,
                            sameAs: this.schema.localBusiness.sameAs
                        });
                    }

                    // Add WebPage
                    if (this.schema.webPage) {
                        graph.push({
                            '@type': this.schema.webPage['@type'],
                            '@id': this.schema.webPage['@id'],
                            url: this.schema.webPage.url,
                            name: this.schema.webPage.name,
                            isPartOf: this.schema.webPage.isPartOf,
                            mainEntity: this.schema.webPage.mainEntity
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
                },

                copyJsonToClipboard() {
                    const jsonText = this.jsonPreview();
                    navigator.clipboard.writeText(jsonText).then(() => {
                        $.toast({
                            heading: 'Success',
                            text: 'JSON copied to clipboard!',
                            icon: 'success',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }).catch(err => {
                        $.toast({
                            heading: 'Error',
                            text: 'Failed to copy JSON',
                            icon: 'error',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    });
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
