@props([
    'blockKey' => 'first_tour_block',
    'blockTitle' => 'First Tour Block',
    'tours' => [],
    'jsonContent' => null,
    'tourVarName' => 'firstTourBlockT'
])

<div x-data="{
    enabled: {{ isset($jsonContent[$blockKey]['is_enabled']) && $jsonContent[$blockKey]['is_enabled'] == '1' ? 'true' : 'false' }},
    headingEnabled: {{ isset($jsonContent[$blockKey]['heading_enabled']) && $jsonContent[$blockKey]['heading_enabled'] == '1' ? 'true' : 'false' }}
}" class="form-box">
    <div class="form-box__header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="title">{{ $blockTitle }}</div>
            <div class="form-check form-switch" data-enabled-text="Enabled"
                data-disabled-text="Disabled">
                <input type="hidden" value="0"
                    name="json_content[{{ $blockKey }}][is_enabled]">
                <input data-toggle-switch class="form-check-input" type="checkbox"
                    id="{{ $blockKey }}" value="1"
                    name="json_content[{{ $blockKey }}][is_enabled]" x-model="enabled">
                <label class="form-check-label" for="{{ $blockKey }}">Enabled</label>
            </div>
        </div>
        <a href="{{ asset('admin/assets/images/tours-blocks/new-card.png') }}"
            data-fancybox="gallery" class="themeBtn p-2">
            <i class='bx bxs-show'></i>
        </a>
    </div>

    <div class="form-box__body" x-show="enabled" x-transition>
        <div class="form-fields mb-4">
            <div class="d-flex align-items-center gap-3">
                <label class="title mb-0">Heading:</label>
                <div class="form-check form-switch" data-enabled-text="Enabled"
                    data-disabled-text="Disabled">
                    <input type="hidden" value="0"
                        name="json_content[{{ $blockKey }}][heading_enabled]">
                    <input data-toggle-switch class="form-check-input" type="checkbox"
                        id="{{ $blockKey }}_heading" value="1"
                        name="json_content[{{ $blockKey }}][heading_enabled]" x-model="headingEnabled">
                    <label class="form-check-label" for="{{ $blockKey }}_heading">Enabled</label>
                </div>
            </div>
            <input x-show="headingEnabled" x-transition name="json_content[{{ $blockKey }}][heading]" type="text"
                class="field mt-3"
                value="{{ $jsonContent ? ($jsonContent[$blockKey]['heading'] ?? '') : '' }}">
        </div>
        <div class="form-fields">
            <label class="title">Select Tours:</label>
            <select name="json_content[{{ $blockKey }}][tour_ids][]" multiple
                class="select2-select">
                @foreach ($tours as $tour)
                    <option value="{{ $tour->id }}"
                        {{ in_array($tour->id, $jsonContent[$blockKey]['tour_ids'] ?? []) ? 'selected' : '' }}>
                        {{ $tour->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
