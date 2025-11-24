<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'))">
    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@type</label>
                                <input type="text" x-model="schema['@type']" name="schema[@type]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@id</label>
                                <input type="text" x-model="schema['@id']" name="schema[@id]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">headline</label>
                                <input type="text" x-model="schema.headline" name="schema[headline]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">alternativeHeadline</label>
                                <input type="text" x-model="schema.alternativeHeadline"
                                    name="schema[alternativeHeadline]" class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">description</label>
                                <textarea x-model="schema.description" name="schema[description]" class="field"
                                    rows="3"></textarea>
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
                                                        <div class="form-fields">
                                                            <input x-model="schema.image[index]"
                                                                :name="`schema[image][${index}]`" type="text"
                                                                class="field">
                                                        </div>
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
                                    <button type="button" class="themeBtn ms-auto" @click="addToArray('image')">
                                        Add <i class="bx bx-plus"></i>
                                    </button>
                                </div>
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
                                <label class="title">datePublished</label>
                                <input type="date" x-model="schema.datePublished" name="schema[datePublished]"
                                    class="field">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">dateModified</label>
                                <input type="date" x-model="schema.dateModified" name="schema[dateModified]"
                                    class="field">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12">
                            <div class="form-fields">
                                <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                    Author
                                </label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.author['@type']" name="schema[author][@type]"
                                    class="field mb-3">

                                <label class="title">name</label>
                                <input type="text" x-model="schema.author.name" name="schema[author][name]"
                                    class="field mb-3">

                                <label class="title">url</label>
                                <input type="text" x-model="schema.author.url" name="schema[author][url]"
                                    class="field mb-3">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12">
                            <div class="form-fields">
                                <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                    Publisher
                                </label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.publisher['@type']"
                                    name="schema[publisher][@type]" class="field mb-3">

                                <label class="title">name</label>
                                <input type="text" x-model="schema.publisher.name" name="schema[publisher][name]"
                                    class="field mb-3">

                                <label class="title">url</label>
                                <input type="text" x-model="schema.publisher.url" name="schema[publisher][url]"
                                    class="field mb-3">

                                <label class="title title--sm mb-2">Logo</label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.publisher.logo['@type']"
                                    name="schema[publisher][logo][@type]" class="field mb-3">

                                <label class="title">url</label>
                                <input type="text" x-model="schema.publisher.logo.url"
                                    name="schema[publisher][logo][url]" class="field mb-3">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12">
                            <div class="form-fields">
                                <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                    Audience
                                </label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.audience['@type']"
                                    name="schema[audience][@type]" class="field mb-3">

                                <label class="title">audienceType</label>
                                <select multiple x-model="schema.audience.audienceType"
                                    name="schema[audience][audienceType][]" class="select2-select"
                                    data-field="audience.audienceType">
                                    <option value="Families">Families</option>
                                    <option value="Solo Travelers">Solo Travelers</option>
                                    <option value="Local Residents">Local Residents</option>
                                    <option value="Adventure Seekers">Adventure Seekers</option>
                                    <option value="Couples">Couples</option>
                                    <option value="Corporate Groups">Corporate Groups</option>
                                    <option value="Tourists">Tourists</option>
                                    <option value="Budget Travel">Budget Travel</option>
                                    <option value="Groups of Friends">Groups of Friends</option>
                                    <option value="Honeymooners">Honeymooners</option>
                                    <option value="Less Budget">Less Budget</option>
                                    <option value="Cultural Tours">Cultural Tours</option>
                                    <option value="Cultural Enthusiasts">Cultural Enthusiasts</option>
                                    <option value="Seek Exclusive">Seek Exclusive</option>
                                    <option value="Private Tours">Private Tours</option>
                                    <option value="High-end Experiences">High-end Experiences</option>
                                    <option value="Local Traditions">Local Traditions</option>
                                    <option value="Interested in Museums">Interested in Museums</option>
                                    <option value="Business Travelers">Business Travelers</option>
                                    <option value="Nature Lovers">Nature Lovers</option>
                                    <option value="Food Lovers">Food Lovers</option>
                                    <option value="Photography Enthusiasts">Photography Enthusiasts</option>
                                    <option value="Travel Agents">Travel Agents</option>
                                    <option value="Travel Enthusiasts">Travel Enthusiasts</option>
                                </select>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12">
                            <div class="form-fields">
                                <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                    Offers
                                </label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.offers['@type']" name="schema[offers][@type]"
                                    class="field mb-3">

                                <label class="title">url</label>
                                <input type="text" x-model="schema.offers.url" name="schema[offers][url]"
                                    class="field mb-3">

                                <label class="title">priceCurrency</label>
                                <input type="text" x-model="schema.offers.priceCurrency"
                                    name="schema[offers][priceCurrency]" class="field mb-3">

                                <label class="title">price</label>
                                <input type="text" x-model="schema.offers.price" name="schema[offers][price]"
                                    class="field mb-3">

                                <label class="title">availability</label>
                                <input type="text" x-model="schema.offers.availability"
                                    name="schema[offers][availability]" class="field mb-3">

                                <label class="title">validFrom</label>
                                <input type="date" x-model="schema.offers.validFrom"
                                    name="schema[offers][validFrom]" class="field mb-3">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12 mb-3">
                            <div class="form-fields">
                                <label class="title">sameAs (Social Links)</label>
                                <div class="repeater-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Social URL</th>
                                                <th class="text-end" scope="col" style="width: 100px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="(item, index) in schema.sameAs" :key="index">
                                                <tr>
                                                    <td>
                                                        <div class="form-fields">
                                                            <input x-model="schema.sameAs[index]"
                                                                :name="`schema[sameAs][${index}]`" type="text"
                                                                class="field">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="delete-btn delete-btn--static"
                                                                @click="removeFromArray('sameAs', index)"
                                                                :disabled="schema.sameAs.length === 1">
                                                                <i class='bx bxs-trash-alt'></i>
                                                            </button>
                                                            <button type="button"
                                                                class="add-btn ms-auto add-btn--static"
                                                                @click="insertInArray('sameAs', index)">
                                                                <i class='bx bx-plus'></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <button type="button" class="themeBtn ms-auto" @click="addToArray('sameAs')">
                                        Add <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="col-12">
                            <div class="form-fields">
                                <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                    Main Entity Of Page
                                </label>

                                <label class="title">@type</label>
                                <input type="text" x-model="schema.mainEntityOfPage['@type']"
                                    name="schema[mainEntityOfPage][@type]" class="field mb-3">

                                <label class="title">@id</label>
                                <input type="text" x-model="schema.mainEntityOfPage['@id']"
                                    name="schema[mainEntityOfPage][@id]" class="field mb-3">
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
                '@type': 'NewsArticle',
                '@id': '',
                headline: '',
                alternativeHeadline: '',
                description: '',
                image: [''],
                url: '',
                datePublished: '',
                dateModified: '',
                author: {
                    '@type': 'Person',
                    name: '',
                    url: ''
                },
                publisher: {
                    '@type': 'Organization',
                    name: '',
                    url: '',
                    logo: {
                        '@type': 'ImageObject',
                        url: ''
                    }
                },
                audience: {
                    '@type': 'Audience',
                    audienceType: []
                },
                offers: {
                    '@type': 'Offer',
                    url: '',
                    priceCurrency: 'AED',
                    price: '',
                    availability: 'https://schema.org/InStock',
                    validFrom: ''
                },
                sameAs: [''],
                mainEntityOfPage: {
                    '@type': 'WebPage',
                    '@id': ''
                }
            };

            return {
                schema: {
                    ...defaults
                },

                init(initialSchema = {}) {
                    // Merge defaults with backend schema safely
                    this.schema = {
                        ...defaults,
                        ...initialSchema,
                        author: {
                            ...defaults.author,
                            ...(initialSchema.author || {})
                        },
                        publisher: {
                            ...defaults.publisher,
                            ...(initialSchema.publisher || {}),
                            logo: {
                                ...defaults.publisher.logo,
                                ...((initialSchema.publisher && initialSchema.publisher.logo) || {})
                            }
                        },
                        audience: {
                            ...defaults.audience,
                            ...(initialSchema.audience || {}),
                            audienceType: (initialSchema.audience && initialSchema.audience.audienceType) || defaults
                                .audience.audienceType
                        },
                        offers: {
                            ...defaults.offers,
                            ...(initialSchema.offers || {})
                        },
                        mainEntityOfPage: {
                            ...defaults.mainEntityOfPage,
                            ...(initialSchema.mainEntityOfPage || {})
                        }
                    };

                    // Ensure array fields are always arrays
                    if (!Array.isArray(this.schema.image)) {
                        this.schema.image = this.schema.image ? [this.schema.image] : [''];
                    }
                    if (this.schema.image.length === 0) this.schema.image = [''];

                    if (!Array.isArray(this.schema.sameAs)) {
                        this.schema.sameAs = this.schema.sameAs ? [this.schema.sameAs] : [''];
                    }
                    if (this.schema.sameAs.length === 0) this.schema.sameAs = [''];

                    if (!Array.isArray(this.schema.audience.audienceType)) {
                        this.schema.audience.audienceType = this.schema.audience.audienceType ? [this.schema.audience
                            .audienceType
                        ] : [];
                    }

                    // Initialize Select2 selects
                    this.$el.querySelectorAll('.select2-select').forEach((el) => {
                        const select = $(el);
                        select.select2();

                        // Handle nested fields (e.g., audience.audienceType)
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

                // Generic array helper methods
                addToArray(fieldName, defaultValue = '') {
                    if (!Array.isArray(this.schema[fieldName])) this.schema[fieldName] = [];
                    this.schema[fieldName].push(defaultValue);
                },
                insertInArray(fieldName, index, defaultValue = '') {
                    if (!Array.isArray(this.schema[fieldName])) this.schema[fieldName] = [];
                    this.schema[fieldName].splice(index + 1, 0, defaultValue);
                },
                removeFromArray(fieldName, index) {
                    if (Array.isArray(this.schema[fieldName]) && this.schema[fieldName].length > 1) {
                        this.schema[fieldName].splice(index, 1);
                    }
                },

                jsonPreview() {
                    return JSON.stringify(this.schema, null, 2);
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
