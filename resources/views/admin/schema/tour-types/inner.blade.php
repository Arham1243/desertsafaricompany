<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'), '{{ $record->title ?? '' }}', '{{ $record->detail_url ?? '' }}', {{ json_encode($record->faqs ?? []) }})">
    <!-- Hidden field to store final @graph JSON -->
    <input type="hidden" name="schema_graph" :value="jsonPreview()">
    <input type="hidden" name="type" value="inner-page">

    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body form-fields">
                    <div class="row">
                        <div class="col-12 mb-3 title title--sm">@type Tourist Trip</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@type</label>
                                <select x-model="schema.touristTrip['@type']" name="schema[touristTrip][@type][]"
                                    multiple class="select2-select" data-field="touristTrip.@type">
                                    <option value="TouristTrip">TouristTrip</option>
                                    <option value="Trip">Trip</option>
                                    <option value="TouristDestination">TouristDestination</option>
                                    <option value="TouristAttraction">TouristAttraction</option>
                                    <option value="Place">Place</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">name</label>
                                <input type="text" x-model="schema.touristTrip.name" name="schema[touristTrip][name]"
                                    class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.touristTrip.description" name="schema[touristTrip][description]" class="field"
                                    rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">url</label>
                                <input type="text" x-model="schema.touristTrip.url" name="schema[touristTrip][url]"
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
                                            <template x-for="(item, index) in schema.touristTrip.image"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input x-model="schema.touristTrip.image[index]"
                                                            :name="`schema[touristTrip][image][${index}]`"
                                                            type="text" class="field">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('touristTrip.image', index)"
                                                                :disabled="schema.touristTrip.image.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button"
                                                                class="add-btn ms-auto add-btn--static"
                                                                @click="insertInNestedArray('touristTrip.image', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn ms-auto"
                                        @click="addToNestedArray('touristTrip.image')">Add <i
                                            class="bx bx-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">touristType</label>
                                <select multiple x-model="schema.touristTrip.touristType"
                                    name="schema[touristTrip][touristType][]" class="select2-select"
                                    data-field="touristTrip.touristType">
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
                                <label class="title">hasMap</label>
                                <input type="text" x-model="schema.touristTrip.hasMap"
                                    name="schema[touristTrip][hasMap]" class="field">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Offer</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">priceCurrency</label>
                                <select multiple x-model="schema.touristTrip.offers.priceCurrency"
                                    name="schema[touristTrip][offers][priceCurrency][]" class="select2-select"
                                    data-field="touristTrip.offers.priceCurrency">
                                    @foreach ($currencies as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">price</label>
                                <input type="text" x-model="schema.touristTrip.offers.price"
                                    name="schema[touristTrip][offers][price]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">availability</label>
                                <select x-model="schema.touristTrip.offers.availability"
                                    name="schema[touristTrip][offers][availability]" class="field">
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
                                <label class="title">url</label>
                                <input type="text" x-model="schema.touristTrip.offers.url"
                                    name="schema[touristTrip][offers][url]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">validFrom</label>
                                <input type="date" x-model="schema.touristTrip.offers.validFrom"
                                    name="schema[touristTrip][offers][validFrom]" class="field">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title title--sm mb-2">Item Offered</label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.touristTrip.offers.itemOffered['@type']"
                                    name="schema[touristTrip][offers][itemOffered][@type]" class="field mb-3">

                                <label class="title">name</label>
                                <input type="text" x-model="schema.touristTrip.offers.itemOffered.name"
                                    name="schema[touristTrip][offers][itemOffered][name]" class="field mb-3">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Itinerary</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">Itinerary Items</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px;">Position</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template
                                                x-for="(item, index) in schema.touristTrip.itinerary.itemListElement"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <input type="number"
                                                            x-model="schema.touristTrip.itinerary.itemListElement[index].position"
                                                            :name="`schema[touristTrip][itinerary][itemListElement][${index}][position]`"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            x-model="schema.touristTrip.itinerary.itemListElement[index].item.name"
                                                            :name="`schema[touristTrip][itinerary][itemListElement][${index}][item][name]`"
                                                            class="field">
                                                    </td>
                                                    <td>
                                                        <textarea x-model="schema.touristTrip.itinerary.itemListElement[index].item.description"
                                                            :name="`schema[touristTrip][itinerary][itemListElement][${index}][item][description]`" class="field"
                                                            rows="2"></textarea>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('touristTrip.itinerary.itemListElement', index)"
                                                                :disabled="schema.touristTrip.itinerary.itemListElement.length ===
                                                                    1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('touristTrip.itinerary.itemListElement', index, {'@type': 'ListItem', position: index + 2, item: {'@type': 'TouristTrip', name: '', description: ''}})">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('touristTrip.itinerary.itemListElement', {'@type': 'ListItem', position: schema.touristTrip.itinerary.itemListElement.length + 1, item: {'@type': 'TouristTrip', name: '', description: ''}})">
                                        Add Itinerary Item</button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Part Of Trip</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@type</label>
                                <input type="text" x-model="schema.touristTrip.partOfTrip['@type']"
                                    name="schema[touristTrip][partOfTrip][@type]" class="field mb-3">

                                <label class="title">name</label>
                                <input type="text" x-model="schema.touristTrip.partOfTrip.name"
                                    name="schema[touristTrip][partOfTrip][name]" class="field mb-3">

                                <label class="title">description</label>
                                <textarea x-model="schema.touristTrip.partOfTrip.description" name="schema[touristTrip][partOfTrip][description]"
                                    class="field" rows="2"></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">Aggregate Rating</div>

                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@type</label>
                                <input type="text" x-model="schema.touristTrip.aggregateRating['@type']"
                                    name="schema[touristTrip][aggregateRating][@type]" class="field mb-3">

                                <label class="title">ratingValue</label>
                                <input type="number" step="0.1"
                                    x-model="schema.touristTrip.aggregateRating.ratingValue"
                                    name="schema[touristTrip][aggregateRating][ratingValue]" class="field mb-3">

                                <label class="title">reviewCount</label>
                                <input type="number" x-model="schema.touristTrip.aggregateRating.reviewCount"
                                    name="schema[touristTrip][aggregateRating][reviewCount]" class="field mb-3">
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="title title--sm mb-0">Reviews</label>
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
                                            <template x-for="(review, index) in schema.touristTrip.review"
                                                :key="index">
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column gap-3">
                                                            <label class="title">Author Name</label>
                                                            <input type="text"
                                                                x-model="schema.touristTrip.review[index].author.name"
                                                                :name="`schema[touristTrip][review][${index}][author][name]`"
                                                                class="field">

                                                            <label class="title">Date Published</label>
                                                            <input type="date"
                                                                x-model="schema.touristTrip.review[index].datePublished"
                                                                :name="`schema[touristTrip][review][${index}][datePublished]`"
                                                                class="field">

                                                            <label class="title">Review Body</label>
                                                            <textarea x-model="schema.touristTrip.review[index].reviewBody"
                                                                :name="`schema[touristTrip][review][${index}][reviewBody]`" class="field" rows="2"></textarea>

                                                            <label class="title">Rating Value</label>
                                                            <input type="number" step="0.1"
                                                                x-model="schema.touristTrip.review[index].reviewRating.ratingValue"
                                                                :name="`schema[touristTrip][review][${index}][reviewRating][ratingValue]`"
                                                                class="field">

                                                            <label class="title">Best Rating</label>
                                                            <input type="number"
                                                                x-model="schema.touristTrip.review[index].reviewRating.bestRating"
                                                                :name="`schema[touristTrip][review][${index}][reviewRating][bestRating]`"
                                                                class="field">

                                                            <label class="title">Worst Rating</label>
                                                            <input type="number"
                                                                x-model="schema.touristTrip.review[index].reviewRating.worstRating"
                                                                :name="`schema[touristTrip][review][${index}][reviewRating][worstRating]`"
                                                                class="field">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column gap-2">
                                                            <button type="button"
                                                                class="delete-btn delete-btn--static"
                                                                @click="removeFromNestedArray('touristTrip.review', index)"
                                                                :disabled="schema.touristTrip.review.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button" class="add-btn add-btn--static"
                                                                @click="insertInNestedArray('touristTrip.review', index, {'@type': 'Review', author: {'@type': 'Person', name: ''}, datePublished: '', reviewBody: '', reviewRating: {'@type': 'Rating', ratingValue: '', bestRating: '5', worstRating: '1'}})">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn"
                                        @click="addToNestedArray('touristTrip.review', {'@type': 'Review', author: {'@type': 'Person', name: ''}, datePublished: '', reviewBody: '', reviewRating: {'@type': 'Rating', ratingValue: '', bestRating: '5', worstRating: '1'}})">
                                        Add Review</button>
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
                                <label class="title mb-0">FAQ Section</label>
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
                                    <label class="title">FAQ Items</label>
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
                                            @click="addToNestedArray('faq.mainEntity', {'@type': 'Question', name: '', acceptedAnswer: {'@type': 'Answer', text: ''}})">
                                            Add FAQ</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-12 mb-3 title title--sm">@type Breadcrumb List</div>

                        <div class="col-12 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="title mb-0">Breadcrumb Navigation</label>
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
                                    <label class="title">Breadcrumb Items</label>
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
                                                        <td>
                                                            <input type="number"
                                                                x-model="schema.breadcrumb.itemListElement[index].position"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][position]`"
                                                                class="field">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                x-model="schema.breadcrumb.itemListElement[index].name"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][name]`"
                                                                class="field">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                x-model="schema.breadcrumb.itemListElement[index].item"
                                                                :name="`schema[breadcrumb][itemListElement][${index}][item]`"
                                                                class="field">
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <button type="button"
                                                                    class="delete-btn delete-btn--static"
                                                                    @click="removeFromNestedArray('breadcrumb.itemListElement', index)"
                                                                    :disabled="schema.breadcrumb.itemListElement.length === 1">
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                                <button type="button" class="add-btn add-btn--static"
                                                                    @click="insertInNestedArray('breadcrumb.itemListElement', index, {'@type': 'ListItem', position: index + 2, name: '', item: ''})">
                                                                    <i class='bx bx-plus'></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                        <button type="button" class="themeBtn"
                                            @click="addToNestedArray('breadcrumb.itemListElement', {'@type': 'ListItem', position: schema.breadcrumb.itemListElement.length + 1, name: '', item: ''})">
                                            Add Breadcrumb</button>
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
        function schemaManager() {
            const defaults = {
                '@context': 'https://schema.org',
                touristTrip: {
                    '@type': ['TouristTrip'],
                    name: '',
                    description: '',
                    url: '',
                    image: [''],
                    touristType: [],
                    hasMap: '',
                    offers: {
                        '@type': 'Offer',
                        priceCurrency: [],
                        price: '',
                        availability: 'https://schema.org/InStock',
                        url: '',
                        validFrom: '',
                        itemOffered: {
                            '@type': 'TouristTrip',
                            name: ''
                        }
                    },
                    itinerary: {
                        '@type': 'ItemList',
                        itemListElement: [{
                            '@type': 'ListItem',
                            position: 1,
                            item: {
                                '@type': 'TouristTrip',
                                name: '',
                                description: ''
                            }
                        }]
                    },
                    partOfTrip: {
                        '@type': 'Trip',
                        name: '',
                        description: ''
                    },
                    aggregateRating: {
                        '@type': 'AggregateRating',
                        ratingValue: '',
                        reviewCount: ''
                    },
                    review: [{
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
                            bestRating: '5',
                            worstRating: '1'
                        }
                    }]
                },
                localBusiness: {
                    "@type": "LocalBusiness",
                    '@id': '',
                    name: '',
                    url: '',
                    logo: '',
                    paymentAccepted: [''],
                    sameAs: ['']
                },
                faq: {
                    '@type': 'FAQPage',
                    '@id': '',
                    mainEntity: []
                },
                breadcrumb: {
                    '@type': 'BreadcrumbList',
                    itemListElement: []
                }
            };

            return {
                schema: {
                    ...defaults
                },
                faqEnabled: false,
                breadcrumbEnabled: false,
                reviewsEnabled: false,

                init(initialSchema = {}, tourTitle = '', tourUrl = '', tourFaqs = []) {
                    // Check if initialSchema has @graph format
                    if (initialSchema['@graph']) {
                        const graph = initialSchema['@graph'];
                        this.schema = {
                            '@context': initialSchema['@context'] || defaults['@context']
                        };

                        // Extract each object type from @graph
                        graph.forEach(item => {
                            if (item['@type'] && (Array.isArray(item['@type']) ? item['@type'].includes(
                                    'TouristTrip') : item['@type'] === 'TouristTrip')) {
                                this.schema.touristTrip = {
                                    ...defaults.touristTrip,
                                    ...item,
                                    offers: {
                                        ...defaults.touristTrip.offers,
                                        ...(item.offers || {}),
                                        itemOffered: {
                                            ...defaults.touristTrip.offers.itemOffered,
                                            ...((item.offers && item.offers.itemOffered) || {})
                                        }
                                    },
                                    itinerary: {
                                        ...defaults.touristTrip.itinerary,
                                        ...(item.itinerary || {}),
                                        itemListElement: (item.itinerary && item.itinerary.itemListElement) ||
                                            defaults.touristTrip.itinerary.itemListElement
                                    },
                                    partOfTrip: {
                                        ...defaults.touristTrip.partOfTrip,
                                        ...(item.partOfTrip || {})
                                    },
                                    aggregateRating: {
                                        ...defaults.touristTrip.aggregateRating,
                                        ...(item.aggregateRating || {})
                                    },
                                    review: item.review || defaults.touristTrip.review
                                };
                            } else if (item['@type'] === 'LocalBusiness') {
                                this.schema.localBusiness = {
                                    ...defaults.localBusiness,
                                    ...item,
                                    sameAs: item.sameAs || defaults.localBusiness.sameAs
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
                        if (!this.schema.touristTrip) this.schema.touristTrip = defaults.touristTrip;
                        if (!this.schema.localBusiness) this.schema.localBusiness = defaults.localBusiness;
                        if (!this.schema.faq) this.schema.faq = defaults.faq;
                        if (!this.schema.breadcrumb) this.schema.breadcrumb = defaults.breadcrumb;
                    } else {
                        // Load from flat format (backward compatibility)
                        this.schema = {
                            ...defaults,
                            ...initialSchema,
                            touristTrip: {
                                ...defaults.touristTrip,
                                ...(initialSchema.touristTrip || {}),
                                offers: {
                                    ...defaults.touristTrip.offers,
                                    ...((initialSchema.touristTrip && initialSchema.touristTrip.offers) || {}),
                                    itemOffered: {
                                        ...defaults.touristTrip.offers.itemOffered,
                                        ...((initialSchema.touristTrip && initialSchema.touristTrip.offers &&
                                            initialSchema.touristTrip.offers.itemOffered) || {})
                                    }
                                },
                                itinerary: {
                                    ...defaults.touristTrip.itinerary,
                                    ...((initialSchema.touristTrip && initialSchema.touristTrip.itinerary) || {}),
                                    itemListElement: (initialSchema.touristTrip && initialSchema.touristTrip
                                            .itinerary && initialSchema.touristTrip.itinerary.itemListElement) ||
                                        defaults.touristTrip.itinerary.itemListElement
                                },
                                partOfTrip: {
                                    ...defaults.touristTrip.partOfTrip,
                                    ...((initialSchema.touristTrip && initialSchema.touristTrip.partOfTrip) || {})
                                },
                                aggregateRating: {
                                    ...defaults.touristTrip.aggregateRating,
                                    ...((initialSchema.touristTrip && initialSchema.touristTrip.aggregateRating) || {})
                                },
                                review: (initialSchema.touristTrip && initialSchema.touristTrip.review) || defaults
                                    .touristTrip.review
                            },
                            // LocalBusiness is always loaded from global settings (passed from controller)
                            localBusiness: initialSchema.localBusiness || defaults.localBusiness,
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

                    // Populate name and url from tour if schema is empty
                    if (!this.schema.touristTrip.name && tourTitle) {
                        this.schema.touristTrip.name = tourTitle;
                    }
                    if (!this.schema.touristTrip.url && tourUrl) {
                        this.schema.touristTrip.url = tourUrl;
                    }

                    // Check if FAQ exists and enable the switch
                    if (this.schema.faq.mainEntity && this.schema.faq.mainEntity.length > 0) {
                        const hasContent = this.schema.faq.mainEntity.some(item =>
                            item.name || item.acceptedAnswer?.text
                        );
                        if (hasContent) {
                            this.faqEnabled = true;
                        }
                    } else if (tourFaqs && tourFaqs.length > 0) {
                        // Populate FAQs from tour if schema FAQs are empty
                        this.schema.faq.mainEntity = tourFaqs.map(faq => ({
                            '@type': 'Question',
                            name: faq.question || '',
                            acceptedAnswer: {
                                '@type': 'Answer',
                                text: faq.answer || ''
                            }
                        }));
                        this.faqEnabled = true;
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

                    // Always override localBusiness with global settings data
                    if (initialSchema.localBusiness) {
                        this.schema.localBusiness = initialSchema.localBusiness;
                    }

                    // Ensure arrays FIRST before checking switches
                    if (!Array.isArray(this.schema.touristTrip.image)) {
                        this.schema.touristTrip.image = this.schema.touristTrip.image ? [this.schema.touristTrip.image] : [
                            ''
                        ];
                    }
                    if (this.schema.touristTrip.image.length === 0) this.schema.touristTrip.image = [''];

                    if (!Array.isArray(this.schema.touristTrip.touristType)) {
                        this.schema.touristTrip.touristType = this.schema.touristTrip.touristType ? [this.schema.touristTrip
                            .touristType
                        ] : [];
                    }

                    if (!Array.isArray(this.schema.touristTrip.itinerary.itemListElement)) {
                        this.schema.touristTrip.itinerary.itemListElement = [];
                    }
                    if (this.schema.touristTrip.itinerary.itemListElement.length === 0) {
                        this.schema.touristTrip.itinerary.itemListElement = [defaults.touristTrip.itinerary.itemListElement[
                            0]];
                    }

                    if (!Array.isArray(this.schema.touristTrip.review)) {
                        this.schema.touristTrip.review = [];
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
                    // Check if Reviews exist and enable the switch
                    if (this.schema.touristTrip.review && this.schema.touristTrip.review.length > 0) {
                        const hasContent = this.schema.touristTrip.review.some(item =>
                            item.author?.name || item.reviewBody || item.reviewRating?.ratingValue
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
                    if (this.reviewsEnabled && this.schema.touristTrip.review.length === 0) {
                        this.schema.touristTrip.review = [defaults.touristTrip.review[0]];
                    }
                    if (this.faqEnabled && this.schema.faq.mainEntity.length === 0) {
                        this.schema.faq.mainEntity = [defaults.faq.mainEntity[0]];
                    }
                    if (this.breadcrumbEnabled && this.schema.breadcrumb.itemListElement.length === 0) {
                        this.schema.breadcrumb.itemListElement = [defaults.breadcrumb.itemListElement[0]];
                    }

                    // Initialize Select2 selects
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

                // Toggle reviews on/off
                toggleReviews() {
                    if (this.reviewsEnabled) {
                        // Enabled - ensure at least one review exists
                        if (!this.schema.touristTrip.review || this.schema.touristTrip.review.length === 0) {
                            this.schema.touristTrip.review = [{
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
                                    bestRating: '5',
                                    worstRating: '1'
                                }
                            }];
                        }
                    }
                    // When disabled, we keep the reviews but hide them
                    // They will be excluded from JSON output if reviewsEnabled is false
                },

                jsonPreview() {
                    // Build @graph array from all schema objects
                    const graph = [];

                    // Add TouristTrip (with all nested properties)
                    if (this.schema.touristTrip) {
                        const touristTripObj = {
                            '@type': this.schema.touristTrip['@type'],
                            name: this.schema.touristTrip.name,
                            description: this.schema.touristTrip.description,
                            url: this.schema.touristTrip.url,
                            image: this.schema.touristTrip.image,
                            touristType: this.schema.touristTrip.touristType,
                            hasMap: this.schema.touristTrip.hasMap,
                            offers: this.schema.touristTrip.offers,
                            itinerary: this.schema.touristTrip.itinerary,
                            partOfTrip: this.schema.touristTrip.partOfTrip,
                            aggregateRating: this.schema.touristTrip.aggregateRating
                        };

                        // Only include review if enabled
                        if (this.reviewsEnabled) {
                            touristTripObj.review = this.schema.touristTrip.review;
                        }

                        graph.push(touristTripObj);
                    }

                    // Add LocalBusiness
                    if (this.schema.localBusiness) {
                        graph.push({
                            '@type': this.schema.localBusiness['@type'],
                            '@id': this.schema.localBusiness['@id'],
                            name: this.schema.localBusiness.name,
                            url: this.schema.localBusiness.url,
                            logo: this.schema.localBusiness.logo,
                            sameAs: this.schema.localBusiness.sameAs,
                            paymentAccepted: this.schema.localBusiness.paymentAccepted
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
