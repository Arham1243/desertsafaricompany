@props([
    'blockKey' => 'first_tour_block',
    'blockTitle' => 'First Tour Block',
    'tours' => [],
    'cities' => [],
    'countries' => [],
    'categories' => [],
    'jsonContent' => null,
    'tourVarName' => 'firstTourBlockT',
])

<div x-data="{
    enabled: {{ isset($jsonContent[$blockKey]['is_enabled']) && $jsonContent[$blockKey]['is_enabled'] == '1' ? 'true' : 'false' }},
    headingEnabled: {{ isset($jsonContent[$blockKey]['heading_enabled']) && $jsonContent[$blockKey]['heading_enabled'] == '1' ? 'true' : 'false' }},
    resource_type: '{{ isset($jsonContent[$blockKey]['resource_type']) ? $jsonContent[$blockKey]['resource_type'] : 'tour' }}'
}" class="form-box">
    <div class="form-box__header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="title">{{ $blockTitle }}</div>
            <div class="form-check form-switch" data-enabled-text="Enabled" data-disabled-text="Disabled">
                <input type="hidden" value="0" name="json_content[{{ $blockKey }}][is_enabled]">
                <input data-toggle-switch class="form-check-input" type="checkbox" id="{{ $blockKey }}"
                    value="1" name="json_content[{{ $blockKey }}][is_enabled]" x-model="enabled">
                <label class="form-check-label" for="{{ $blockKey }}">Enabled</label>
            </div>
        </div>
        <a href="{{ asset('admin/assets/images/tours-blocks/new-card.png') }}" data-fancybox="gallery"
            class="themeBtn p-2">
            <i class='bx bxs-show'></i>
        </a>
    </div>

    <div class="form-box__body" x-show="enabled" x-transition>
        <div class="form-fields mb-4">
            <div class="d-flex align-items-center gap-3">
                <label class="title mb-0">Heading:</label>
                <div class="form-check form-switch" data-enabled-text="Enabled" data-disabled-text="Disabled">
                    <input type="hidden" value="0" name="json_content[{{ $blockKey }}][heading_enabled]">
                    <input data-toggle-switch class="form-check-input" type="checkbox" id="{{ $blockKey }}_heading"
                        value="1" name="json_content[{{ $blockKey }}][heading_enabled]"
                        x-model="headingEnabled">
                    <label class="form-check-label" for="{{ $blockKey }}_heading">Enabled</label>
                </div>
            </div>
            <input x-show="headingEnabled" x-transition name="json_content[{{ $blockKey }}][heading]"
                type="text" class="field mt-3"
                value="{{ $jsonContent ? $jsonContent[$blockKey]['heading'] ?? '' : '' }}">
        </div>
        <div class="d-flex align-items-center gap-5 px-4 mb-3">
            <div class="form-check p-0">
                <input class="form-check-input" type="radio" name="json_content[{{ $blockKey }}][resource_type]"
                    id="{{ $blockKey }}-resource_type_tour" x-model="resource_type" value="tour" />
                <label class="form-check-label" for="{{ $blockKey }}-resource_type_tour">Tours</label>
            </div>
            <div class="form-check p-0">
                <input class="form-check-input" type="radio" name="json_content[{{ $blockKey }}][resource_type]"
                    id="{{ $blockKey }}-resource_type_city" x-model="resource_type" value="city" />
                <label class="form-check-label" for="{{ $blockKey }}-resource_type_city">Cities</label>
            </div>
            <div class="form-check p-0">
                <input class="form-check-input" type="radio" name="json_content[{{ $blockKey }}][resource_type]"
                    id="{{ $blockKey }}-resource_type_country" x-model="resource_type" value="country" />
                <label class="form-check-label" for="{{ $blockKey }}-resource_type_country">Countries</label>
            </div>
            <div class="form-check p-0">
                <input class="form-check-input" type="radio" name="json_content[{{ $blockKey }}][resource_type]"
                    id="{{ $blockKey }}-resource_type_category" x-model="resource_type" value="category" />
                <label class="form-check-label" for="{{ $blockKey }}-resource_type_category">Categories</label>
            </div>
        </div>

        <div x-show="resource_type == 'tour'" x-transition>
            <div class="form-fields mb-4">
                <label class="title">Sort By:</label>
                <select name="json_content[{{ $blockKey }}][sort_by]" class="field">
                    <option value="" selected disabled>Select</option>
                    <option value="asc"
                        {{ isset($jsonContent[$blockKey]['sort_by']) && $jsonContent[$blockKey]['sort_by'] === 'asc' ? 'selected' : '' }}>
                        Asc by title (A to Z)</option>
                    <option value="desc"
                        {{ isset($jsonContent[$blockKey]['sort_by']) && $jsonContent[$blockKey]['sort_by'] === 'desc' ? 'selected' : '' }}>
                        Desc by title (Z to A)</option>
                    <option value="random"
                        {{ isset($jsonContent[$blockKey]['sort_by']) && $jsonContent[$blockKey]['sort_by'] === 'random' ? 'selected' : '' }}>
                        Random</option>
                </select>
            </div>
            <div class="form-fields">
                <label class="title">Select Tours:</label>
                <select name="json_content[{{ $blockKey }}][tour_ids][]" multiple class="select2-select">
                    @foreach ($tours as $xtour)
                        <option value="{{ $xtour->id }}"
                            {{ in_array($xtour->id, $jsonContent[$blockKey]['tour_ids'] ?? []) ? 'selected' : '' }}>
                            {{ $xtour->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div x-show="resource_type == 'city'" x-transition>
            <div class="form-fields">
                <label class="title">Select Cities:</label>
                <select name="json_content[{{ $blockKey }}][city_ids][]" multiple class="select2-select">
                    @foreach ($cities as $ycity)
                        <option value="{{ $ycity->id }}"
                            {{ in_array($ycity->id, $jsonContent[$blockKey]['city_ids'] ?? []) ? 'selected' : '' }}>
                            {{ $ycity->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div x-show="resource_type == 'country'" x-transition>
            <div class="form-fields">
                <label class="title">Select Countries:</label>
                <select name="json_content[{{ $blockKey }}][country_ids][]" multiple class="select2-select">
                    @foreach ($countries as $zcountry)
                        <option value="{{ $zcountry->id }}"
                            {{ in_array($zcountry->id, $jsonContent[$blockKey]['country_ids'] ?? []) ? 'selected' : '' }}>
                            {{ $zcountry->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div x-show="resource_type == 'category'" x-transition>
            <div class="form-fields">
                <label class="title">Select Categories:</label>
                <select name="json_content[{{ $blockKey }}][category_ids][]" multiple class="select2-select">
                    @foreach ($categories as $lcategory)
                        <option value="{{ $lcategory->id }}"
                            {{ in_array($lcategory->id, $jsonContent[$blockKey]['category_ids'] ?? []) ? 'selected' : '' }}>
                            {{ $lcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
