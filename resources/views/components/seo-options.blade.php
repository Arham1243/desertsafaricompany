@php
    $favicon = App\Models\Setting::where('key', 'favicon')->first()->value ?? null;
@endphp
<div class="seo-options">
    <div class="form-box">
        <div class="form-box__header d-flex align-items-center justify-content-between">
            <div class="title">Search Engine</div>
            <a href="javascript:void(0)" onclick="document.getElementById('seo_manager').classList.remove('d-none')"
                class="themeBtn p-2"><i class='bx bxs-edit-alt'></i></a>
        </div>
        <div class="form-box__body py-4">
            <div class="google-preview">
                <div class="google-preview__header">
                    <div class="logo">
                        <img src="{{ $favicon ? asset($favicon) : asset('favicon.ico') }}" alt="Favicon"
                            class="imgFluid">
                    </div>
                    <div class="content">
                        <div class="title">{{ env('APP_NAME') }}</div>
                        <div class="link">{{ buildUrl(url('/'), $resource, $slug) }}</div>
                    </div>
                </div>
                <div class="google-preview__content">
                    <div class="heading" id="google_title">{{ $seo->seo_title ?? '' }}</div>
                    <div class="description" id="google_desc">
                        {{ $seo->seo_description ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-box d-none" id="seo_manager">
        <div class="form-box__header">
            <div class="title">Seo Manager</div>
        </div>
        <div class="form-box__body">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-fields" x-data="{ seoIndex: '{{ old('is_seo_index', $seo->is_seo_index ?? '') }}' }">
                        <label class="title">Allow search engines to show this service in search results?</label>
                        <select name="seo[is_seo_index]" class="field" x-model="seoIndex">
                            <option value="" disabled selected>Select</option>
                            <option value="1" :selected="seoIndex == '1'">Yes</option>
                            <option value="0" :selected="seoIndex == '0'">No</option>
                        </select>

                        <template x-if="seoIndex == '0'">
                            <small class="mt-2" style="color: red">
                                Bots will be blocked from indexing this page (meta: noindex, nofollow).
                            </small>
                        </template>

                        @error('seo[is_seo_index]')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 mt-4" id="seo_options">
                    <div class="tabs-wrapper">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">General
                                    Options</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Share
                                    Facebook</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#contact" type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Share Twitter</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="schema-tab" data-bs-toggle="tab"
                                    data-bs-target="#schema" type="button" role="tab" aria-controls="schema"
                                    aria-selected="false">Schema</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-dark" id="canonical-tab" data-bs-toggle="tab"
                                    data-bs-target="#canonical" type="button" role="tab" aria-controls="canonical"
                                    aria-selected="false">Canonical</button>
                            </li>
                        </ul>
                        <div class="tab-content-wrapper">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Seo Title:
                                            </label>
                                            <input type="text" name="seo[seo_title]" class="field"
                                                value="{{ old('seo[seo_title]', $seo->seo_title ?? '') }}"
                                                placeholder="Title" oninput="updateText(this,'google_title')">
                                            @error('seo[seo_title]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Seo Description:
                                            </label>
                                            <textarea name="seo[seo_description]" class="field" rows="3" placeholder="Enter Description..."
                                                oninput="updateText(this,'google_desc')">{{ old('seo[seo_description]', $seo->seo_description ?? '') }}</textarea>
                                            @error('seo[seo_description]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-fields col-md-4">
                                            <label class="title">
                                                Seo Feature Image:
                                            </label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($seo->seo_featured_image) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="seo[seo_featured_image]"
                                                            id="seo_featured_image" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder">
                                                            <i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="seo_featured_image"
                                                            class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>


                                                    <div class="upload-box__img {{ !empty($seo->seo_featured_image) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn>
                                                            <i class='bx bxs-trash-alt'></i>
                                                        </button>
                                                        <a href="{{ asset($seo ? $seo->seo_featured_image : 'admin/assets/images/loading.webp') }}"
                                                            class="mask" data-fancybox="gallery">
                                                            <img src="{{ asset($seo ? $seo->seo_featured_image : 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid"
                                                                data-upload-preview>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="text-danger d-none mt-2 text-center" data-error-message>
                                                    Please upload a valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 1200 &times; 630
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="tab-pane fade" id="profile" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <div class="row">
                                        <!-- Facebook Title -->
                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Facebook Title:
                                            </label>
                                            <input type="text" name="seo[fb_title]" class="field"
                                                value="{{ old('seo[fb_title]', $seo->fb_title ?? '') }}"
                                                placeholder="Title">
                                            @error('seo[fb_title]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>


                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Facebook Description:
                                            </label>
                                            <textarea name="seo[fb_description]" class="field" rows="3" placeholder="Enter Description...">{{ old('seo[fb_description]', $seo->fb_description ?? '') }}</textarea>
                                            @error('seo[fb_description]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-fields col-md-4">
                                            <label class="title">
                                                Facebook Feature Image:
                                            </label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($seo->fb_featured_image) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="seo[fb_featured_image]"
                                                            id="fb_featured_image" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder">
                                                            <i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="fb_featured_image"
                                                            class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($seo->fb_featured_image) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn>
                                                            <i class='bx bxs-trash-alt'></i>
                                                        </button>
                                                        <a href="{{ asset($seo ? $seo->fb_featured_image : 'admin/assets/images/loading.webp') }}"
                                                            class="mask" data-fancybox="gallery">
                                                            <img src="{{ asset($seo ? $seo->fb_featured_image : 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid"
                                                                data-upload-preview>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="text-danger d-none mt-2 text-center" data-error-message>
                                                    Please upload a valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 1200 &times; 630
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel"
                                    aria-labelledby="contact-tab">
                                    <div class="row">
                                        <!-- Twitter Title -->
                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Twitter Title:
                                            </label>
                                            <input type="text" name="seo[tw_title]" class="field"
                                                value="{{ old('seo[tw_title]', $seo->tw_title ?? '') }}"
                                                placeholder="Title">
                                            @error('seo[tw_title]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Twitter Description -->
                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Twitter Description:
                                            </label>
                                            <textarea name="seo[tw_description]" class="field" rows="3" placeholder="Enter Description...">{{ old('seo[tw_description]', $seo->tw_description ?? '') }}</textarea>
                                            @error('seo[tw_description]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Twitter Featured Image -->
                                        <div class="form-fields col-md-4">
                                            <label class="title">
                                                Twitter Feature Image:
                                            </label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($seo->tw_featured_image) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="seo[tw_featured_image]"
                                                            id="tw_featured_image" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder">
                                                            <i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="tw_featured_image"
                                                            class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>


                                                    <div class="upload-box__img {{ !empty($seo->tw_featured_image) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn>
                                                            <i class='bx bxs-trash-alt'></i>
                                                        </button>
                                                        <a href="{{ asset($seo ? $seo->tw_featured_image : 'admin/assets/images/loading.webp') }}"
                                                            class="mask" data-fancybox="gallery">
                                                            <img src="{{ asset($seo ? $seo->tw_featured_image : 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid"
                                                                data-upload-preview>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="text-danger d-none mt-2 text-center" data-error-message>
                                                    Please upload a valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 1200 &times; 630
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="schema" role="tabpanel"
                                    aria-labelledby="schema-tab">
                                    @if ($entity && $id)
                                        @if ($entity === 'tours')
                                            @php
                                                $tour = \App\Models\Tour::find($id);
                                                $schema_type = $tour->schema_type;
                                            @endphp
                                            <div x-data="{ schema_type: '{{ $schema_type ?? 'inner-page' }}' }">
                                                <div class="form-fields mb-3">
                                                    <label class="title mb-2">Schema Type:</label>
                                                    <select x-model="schema_type" name="tour[general][schema_type]"
                                                        class="field">
                                                        <option value="inner-page">TouristTrip (Inner Page)</option>
                                                        <option value="water-activity">SportsActivityLocation (Water
                                                            Activities)</option>
                                                        <option value="boat-trip">BoatTrip (Water Activities)</option>
                                                        <option value="bus-trip">BusTrip (Bus Tour)</option>
                                                    </select>
                                                </div>

                                                <a :href="`{{ route('admin.schema.index', ['entity' => $entity, 'id' => $id]) }}?type=${schema_type}`"
                                                    target="_blank" class="themeBtn ml-2 mb-2">Edit Schema</a>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.schema.index', ['entity' => $entity, 'id' => $id]) }}"
                                                target="_blank" class="themeBtn ml-2 mb-2">Edit Schema</a>
                                        @endif
                                    @endif
                                    <!-- Schema -->
                                    <div class="form-fields mt-3">
                                        <label class="title">
                                            Schema:
                                        </label>
                                        <textarea readonly name="seo[schema]" class="field" rows="15">{{ old('seo[schema]', $seo->schema ?? '') }}</textarea>
                                        @error('seo[schema]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="canonical" role="tabpanel"
                                    aria-labelledby="canonical-tab">
                                    <div class="row">
                                        <!-- Canonical -->
                                        <div class="form-fields col-md-12">
                                            <label class="title">
                                                Canonical:
                                            </label>
                                            <input type="text" name="seo[canonical]" class="field"
                                                value="{{ old('seo[canonical]', $seo->canonical ?? '') }}"
                                                placeholder="Enter URL">
                                            @error('seo[canonical]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        function updateText(currentInput, ElementId) {
            let textPreview = document.getElementById(ElementId)
            textPreview.textContent = currentInput.value
        }
    </script>
@endsection
