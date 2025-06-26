@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.edit', $tour) }}
            <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @method('PATCH')

                @csrf
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">

                            <h3 class="heading">Edit Tour: {{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildUrl(url('/'), 'tours/') }}</div>
                                    <input value="{{ $tour->slug ?? '#' }}" type="button" class="link permalink-input"
                                        data-field-id="slug">
                                    <input type="hidden" id="slug" value="{{ $tour->slug ?? '#' }}"
                                        name="tour[general][slug]">
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('tours.details', $tour->slug) }}" target="_blank" class="themeBtn">View
                            Tour</a>
                    </div>
                </div>
                <div class="row" x-data="{ optionTab: 'general' }">
                    <div class="col-md-3">
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Tour Information
                                </div>
                            </div>
                            <div class="form-box__body p-0">
                                <ul class="settings">
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'general' }" @click="optionTab = 'general'">
                                            <i class="bx bx-cog"></i> General
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'pricing' }" @click="optionTab = 'pricing'">
                                            <i class="bx bx-dollar"></i> Pricing
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'location' }" @click="optionTab = 'location'">
                                            <i class="bx bx-map"></i> Location
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'availability' }"
                                            @click="optionTab = 'availability'">
                                            <i class="bx bx-time-five"></i> Availability
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'addOn' }" @click="optionTab = 'addOn'">
                                            <i class="bx bx-plus-circle"></i> Add On
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'status' }" @click="optionTab = 'status'">
                                            <i class="bx bx-check-circle"></i> Status
                                        </button>
                                    </li>
                                    <li class="settings-item">
                                        <button type="button" class="settings-item__link"
                                            :class="{ 'active': optionTab === 'seo' }" @click="optionTab = 'seo'">
                                            <i class="bx bx-search-alt"></i> SEO
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div x-show="optionTab === 'general'" class="general-options">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Tour Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-fields">
                                                <label class="title">Title :</label>
                                                <input type="text" name="tour[general][title]" class="field"
                                                    value="{{ old('tour[general][title]', $tour->title) }}" placeholder=""
                                                    data-error="Title">
                                                @error('tour[general][title]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="form-fields">
                                                <label class="title">Content
                                                    :</label>
                                                <textarea class="editor" name="tour[general][content]" data-placeholder="content" data-error="Content">
                                            {{ old('tour[general][content]', $tour->content) }}
                                        </textarea>
                                                @error('tour[general][content]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <div class="form-fields">
                                                <label class="title">Lines to Display Before "See More" </label>
                                                <input oninput="this.value = Math.abs(this.value)" type="number"
                                                    min="0" name="tour[general][description_line_limit]"
                                                    class="field"
                                                    value="{{ $tour->description_line_limit !== 0 ? $tour->description_line_limit : '15' }}"
                                                    data-error="description_line_limit">
                                                @error('tour[general][description_line_limit]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12  mt-4">
                                            <div class="form-fields">
                                                <label class="title">Select category
                                                    :</label>
                                                <select name="tour[general][category_id]" class="select2-select"
                                                    data-error="Category" placeholder="Select Categories">
                                                    @php
                                                        renderCategories($categories, $tour->category->id ?? null);
                                                    @endphp
                                                </select>
                                                @error('tour[general][category_id]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <span class="title mb-0">Badge Icon:
                                                        <a class="p-0 ps-2 nav-link" href="//boxicons.com"
                                                            target="_blank">boxicons</a>
                                                    </span>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input class="form-check-input" data-toggle-switch type="checkbox"
                                                            id="enable-badge-section" value="1"
                                                            name="tour[badge][is_enabled]"
                                                            {{ old('tour.badge.is_enabled', optional(json_decode($tour->badge))->is_enabled) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="enable-badge-section">Enabled</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="text" name="tour[badge][icon_class]" class="field"
                                                        value="{{ old('tour.badge.icon_class', optional(json_decode($tour->badge))->icon_class ?? 'bx bx-badge-check') }}"
                                                        placeholder="" oninput="showIcon(this)">
                                                    <i class="{{ old('tour.badge.icon_class', optional(json_decode($tour->badge))->icon_class ?? 'bx bx-badge-check') }} bx-md"
                                                        data-preview-icon></i>
                                                </div>
                                                @error('tour.badge.icon_class')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <label class="title">Badge Name:</label>
                                                <input type="text" name="tour[badge][name]" class="field"
                                                    value="{{ old('tour.badge.name', optional(json_decode($tour->badge))->name) }}"
                                                    placeholder="">
                                                @error('tour.badge.name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Badge Background Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="background-color-picker" data-color-picker></label>
                                                    <input id="background-color-picker" type="text"
                                                        name="tour[badge][background_color]" data-color-picker-input
                                                        value="{{ old('tour.badge.background_color', optional(json_decode($tour->badge))->background_color ?? '#edab56') }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Badge Icon Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="icon-color-picker" data-color-picker></label>
                                                    <input id="icon-color-picker" type="text"
                                                        name="tour[badge][icon_color]" data-color-picker-input
                                                        value="{{ old('tour.badge.icon_color', optional(json_decode($tour->badge))->icon_color ?? '#000000') }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>







                                        <div class="col-md-12 mt-5">
                                            <div class="form-fields">
                                                <label class="title title--sm">Features:</label>

                                                <div x-data="featuresManager">
                                                    <div class="repeater-table">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            Icon:
                                                                            <a class="p-0 nav-link" href="//boxicons.com"
                                                                                target="_blank">boxicons</a>
                                                                        </div>
                                                                    </th>
                                                                    <th scope="col">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            Icon Color:
                                                                            <a class="p-0 nav-link"
                                                                                href="//html-color-codes.info"
                                                                                target="_blank">Get Color Codes</a>
                                                                        </div>
                                                                    </th>
                                                                    <th scope="col">Title</th>
                                                                    <th scope="col">Content</th>
                                                                    <th scope="col">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template x-for="(feature, index) in features"
                                                                    :key="index">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="d-flex align-items-center gap-3">
                                                                                <input type="text" class="field"
                                                                                    :name="`tour[general][features][${index}][icon]`"
                                                                                    x-model="feature.icon"
                                                                                    @input="$el.nextElementSibling.className = feature.icon"
                                                                                    placeholder="Enter icon class">
                                                                                <i style="font-size: 1.5rem"
                                                                                    :class="` ${feature.icon}  `"
                                                                                    data-preview-icon></i>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="field color-picker"
                                                                                data-color-picker-container>
                                                                                <label :for="`icon-color-picker-${index}`"
                                                                                    data-color-picker></label>
                                                                                <input type="text"
                                                                                    data-color-picker-input
                                                                                    :id="`icon-color-picker-${index}`"
                                                                                    :name="`tour[general][features][${index}][icon_color]`"
                                                                                    x-model="feature.icon_color"
                                                                                    placeholder="Enter color code">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                :name="`tour[general][features][${index}][title]`"
                                                                                x-model="feature.title" class="field"
                                                                                maxlength="50" placeholder="Enter title">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                :name="`tour[general][features][${index}][content]`"
                                                                                x-model="feature.content" class="field"
                                                                                maxlength="50"
                                                                                placeholder="Enter content">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="delete-btn ms-auto delete-btn--static"
                                                                                @click="removeFeature(index)">
                                                                                <i class='bx bxs-trash-alt'></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                        <button type="button" class="themeBtn ms-auto"
                                                            @click="addFeature">
                                                            Add <i class="bx bx-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @php
                                            $inclusions = json_decode($tour->inclusions) ?? [];
                                            $inclusions = empty($inclusions) ? [''] : $inclusions;
                                        @endphp

                                        <div class="col-md-12 mt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3">Include:</label>
                                                <div class="mb-4">
                                                    <label class="title">Title</label>
                                                    <input type="text" name="exclusions_inclusions_heading[inclusions]"
                                                        class="field"
                                                        value="{{ json_decode($tour->exclusions_inclusions_heading) ? json_decode($tour->exclusions_inclusions_heading)->inclusions : '' }}">
                                                </div>

                                                <div class="repeater-table" data-repeater>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Title</th>
                                                                <th class="text-end" scope="col">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody data-repeater-list>
                                                            @foreach ($inclusions as $inclusion)
                                                                <tr data-repeater-item>
                                                                    <td>
                                                                        <input name="tour[general][inclusions][]"
                                                                            type="text" class="field"
                                                                            value="{{ $inclusion }}">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                            data-repeater-remove>
                                                                            <i class='bx bxs-trash-alt'></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $exclusions = json_decode($tour->exclusions) ?? [];
                                            $exclusions = empty($exclusions) ? [''] : $exclusions;
                                        @endphp

                                        <div class="col-md-12 mt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3 ">Exclude:</label>
                                                <div class="mb-4">
                                                    <label class="title">Title</label>
                                                    <input type="text" name="exclusions_inclusions_heading[exclusions]"
                                                        class="field"
                                                        value="{{ json_decode($tour->exclusions_inclusions_heading) ? json_decode($tour->exclusions_inclusions_heading)->exclusions : '' }}">
                                                </div>
                                                <div class="repeater-table" data-repeater>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Title</th>
                                                                <th class="text-end" scope="col">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody data-repeater-list>
                                                            @foreach ($exclusions as $exclusion)
                                                                <tr data-repeater-item>
                                                                    <td>
                                                                        <input name="tour[general][exclusions][]"
                                                                            type="text" class="field"
                                                                            value="{{ $exclusion }}">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                            data-repeater-remove disabled>
                                                                            <i class='bx bxs-trash-alt'></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        @php
                                            $tourDetails = json_decode($tour->details, true) ?? [
                                                'title' => 'Important Information',
                                                'sections' => [],
                                            ];
                                        @endphp

                                        <div class="col-md-12 mt-4">
                                            <div class="form-fields">
                                                <label class="d-flex align-items-center mb-3 justify-content-between">
                                                    <span class="title title--sm mb-0">Tour Information:</span>
                                                </label>
                                                <div x-data="{
                                                    formData: {
                                                        sections: @js($tourDetails['sections'] ?? [])
                                                    }
                                                }">
                                                    <div class="repeater-table">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Section Title</th>
                                                                    <th>Category Title & Items</th>
                                                                    <th class="text-end">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template
                                                                    x-for="(section, sectionIndex) in formData.sections"
                                                                    :key="sectionIndex">
                                                                    <tr>
                                                                        <!-- Section Title -->
                                                                        <td>
                                                                            <input x-model="section.title"
                                                                                :name="`details[sections][${sectionIndex}][title]`"
                                                                                type="text" class="field"
                                                                                placeholder="Section Title">
                                                                        </td>

                                                                        <!-- Single Category Title with Multiple Items -->
                                                                        <td>
                                                                            <div>
                                                                                <div class="mb-2">
                                                                                    <input x-model="section.category.title"
                                                                                        :name="`details[sections][${sectionIndex}][category][title]`"
                                                                                        type="text"
                                                                                        placeholder="Category Title"
                                                                                        class="field">
                                                                                </div>
                                                                                <div class="ms-4">
                                                                                    <template
                                                                                        x-for="(item, itemIndex) in section.category.items"
                                                                                        :key="itemIndex">
                                                                                        <div class="d-flex gap-3 mb-2">
                                                                                            <input
                                                                                                x-model="section.category.items[itemIndex]"
                                                                                                :name="`details[sections][${sectionIndex}][category][items][${itemIndex}]`"
                                                                                                type="text"
                                                                                                placeholder="Item"
                                                                                                class="field">
                                                                                            <button type="button"
                                                                                                @click="section.category.items.splice(itemIndex, 1)"
                                                                                                class="delete-btn delete-btn--static align-self-center">
                                                                                                <i
                                                                                                    class="bx bxs-trash-alt"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </template>
                                                                                    <button type="button"
                                                                                        @click="section.category.items.push('')"
                                                                                        class="themeBtn mt-3">
                                                                                        Add Item <i class="bx bx-plus"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </td>

                                                                        <!-- Delete Section -->
                                                                        <td>
                                                                            <button type="button"
                                                                                @click="formData.sections.splice(sectionIndex, 1)"
                                                                                class="delete-btn delete-btn--static">
                                                                                <i class="bx bxs-trash-alt"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>

                                                        <!-- Add Section -->
                                                        <div class="mt-4">
                                                            <button type="button"
                                                                @click="formData.sections.push({ title: '', category: { title: '', items: [] } })"
                                                                class="themeBtn ms-auto">
                                                                Add <i class="bx bx-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        @php
                                            $faqs = !$tour->faqs->isEmpty()
                                                ? $tour->faqs
                                                : [['question' => '', 'answer' => '']];
                                        @endphp
                                        <div class="col-md-12 mt-5">
                                            <div class="form-fields">
                                                <label class="title title--sm">FAQs:</label>
                                                <div class="repeater-table" data-repeater>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Question</th>
                                                                <th scope="col">Answer</th>
                                                                <th class="text-end" scope="col">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody data-repeater-list>
                                                            @foreach ($faqs as $faq)
                                                                <tr data-repeater-item>
                                                                    <td>
                                                                        <textarea name="tour[general][faq][question][]" class="field" rows="2">{{ $faq['question'] ?? '' }}</textarea>
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="tour[general][faq][answer][]" class="field" rows="2">{{ $faq['answer'] ?? '' }}</textarea>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                            data-repeater-remove>
                                                                            <i class='bx bxs-trash-alt'></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mt-5">
                                            <div class="form-fields">
                                                <label class="d-flex align-items-center justify-content-between"><span
                                                        class="title title--sm mb-0">Banner:</span>
                                                    <span class="title d-flex align-items-center gap-1">Selected
                                                        Banner:
                                                        <a href="{{ asset('admin/assets/images/banner-styles/1.png') }}"
                                                            data-fancybox="gallery" class="themeBtn p-1"><i
                                                                class='bx  bxs-show'></i></a>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="form-fields">
                                                <input type="hidden" name="tour[general][banner_type]"
                                                    value="{{ $tour->banner_type ?? '1' }}">
                                                <div class="title">
                                                    <div>Banner Image :</div>
                                                </div>

                                                <div class="upload" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box {{ empty($tour->banner_image) ? 'show' : '' }}"
                                                            data-upload-box>
                                                            <input type="file" name="banner_image"
                                                                {{ empty($tour->banner_image) ? '' : '' }}
                                                                data-error="Banner Feature Image"
                                                                id="banner_featured_image" class="upload-box__file d-none"
                                                                accept="image/*" data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i>
                                                            </div>
                                                            <label for="banner_featured_image"
                                                                class="upload-box__btn themeBtn">Upload
                                                                Image</label>
                                                        </div>
                                                        <div class="upload-box__img {{ !empty($tour->banner_image) ? 'show' : '' }}"
                                                            data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="{{ asset($tour->banner_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset($tour->banner_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                    alt="{{ $tour->banner_image_alt_text }}"
                                                                    class="imgFluid" data-upload-preview>
                                                            </a>
                                                            <input type="text" name="banner_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="{{ $tour->banner_image_alt_text }}">
                                                        </div>
                                                    </div>
                                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                                        Please
                                                        upload a
                                                        valid image file
                                                    </div>
                                                    @error('banner_image')
                                                        <div class="text-danger mt-2 text-center">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="dimensions text-center mt-3">
                                                    <strong>Dimensions:</strong> 1350 &times; 400
                                                </div>
                                            </div>

                                            <div class="form-fields">
                                                <div class="title">
                                                    <div>Youtube Video :</div>
                                                </div>
                                                <input type="text" name="tour[general][video_link]" class="field"
                                                    value="{{ $tour->video_link }}">
                                                @error('tour[general][video_link]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-fields">
                                                <div class="multiple-upload" data-upload-multiple>
                                                    <input type="file" class="gallery-input d-none" multiple
                                                        data-upload-multiple-input accept="image/*" id="banners"
                                                        name="gallery[]">
                                                    <label class="multiple-upload__btn themeBtn" for="banners">
                                                        <i class='bx bx-plus'></i>
                                                        Gallery
                                                    </label>
                                                    <div class="dimensions mt-3">
                                                        <strong>Dimensions:</strong> 760 &times; 400
                                                    </div>
                                                    <ul class="multiple-upload__imgs" data-upload-multiple-images>
                                                    </ul>
                                                    <div class="text-danger error-message d-none"
                                                        data-upload-multiple-error>
                                                    </div>
                                                </div>
                                                @if (!$tour->media->isEmpty())
                                                    <div class="form-fields mt-3">
                                                        <label class="title">Current Gallery images:</label>
                                                        <ul class="multiple-upload__imgs">
                                                            @foreach ($tour->media as $media)
                                                                <li class="single-image">
                                                                    <a href="{{ route('admin.media.destroy', $media->id) }}"
                                                                        onclick="return confirm('Are you sure you want to delete this media?')"
                                                                        class="delete-btn">
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </a>
                                                                    <a class="mask"
                                                                        href="{{ asset($media->file_path) }}"
                                                                        data-fancybox="gallery">
                                                                        <img src="{{ asset($media->file_path) }}"
                                                                            class="imgFluid"
                                                                            alt="{{ $media->alt_text }}" />
                                                                    </a>
                                                                    <input type="text" value="{{ $media->alt_text }}"
                                                                        class="field" readonly>
                                                                </li>
                                                            @endforeach
                                                        </ul>

                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'location'" class="location-options">
                            <div class="form-box" x-data="{ locationType: '{{ $tour->location_type != null ? $tour->location_type : 'normal_location' }}' }">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="title">Tour Locations</div>
                                    <div class="d-flex align-items-center gap-5">
                                        <div class="form-check p-0">
                                            <input class="form-check-input" type="radio"
                                                name="tour[location][location_type]" id="normal_location"
                                                x-model="locationType" value="normal_location" checked>
                                            <label class="form-check-label" for="normal_location">Location</label>
                                        </div>
                                        <div class="form-check p-0">
                                            <input class="form-check-input" type="radio"
                                                name="tour[location][location_type]" id="normal_itinerary"
                                                x-model="locationType" value="normal_itinerary">
                                            <label class="form-check-label" for="normal_itinerary">Normal
                                                Itinerary</label>
                                        </div>
                                        <div class="form-check p-0">
                                            <input class="form-check-input" type="radio"
                                                name="tour[location][location_type]" id="itinerary_experience"
                                                x-model="locationType" value="itinerary_experience">
                                            <label class="form-check-label" for="itinerary_experience">Plan Itinerary
                                                Experience</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-box__body">
                                    <div x-show="locationType === 'normal_location'">
                                        <div class="form-fields">
                                            <label class="title">Location :</label>
                                            <select name="tour[location][normal_location][city_ids][]"
                                                class="select2-select" data-error="Location" multiple
                                                placeholder="Select Locations" autocomplete="new-password">
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        {{ $tour->cities->contains('id', $city->id) ? 'selected' : '' }}>
                                                        {{ $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @error('city_ids')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-fields">
                                            <label class="title">Real Tour address
                                                :</label>
                                            <input type="text" name="tour[location][normal_location][address]"
                                                class="field" value="{{ $tour->address }}"
                                                data-error="Real Tour address">
                                            @error('tour[location][normal_location][address]')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div x-show="locationType === 'normal_itinerary'">
                                        <div class="form-fields">
                                            <label class=" d-flex align-items-center mb-3 justify-content-between"><span
                                                    class="title title--sm mb-0">Itinerary:</span>
                                                <span class="title d-flex align-items-center gap-1">Section
                                                    Preview:
                                                    <a href="{{ asset('admin/assets/images/itinerary.png') }}"
                                                        data-fancybox="gallery" class="themeBtn p-1"><i
                                                            class='bx  bxs-show'></i></a>
                                                </span>
                                            </label>
                                            <div class="repeater-table" data-repeater>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Day - Title</th>
                                                            <th scope="col">Content</th>
                                                            <th scope="col">Feature Image</th>
                                                            <th class="text-end" scope="col">Remove</th>
                                                        </tr>
                                                    </thead>

                                                    @php
                                                        $normalItineraries = !$tour->normalItineraries->isEmpty()
                                                            ? $tour->normalItineraries
                                                            : [
                                                                [
                                                                    'id' => null,
                                                                    'day' => null,
                                                                    'title' => null,
                                                                    'description' => null,
                                                                    'featured_image' => null,
                                                                    'featured_image_alt_text' => 'Feature Image',
                                                                ],
                                                            ];
                                                    @endphp
                                                    <tbody data-repeater-list>
                                                        @foreach ($normalItineraries as $i => $itinerary)
                                                            <tr data-repeater-item>
                                                                <td class="w-25">
                                                                    <input name="tour[location][normal_itinerary][ids][]"
                                                                        type="hidden" value="{{ $itinerary['id'] }}">
                                                                    <input name="tour[location][normal_itinerary][days][]"
                                                                        type="text" class="field" placeholder="Day"
                                                                        value="{{ $itinerary['day'] }}">
                                                                    <br>
                                                                    <input name="tour[location][normal_itinerary][title][]"
                                                                        type="text" class="field mt-3"
                                                                        value="{{ $itinerary['title'] }}"
                                                                        placeholder="Title">
                                                                </td>
                                                                <td>
                                                                    <textarea name="tour[location][normal_itinerary][description][]" class="field"rows="2">{{ $itinerary['description'] }}</textarea>
                                                                </td>
                                                                <td class="w-25">
                                                                    <div class="upload upload--sm" data-upload>
                                                                        <div class="upload-box-wrapper">
                                                                            <div class="upload-box {{ empty($itinerary['featured_image']) ? 'show' : '' }}"
                                                                                data-upload-box>
                                                                                <input type="file"
                                                                                    name="tour[location][normal_itinerary][featured_image][]"
                                                                                    data-error="Feature Image"
                                                                                    id="itinerary_featured_image_{{ $i }}"
                                                                                    class="upload-box__file d-none"
                                                                                    accept="image/*" data-file-input>
                                                                                <div class="upload-box__placeholder"><i
                                                                                        class='bx bxs-image'></i>
                                                                                </div>
                                                                                <label
                                                                                    for="itinerary_featured_image_{{ $i }}"
                                                                                    class="upload-box__btn themeBtn">Upload
                                                                                    Image</label>
                                                                            </div>
                                                                            <div class="upload-box__img {{ !empty($itinerary['featured_image']) ? 'show' : '' }}"
                                                                                data-upload-img>
                                                                                <button type="button" class="delete-btn"
                                                                                    data-delete-btn><i
                                                                                        class='bx bxs-trash-alt'></i></button>
                                                                                <a href="{{ asset($itinerary['featured_image'] ?? 'admin/assets/images/placeholder.png') }}"
                                                                                    class="mask"
                                                                                    data-fancybox="gallery">
                                                                                    <img src="{{ asset($itinerary['featured_image'] ?? 'admin/assets/images/placeholder.png') }}"
                                                                                        alt="{{ $itinerary['featured_image_alt_text'] }}"
                                                                                        class="imgFluid"
                                                                                        data-upload-preview>
                                                                                </a>
                                                                                <input type="text"
                                                                                    name="tour[location][normal_itinerary][featured_image_alt_text][]"
                                                                                    class="field"
                                                                                    placeholder="Enter alt text"
                                                                                    value="{{ $itinerary['featured_image_alt_text'] }}">
                                                                            </div>
                                                                        </div>
                                                                        <div data-error-message
                                                                            class="text-danger mt-2 d-none text-center">
                                                                            Please
                                                                            upload a
                                                                            valid image file
                                                                        </div>
                                                                    </div>
                                                                    <div class="dimensions text-center mt-3">
                                                                        <strong>Dimensions:</strong> 600 &times; 400
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        data-repeater-remove disabled>
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <button type="button" class="themeBtn ms-auto" data-repeater-create>Add
                                                    <i class="bx bx-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show="locationType === 'itinerary_experience'">
                                        @php
                                            $itineraryExperience = json_decode($tour->itinerary_experience, true);
                                            $mergedPlan = [];
                                            $mainStopTitles = [];
                                            $hasSubStops = false;

                                            // Merging vehicles
                                            if (isset($itineraryExperience['vehicles'])) {
                                                foreach ($itineraryExperience['vehicles'] as $vehicle) {
                                                    if (isset($vehicle['order'])) {
                                                        $mergedPlan[] = $vehicle;
                                                    }
                                                }
                                            }

                                            // Merging stops and their sub-stops
                                            foreach ($itineraryExperience['stops'] as $stop) {
                                                if (isset($stop['order'], $stop['title'], $stop['activities'])) {
                                                    $stopData = [
                                                        'order' => $stop['order'],
                                                        'type' => 'stop',
                                                        'title' => $stop['title'],
                                                        'icon_class' => $stop['icon_class'] ?? '',
                                                        'activities' => $stop['activities'],
                                                        'sub_stops' => [], // Initialize sub_stops as an empty array
                                                    ];

                                                    // Check if sub_stops are enabled
                                                    if (
                                                        isset($itineraryExperience['enable_sub_stops']) &&
                                                        $itineraryExperience['enable_sub_stops'] == '1'
                                                    ) {
                                                        if (isset($itineraryExperience['stops']['sub_stops'])) {
                                                            // Access sub_stops
                                                            $subStops = $itineraryExperience['stops']['sub_stops'];
                                                            $subStopsMainStops = $subStops['main_stop'];
                                                            $subStopsTitles = $subStops['title'];
                                                            $subStopsActivities = $subStops['activities'];
                                                            // Check for matching main_stop titles
                                                            foreach ($subStopsMainStops as $key => $subMainStop) {
                                                                $subStopsTitle = $subStopsTitles[$key];
                                                                $subStopsActivity = $subStopsActivities[$key];
                                                                if (isset($subMainStop)) {
                                                                    if ($subMainStop === $stop['title']) {
                                                                        $stopData['sub_stops'][] = [
                                                                            'title' => $subStopsTitle ?? null,
                                                                            'activities' => $subStopsActivity ?? null,
                                                                        ];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $mergedPlan[] = $stopData; // Add stop data with sub-stops to the merged plan
                                                    $mainStopTitles[] = $stop['title'];
                                                }
                                            }

                                            // Sort merged plan by order
                                            usort($mergedPlan, function ($a, $b) {
                                                $orderA = isset($a['order']) ? (int) $a['order'] : PHP_INT_MAX;
                                                $orderB = isset($b['order']) ? (int) $b['order'] : PHP_INT_MAX;
                                                return $orderA <=> $orderB;
                                            });

                                        @endphp



                                        <div class="plan-itenirary">
                                            <div class="form-fields">
                                                <label class="d-flex align-items-center mb-3 justify-content-between">
                                                    <span class="title title--sm mb-0">Plan Itinerary
                                                        Experience:</span>
                                                    <span class="title d-flex align-items-center gap-1">
                                                        Section Preview:
                                                        <a href="{{ asset('admin/assets/images/itinerary-exp.png') }}"
                                                            data-fancybox="gallery" class="themeBtn p-1"><i
                                                                class='bx  bxs-show'></i></a>
                                                    </span>
                                                </label>

                                            </div>
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Map Iframe Link:</div>
                                                    <a class="p-0 nav-link" href="https://www.google.com/maps/d/"
                                                        target="_blank">Google Map Generator</a>
                                                </div>
                                                <input type="text" name="itinerary_experience[map_iframe]"
                                                    class="field"
                                                    value="{{ $itineraryExperience['map_iframe'] ?? '' }}">
                                                @error('itinerary_experience[map_iframe]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div x-data="handlePickupDropoff()">
                                                <template x-if="inheritFromPickup">
                                                    <div class="d-none">
                                                        <template x-for="(entry, index) in formData.dropoff"
                                                            :key="index">
                                                            <div>
                                                                <input type="hidden"
                                                                    :name="`itinerary_experience[pickup_dropoff_details][dropoff][${index}][city]`"
                                                                    :value="entry.city">
                                                                <template x-for="(point, pointIndex) in entry.points"
                                                                    :key="pointIndex">
                                                                    <input type="hidden"
                                                                        :name="`itinerary_experience[pickup_dropoff_details][dropoff][${index}][points][${pointIndex}]`"
                                                                        :value="point">
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <template x-for="(loc, index) in dropoffLocations"
                                                            :key="index">
                                                            <input type="hidden"
                                                                :name="`itinerary_experience[dropoff_locations][${index}]`"
                                                                :value="loc">
                                                        </template>
                                                    </div>
                                                </template>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-fields mt-3">
                                                            <label class="title title--sm">Pickup Locations:</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields border p-3">
                                                            <label class="title mb-2">Pickup Locations</label>
                                                            <template x-for="(loc, index) in pickupLocations"
                                                                :key="index">
                                                                <div class="d-flex gap-2 mb-2">
                                                                    <input type="text" class="field"
                                                                        x-model="pickupLocations[index]"
                                                                        :name="`itinerary_experience[pickup_locations][${index}]`">
                                                                    <button type="button"
                                                                        @click="pickupLocations.splice(index, 1)"
                                                                        class="delete-btn delete-btn--static align-self-center">
                                                                        <i class="bx bxs-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="themeBtn mt-2"
                                                                @click="pickupLocations.push('')">
                                                                Add <i class="bx bx-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-fields border p-3">
                                                            <label class="title mb-2">Pickup Sub Locations</label>
                                                            <template x-for="(entry, index) in formData.pickup"
                                                                :key="index">
                                                                <div class="mb-3">
                                                                    <div class="d-flex gap-2 mb-2">
                                                                        <select class="form-select field"
                                                                            x-model="entry.city"
                                                                            :required="entry.points.length > 0"
                                                                            :name="`itinerary_experience[pickup_dropoff_details][pickup][${index}][city]`">
                                                                            <option value="">Select Location</option>
                                                                            <template
                                                                                x-for="loc in pickupLocations.filter(l => l.trim() && (entry.city === l || !formData.pickup.some((e, i) => i !== index && e.city === l)))"
                                                                                :key="loc">
                                                                                <option :value="loc"
                                                                                    :selected="entry.city === loc"
                                                                                    x-text="loc"></option>
                                                                            </template>
                                                                        </select>

                                                                        <button type="button"
                                                                            class="delete-btn delete-btn--static align-self-center"
                                                                            @click="formData.pickup.splice(index, 1)">
                                                                            <i class="bx bxs-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                    <template x-for="(point, pointIndex) in entry.points"
                                                                        :key="pointIndex">
                                                                        <div class="d-flex gap-2 mb-2 ms-4">
                                                                            <input type="text" class="field"
                                                                                x-model="entry.points[pointIndex]"
                                                                                :name="`itinerary_experience[pickup_dropoff_details][pickup][${index}][points][${pointIndex}]`">
                                                                            <button type="button"
                                                                                class="delete-btn delete-btn--static align-self-center"
                                                                                @click="entry.points.splice(pointIndex, 1)">
                                                                                <i class="bx bxs-trash-alt"></i>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                    <button type="button" class="themeBtn mt-1 ms-4"
                                                                        @click="entry.points.push('')">
                                                                        Add Sub Location <i class="bx bx-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="themeBtn"
                                                                @click="formData.pickup.push({ city: '', points: [''] })">
                                                                Add <i class="bx bx-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div
                                                            class="form-fields d-flex align-items-center justify-content-between mt-3">
                                                            <label class="title title--sm">Dropoff Locations:</label>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" checked
                                                                    id="copyFromPickup" x-model="inheritFromPickup"
                                                                    :name="`itinerary_experience[pickup_dropoff_details][inheritFromPickup]`">
                                                                <label class="form-check-label" for="copyFromPickup">
                                                                    Same as Pickup
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6" x-show="!inheritFromPickup">
                                                        <div class="form-fields border p-3">
                                                            <label class="title mb-2">Dropoff Locations</label>
                                                            <template x-for="(loc, index) in dropoffLocations"
                                                                :key="index">
                                                                <div class="d-flex gap-2 mb-2">
                                                                    <input type="text" class="field"
                                                                        x-model="dropoffLocations[index]"
                                                                        :name="`itinerary_experience[dropoff_locations][${index}]`">
                                                                    <button type="button"
                                                                        @click="dropoffLocations.splice(index, 1)"
                                                                        class="delete-btn delete-btn--static align-self-center">
                                                                        <i class="bx bxs-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="themeBtn mt-2"
                                                                @click="dropoffLocations.push('')">
                                                                Add <i class="bx bx-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6" x-show="!inheritFromPickup">
                                                        <div class="form-fields border p-3">
                                                            <label class="title mb-2">Dropoff Sub Locations</label>
                                                            <template x-for="(entry, index) in formData.dropoff"
                                                                :key="index">
                                                                <div class="mb-3">
                                                                    <div class="d-flex gap-2 mb-2">
                                                                        <select class="form-select field"
                                                                            x-model.lazy="entry.city"
                                                                            :required="!inheritFromPickup && entry.points.length >
                                                                                0"
                                                                            :name="`itinerary_experience[pickup_dropoff_details][dropoff][${index}][city]`">
                                                                            <option value="">Select Location</option>
                                                                            <template
                                                                                x-for="(loc, index) in dropoffLocations.filter(l => l.trim() && !formData.dropoff.some((loc, i) => i !== index && loc.city === l))"
                                                                                :key="loc">
                                                                                <option :value="loc"
                                                                                    :selected="entry.city === loc"
                                                                                    x-text="loc"></option>
                                                                            </template>
                                                                        </select>
                                                                        <button type="button"
                                                                            class="delete-btn delete-btn--static align-self-center"
                                                                            @click="formData.dropoff.splice(index, 1)">
                                                                            <i class="bx bxs-trash-alt"></i>
                                                                        </button>
                                                                    </div>
                                                                    <template x-for="(point, pointIndex) in entry.points"
                                                                        :key="pointIndex">
                                                                        <div class="d-flex gap-2 mb-2 ms-4">
                                                                            <input type="text" class="field"
                                                                                x-model="entry.points[pointIndex]"
                                                                                :name="`itinerary_experience[pickup_dropoff_details][dropoff][${index}][points][${pointIndex}]`">
                                                                            <button type="button"
                                                                                class="delete-btn delete-btn--static align-self-center"
                                                                                @click="entry.points.splice(pointIndex, 1)">
                                                                                <i class="bx bxs-trash-alt"></i>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                    <button type="button" class="themeBtn mt-1 ms-4"
                                                                        @click="entry.points.push('')">
                                                                        Add Sub Location <i class="bx bx-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="themeBtn"
                                                                @click="formData.dropoff.push({ city: '', points: [''] })">
                                                                Add <i class="bx bx-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-fields mt-4 repeater-table">
                                                <div class="form-fields">
                                                    <label class="title title--sm">Itinerary:</label>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Order</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col" colspan="2">Fields</th>
                                                            <th class="text-end" scope="col">Remove</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itinerary-table-body" data-sortable-body>
                                                        @foreach ($mergedPlan as $i => $plan)
                                                            @if ($plan['type'] == 'vehicle')
                                                                <tr data-item-type="vehicle" draggable="true">
                                                                    <td>
                                                                        <div class="order-menu"><i
                                                                                class='bx-sm bx bx-menu'></i></div>
                                                                        <input type="hidden"
                                                                            name="itinerary_experience[vehicles][{{ $i }}][order]"
                                                                            value="{{ $plan['order'] }}">
                                                                        <input type="hidden"
                                                                            name="itinerary_experience[vehicles][{{ $i }}][type]"
                                                                            value="vehicle">
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center gap-1"><i
                                                                                class='bx bxs-car'></i>Vehicle</div>
                                                                    </td>
                                                                    <td><label class="title">Icon class: <a
                                                                                class="p-0 ps-2 nav-link"
                                                                                href="https://boxicons.com/"
                                                                                target="_blank">Boxicons</a></label><input
                                                                            name="itinerary_experience[vehicles][{{ $i }}][icon_class]"
                                                                            type="text" class="field"
                                                                            value="{{ $plan['icon_class'] ?? '' }}"></td>
                                                                    <td><label class="title">Name:</label><input
                                                                            name="itinerary_experience[vehicles][{{ $i }}][name]"
                                                                            value="{{ $plan['name'] }}" type="text"
                                                                            class="field"></td>
                                                                    <td><label class="title">Time (mins):</label><input
                                                                            name="itinerary_experience[vehicles][{{ $i }}][time]"
                                                                            value="{{ $plan['time'] }}" type="number"
                                                                            class="field"></td>
                                                                    <td><button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"><i
                                                                                class='bx bxs-trash-alt'></i></button></td>
                                                                </tr>
                                                            @elseif($plan['type'] == 'stop')
                                                                <tr data-item-type="stop" draggable="true">
                                                                    <td>
                                                                        <div class="order-menu"><i
                                                                                class='bx-sm bx bx-menu'></i></div>
                                                                        <input type="hidden"
                                                                            name="itinerary_experience[stops][{{ $i }}][order]"
                                                                            value="{{ $plan['order'] }}">
                                                                        <input type="hidden"
                                                                            name="itinerary_experience[stops][{{ $i }}][type]"
                                                                            value="stop">
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center gap-1"><i
                                                                                class="bx bx-star"></i>Stop</div>
                                                                    </td>
                                                                    <td><label class="title">Icon class: <a
                                                                                class="p-0 ps-2 nav-link"
                                                                                href="https://boxicons.com/"
                                                                                target="_blank">Boxicons</a></label><input
                                                                            name="itinerary_experience[stops][{{ $i }}][icon_class]"
                                                                            type="text" class="field"
                                                                            value="{{ $plan['icon_class'] ?? '' }}"></td>
                                                                    <td><label class="title">Title:</label><input
                                                                            name="itinerary_experience[stops][{{ $i }}][title]"
                                                                            value="{{ $plan['title'] }}" type="text"
                                                                            class="field"></td>
                                                                    <td><label class="title">Activities:</label><input
                                                                            name="itinerary_experience[stops][{{ $i }}][activities]"
                                                                            value="{{ $plan['activities'] }}"
                                                                            type="text" class="field"></td>
                                                                    <td><button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"><i
                                                                                class='bx bxs-trash-alt'></i></button></td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="dropdown bootsrap-dropdown mt-4 d-flex justify-content-end">
                                                    <button type="button" class="themeBtn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Add <i class="bx bx-chevron-down"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-itinerary-action="add-vehicle">
                                                                <i class='bx bxs-car'></i> Add Vehicle
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-itinerary-action="add-stop">
                                                                <i class="bx bx-star"></i> Add Stop
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-check {{ isset($itineraryExperience['enable_sub_stops']) ? '' : 'd-none' }}"
                                                id="add-stop-btn">
                                                <input class="form-check-input" type="checkbox"
                                                    name="itinerary_experience[enable_sub_stops]"
                                                    id="itinerary_experience_enabled_sub_stops" value="1"
                                                    {{ isset($itineraryExperience['enable_sub_stops']) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="itinerary_experience_enabled_sub_stops">Add
                                                    Sub Stops</label>
                                            </div>
                                            <div class="form-fields mt-4 {{ isset($itineraryExperience['enable_sub_stops']) ? '' : 'd-none' }}"
                                                id="itinerary_experience_sub_stops">
                                                <label class="title title--sm">Sub Stops:</label>
                                                <div class="repeater-table" data-repeater>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Main Stop</th>
                                                                <th scope="col">Fields</th>
                                                                <th class="text-end" scope="col">Remove</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody data-repeater-list>
                                                            @foreach ($mergedPlan as $i => $plan)
                                                                @if ($plan['type'] == 'stop' && isset($plan['sub_stops']) && count($plan['sub_stops']) > 0)
                                                                    @php $hasSubStops = true; @endphp
                                                                    @foreach ($plan['sub_stops'] as $j => $subStop)
                                                                        <tr data-repeater-item>
                                                                            <td>
                                                                                <select
                                                                                    name="itinerary_experience[stops][sub_stops][main_stop][]"
                                                                                    class="field main-stop-dropdown">
                                                                                    <option value="" selected>Select
                                                                                    </option>
                                                                                    @foreach ($mainStopTitles as $mainStop)
                                                                                        <option
                                                                                            value="{{ $mainStop }}"
                                                                                            {{ $mainStop === $plan['title'] ? 'selected' : '' }}>
                                                                                            {{ $mainStop }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input
                                                                                    name="itinerary_experience[stops][sub_stops][title][]"
                                                                                    type="text" class="field"
                                                                                    value="{{ $subStop['title'] }}"
                                                                                    placeholder="Title">
                                                                                <br>
                                                                                <div class="mt-3">
                                                                                    <input
                                                                                        name="itinerary_experience[stops][sub_stops][activities][]"
                                                                                        type="text" class="field"
                                                                                        value="{{ $subStop['activities'] }}"
                                                                                        placeholder="Activities">
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="delete-btn ms-auto delete-btn--static"
                                                                                    data-repeater-remove>
                                                                                    <i class='bx bxs-trash-alt'></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach

                                                            @if (!$hasSubStops)
                                                                <tr data-repeater-item>
                                                                    <td>
                                                                        <select
                                                                            name="itinerary_experience[stops][sub_stops][main_stop][]"
                                                                            class="field main-stop-dropdown">
                                                                            <option value="" selected>Select</option>
                                                                            @foreach ($mainStopTitles as $mainStop)
                                                                                <option value="{{ $mainStop }}">
                                                                                    {{ $mainStop }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input
                                                                            name="itinerary_experience[stops][sub_stops][title][]"
                                                                            type="text" class="field"
                                                                            placeholder="Title">
                                                                        <br>
                                                                        <div class="mt-3">
                                                                            <input
                                                                                name="itinerary_experience[stops][sub_stops][activities][]"
                                                                                type="text" class="field"
                                                                                placeholder="Activities">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                            data-repeater-remove>
                                                                            <i class='bx bxs-trash-alt'></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i></button>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'pricing'" class="pricing-options">
                            <div class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="title">Pricing</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="form-fields">
                                                <div class="title title--sm">Tour Price:</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-3">
                                            <div class="form-fields">
                                                <label class="title">Price :</label>
                                                <input step="0.01" min="0" type="number"
                                                    name="tour[pricing][regular_price]" class="field"
                                                    value="{{ old('tour[pricing][regular_price]', $tour->regular_price) }}"
                                                    data-error="Price">
                                                @error('tour[pricing][regular_price]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <label class="title">Sale Price :</label>
                                                <input step="0.01" min="0" type="number"
                                                    name="tour[pricing][sale_price]" class="field"
                                                    value="{{ old('tour[pricing][sale_price]', $tour->sale_price) }}"
                                                    data-error="Sale Price">
                                                @error('tour[pricing][sale_price]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 my-2">
                                            <hr>
                                        </div>
                                        <div class="col-12" x-data="{ personType: {{ $tour->is_person_type_enabled == '1' ? '1' : '0' }} }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Person Types:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="tour[pricing][is_person_type_enabled]"
                                                                {{ $tour->is_person_type_enabled == '1' ? 'checked' : '' }}
                                                                id="enebled_person_types" value="1"
                                                                x-model="personType"
                                                                @change="personType = personType ? 1 : 0">
                                                            <label class="form-check-label" for="enebled_person_types">
                                                                Enable Person Types
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12" x-show="personType == 1">
                                                    <div x-data="{ tourType: '{{ $tour->price_type != null ? $tour->price_type : 'normal' }}' }">
                                                        <div
                                                            class="d-flex align-items-center justify-content-center gap-5 mt-3 mb-4">
                                                            <div class="form-check p-0">
                                                                <input class="form-check-input" type="radio"
                                                                    name="tour[pricing][price_type]" x-model="tourType"
                                                                    value="normal" id="normalPrice" checked>
                                                                <label class="form-check-label" for="normalPrice">Normal
                                                                    Tour
                                                                    Price</label>
                                                            </div>
                                                            <div class="form-check p-0">
                                                                <input class="form-check-input" type="radio"
                                                                    name="tour[pricing][price_type]" x-model="tourType"
                                                                    value="private" id="privatePrice">
                                                                <label class="form-check-label" for="privatePrice">Private
                                                                    Tour Price</label>
                                                            </div>
                                                            <div class="form-check p-0">
                                                                <input class="form-check-input" type="radio"
                                                                    name="tour[pricing][price_type]" x-model="tourType"
                                                                    value="water" id="waterPrice">
                                                                <label class="form-check-label" for="waterPrice">Water /
                                                                    Desert
                                                                    Activities</label>
                                                            </div>
                                                            <div class="form-check p-0">
                                                                <input class="form-check-input" type="radio"
                                                                    name="tour[pricing][price_type]" x-model="tourType"
                                                                    value="promo" id="promoPrice">
                                                                <label class="form-check-label"
                                                                    for="promoPrice">Promo</label>
                                                            </div>
                                                        </div>
                                                        <div x-show="tourType === 'normal'">
                                                            <div class="form-fields">
                                                                <div class="title title--sm">Normal Tour Pricing:</div>
                                                                <div class="repeater-table" data-repeater>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">
                                                                                    Person Type
                                                                                </th>
                                                                                <th scope="col">Min</th>
                                                                                <th scope="col">Max</th>
                                                                                <th scope="col">Price</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        @php
                                                                            $normalTourPrices = !$tour->normalPrices->isEmpty()
                                                                                ? $tour->normalPrices
                                                                                : [
                                                                                    [
                                                                                        'person_type' => '',
                                                                                        'person_description' => '',
                                                                                        'min_person' => '',
                                                                                        'max_person' => '',
                                                                                        'price' => '',
                                                                                    ],
                                                                                ];
                                                                        @endphp
                                                                        <tbody data-repeater-list>
                                                                            @foreach ($normalTourPrices as $normalTourPrice)
                                                                                <tr data-repeater-item>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="tour[pricing][normal][person_type][]"
                                                                                            class="field"
                                                                                            value="{{ $normalTourPrice['person_type'] }}"
                                                                                            placeholder="Eg: Adult">
                                                                                        <br>
                                                                                        <div class="mt-3">
                                                                                            <input type="text"
                                                                                                name="tour[pricing][normal][person_description][]"
                                                                                                class="field"
                                                                                                value="{{ $normalTourPrice['person_description'] }}"
                                                                                                placeholder="Description">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            min="0"
                                                                                            name="tour[pricing][normal][min_person][]"
                                                                                            value="{{ $normalTourPrice['min_person'] }}"
                                                                                            class="field">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            min="0"
                                                                                            name="tour[pricing][normal][max_person][]"
                                                                                            value="{{ $normalTourPrice['max_person'] }}"
                                                                                            class="field">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            step="0.01" min="0"
                                                                                            name="tour[pricing][normal][price][]"
                                                                                            value="{{ $normalTourPrice['price'] }}"
                                                                                            class="field">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                                            data-repeater-remove disabled>
                                                                                            <i
                                                                                                class='bx bxs-trash-alt'></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <button type="button" class="themeBtn ms-auto"
                                                                        data-repeater-create>Add
                                                                        <i class="bx bx-plus"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div x-show="tourType === 'private'">
                                                            <div class="form-fields">
                                                                <div class="title title--sm">Private Tour Pricing:</div>
                                                                <div class="repeater-table">
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Car Price</th>
                                                                                <th scope="col">Min</th>
                                                                                <th scope="col">Max</th>

                                                                            </tr>
                                                                        </thead>
                                                                        @php
                                                                            $privateTourPrice =
                                                                                $tour->privatePrices != null
                                                                                    ? $tour->privatePrices
                                                                                    : [
                                                                                        'car_price' => '',
                                                                                        'min_person' => '',
                                                                                        'max_person' => '',
                                                                                    ];
                                                                        @endphp
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="number" step="0.01"
                                                                                        min="0"
                                                                                        name="tour[pricing][private][car_price]"
                                                                                        value="{{ $privateTourPrice['car_price'] }}"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][private][min_person]"
                                                                                        value="{{ $privateTourPrice['min_person'] }}"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][private][max_person]"
                                                                                        value="{{ $privateTourPrice['max_person'] }}"
                                                                                        class="field">
                                                                                </td>

                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div x-show="tourType === 'water'">
                                                            <div class="form-fields">
                                                                <div class="repeater-table" data-repeater>
                                                                    <label class="title title--sm">Water / Desert
                                                                        Activities
                                                                        Pricing:</label>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Time</th>
                                                                                <th scope="col">Price</th>
                                                                                <th class="text-end" scope="col">Remove
                                                                                </th>
                                                                            </tr>
                                                                        </thead>

                                                                        @php
                                                                            $waterMints = [
                                                                                '00:15',
                                                                                '00:30',
                                                                                '00:45',
                                                                                '01:00',
                                                                                '01:15',
                                                                                '01:30',
                                                                                '01:45',
                                                                                '02:00',
                                                                                '02:15',
                                                                                '02:30',
                                                                                '02:45',
                                                                                '03:00',
                                                                                '03:15',
                                                                                '03:30',
                                                                                '03:45',
                                                                                '04:00',
                                                                            ];
                                                                            $waterTourPrices = !$tour->waterPrices->isEmpty()
                                                                                ? $tour->waterPrices
                                                                                : [
                                                                                    [
                                                                                        'time' => '00:15:00',
                                                                                        'water_price' => '',
                                                                                    ],
                                                                                ];
                                                                        @endphp
                                                                        <tbody data-repeater-list>
                                                                            @foreach ($waterTourPrices as $waterTourPrice)
                                                                                @php
                                                                                    $selectedTime = substr(
                                                                                        $waterTourPrice['time'],
                                                                                        0,
                                                                                        5,
                                                                                    );
                                                                                @endphp
                                                                                <tr data-repeater-item>
                                                                                    <td>
                                                                                        <select
                                                                                            name="tour[pricing][water][time][]"
                                                                                            class="field"
                                                                                            data-error="Desert Activities Time">
                                                                                            <option value="">Select
                                                                                                Time
                                                                                            </option>
                                                                                            @foreach ($waterMints as $waterMint)
                                                                                                <option
                                                                                                    value="{{ $waterMint }}"
                                                                                                    {{ $waterMint === $selectedTime ? 'selected' : '' }}>

                                                                                                    {{ $waterMint }}
                                                                                                    ({{ (int) substr($waterMint, 0, 2) * 60 + (int) substr($waterMint, 3, 2) }}
                                                                                                    mins)
                                                                                                </option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input
                                                                                            name="tour[pricing][water][water_price][]"
                                                                                            type="number"
                                                                                            class="field"
                                                                                            placeholder="Price"
                                                                                            step="0.01"
                                                                                            value="{{ $waterTourPrice['water_price'] }}"
                                                                                            min="0"
                                                                                            data-error="Desert Activities Price">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                                            data-repeater-remove disabled>
                                                                                            <i
                                                                                                class='bx bxs-trash-alt'></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <button type="button" class="themeBtn ms-auto"
                                                                        data-repeater-create>Add
                                                                        <i class="bx bx-plus"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div x-show="tourType === 'promo'">
                                                            <div class="form-fields">
                                                                <label class="title title--sm">Promo Pricing
                                                                    Discounts:</label>
                                                                @php
                                                                    $promoDiscountConfig =
                                                                        isset($tour->promo_discount_config) &&
                                                                        $tour->promo_discount_config
                                                                            ? json_decode($tour->promo_discount_config)
                                                                            : null;
                                                                @endphp
                                                                <div class="row mb-4">
                                                                    <div class="col-md-12">
                                                                        <label class="title title--xs">Weekday Discounts
                                                                            (Mon–Thu):</label>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Renewal Timer (hours)
                                                                                :</label>
                                                                            <select
                                                                                name="tour[pricing][promo][discount][weekday_timer_hours]"
                                                                                class="field">
                                                                                <option value="" selected disabled>
                                                                                    Select</option>
                                                                                @for ($i = 1; $i <= 12; $i++)
                                                                                    <option value="{{ $i }}"
                                                                                        {{ $i == ($promoDiscountConfig->weekday_timer_hours ?? 10) ? 'selected' : '' }}>
                                                                                        {{ $i }}
                                                                                    </option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Discount (%) :</label>
                                                                            <input type="number"
                                                                                value="{{ $promoDiscountConfig->weekday_discount_percent ?? '' }}"
                                                                                name="tour[pricing][promo][discount][weekday_discount_percent]"
                                                                                step="0.01" min="0"
                                                                                class="field">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-4">
                                                                    <div class="col-md-12">
                                                                        <label class="title title--xs">Weekend Discounts
                                                                            (Fri–Sun):</label>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Renewal Timer (hours)
                                                                                :</label>
                                                                            <select
                                                                                name="tour[pricing][promo][discount][weekend_timer_hours]"
                                                                                class="field">
                                                                                <option value="" selected disabled>
                                                                                    Select</option>
                                                                                @for ($i = 1; $i <= 12; $i++)
                                                                                    <option value="{{ $i }}"
                                                                                        {{ $i == ($promoDiscountConfig->weekend_timer_hours ?? 10) ? 'selected' : '' }}>
                                                                                        {{ $i }}
                                                                                    </option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Discount (%) :</label>
                                                                            <input type="number"
                                                                                value="{{ $promoDiscountConfig->weekend_discount_percent ?? '' }}"
                                                                                name="tour[pricing][promo][discount][weekend_discount_percent]"
                                                                                step="0.01" min="0"
                                                                                class="field">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="repeater-table" data-repeater>
                                                                    <label class="title title--sm">Promo Pricing:</label>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Title</th>
                                                                                <th scope="col">Price</th>
                                                                                <th class="text-end" scope="col">
                                                                                    Remove
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        @php
                                                                            $promoTourPrices = !$tour->promoPrices->isEmpty()
                                                                                ? $tour->promoPrices
                                                                                : [
                                                                                    [
                                                                                        'promo_title' => '',
                                                                                        'original_price' => '',
                                                                                    ],
                                                                                ];
                                                                        @endphp
                                                                        <tbody data-repeater-list>
                                                                            @foreach ($promoTourPrices as $i => $promoTourPrice)
                                                                                <tr data-repeater-item>
                                                                                    <td>
                                                                                        <textarea rows="6" name="tour[pricing][promo][promo_title][]" class="field" placeholder="E.g., Adult"
                                                                                            data-error="Package Title">{{ $promoTourPrice['promo_title'] }}</textarea>

                                                                                    </td>
                                                                                    <td style="width: 35%"
                                                                                        calculate-promo-price>
                                                                                        <div>
                                                                                            <input
                                                                                                name="tour[pricing][promo][original_price][]"
                                                                                                type="number"
                                                                                                class="field"
                                                                                                placeholder="Original Price"
                                                                                                value="{{ $promoTourPrice['original_price'] }}"
                                                                                                step="0.01"
                                                                                                min="0">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                                            data-repeater-remove disabled>
                                                                                            <i
                                                                                                class='bx bxs-trash-alt'></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <button type="button" class="themeBtn ms-auto"
                                                                        data-repeater-create>Add
                                                                        <i class="bx bx-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 my-2">
                                            <hr>
                                        </div>
                                        <div class="col-12" x-data="{ extraPrice: {{ $tour->is_extra_price_enabled == '1' ? '1' : '0' }} }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Extra Price:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="tour[pricing][is_extra_price_enabled]"
                                                                {{ $tour->is_extra_price_enabled == '1' ? 'checked' : '' }}
                                                                id="enebled_extra_price"
                                                                @change="extraPrice = extraPrice ? 1 : 0" value="1"
                                                                x-model="extraPrice">
                                                            <label class="form-check-label" for="enebled_extra_price">
                                                                Enable extra price
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12" x-show="extraPrice == 1">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-fields">
                                                                <div class="title">Extra Price:</div>
                                                                <div class="repeater-table" data-repeater>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">Name</th>
                                                                                <th scope="col">Price</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        @php
                                                                            $tourExtraPrices = $tour->extra_prices
                                                                                ? json_decode($tour->extra_prices)
                                                                                : [
                                                                                    [
                                                                                        'name' => '',
                                                                                        'price' => '',
                                                                                    ],
                                                                                ];

                                                                        @endphp
                                                                        <tbody data-repeater-list>
                                                                            @foreach ($tourExtraPrices as $i => $extraPrice)
                                                                                <tr data-repeater-item>
                                                                                    <td>
                                                                                        <input type="text"
                                                                                            name="tour[pricing][extra_price][{{ $i }}][name]"
                                                                                            class="field"
                                                                                            value="{{ $extraPrice->name }}"
                                                                                            placeholder="Extra Price Name">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            step="0.01"
                                                                                            min="0"
                                                                                            value="{{ $extraPrice->price }}"
                                                                                            name="tour[pricing][extra_price][{{ $i }}][price]"
                                                                                            class="field">
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                                            data-repeater-remove disabled>
                                                                                            <i
                                                                                                class='bx bxs-trash-alt'></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                    <button type="button" class="themeBtn ms-auto"
                                                                        data-repeater-create>Add <i
                                                                            class="bx bx-plus"></i></button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 my-2">
                                            <hr>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 mt-3">
                                                            <div class="form-fields">
                                                                <div class="title">Discount by number of people:</div>
                                                                <div class="repeater-table" data-repeater>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col">
                                                                                    No of people
                                                                                </th>
                                                                                <th scope="col">Discount
                                                                                </th>
                                                                                <th scope="col">Type</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        @php
                                                                            $tourDiscounts = $tour->discount_by_number_of_people
                                                                                ? json_decode(
                                                                                    $tour->discount_by_number_of_people,
                                                                                )
                                                                                : null;
                                                                            $tourDiscounts = !empty($tourDiscounts)
                                                                                ? $tourDiscounts
                                                                                : [
                                                                                    [
                                                                                        'people_from' => '',
                                                                                        'people_to' => '',
                                                                                        'discount' => '',
                                                                                        'type' => '',
                                                                                    ],
                                                                                ];
                                                                        @endphp

                                                                        <tbody data-repeater-list>
                                                                            @foreach ($tourDiscounts as $discount)
                                                                                @php
                                                                                    // Safely access keys and assign default values if missing
                                                                                    $people_from =
                                                                                        $discount['people_from'] ?? '';
                                                                                    $people_to =
                                                                                        $discount['people_to'] ?? '';
                                                                                    $discount_price =
                                                                                        $discount['discount'] ?? '';
                                                                                    $discount_type =
                                                                                        $discount['type'] ?? '';
                                                                                @endphp
                                                                                <tr data-repeater-item>
                                                                                    <td>
                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <input type="number"
                                                                                                    min="0"
                                                                                                    name="tour[pricing][discount][people_from][]"
                                                                                                    class="field"
                                                                                                    value="{{ $people_from }}"
                                                                                                    placeholder="from">
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <input type="number"
                                                                                                    min="0"
                                                                                                    name="tour[pricing][discount][people_to][]"
                                                                                                    class="field"
                                                                                                    value="{{ $people_to }}"
                                                                                                    placeholder="to">
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number"
                                                                                            min="0"
                                                                                            name="tour[pricing][discount][discount][]"
                                                                                            value="{{ $discount_price }}"
                                                                                            class="field"
                                                                                            placeholder="">
                                                                                    </td>
                                                                                    <td>
                                                                                        <select class="field"
                                                                                            name="tour[pricing][discount][type][]">
                                                                                            <option value=""
                                                                                                selected>Select</option>
                                                                                            <option value="fixed"
                                                                                                {{ $discount_type == 'fixed' ? 'selected' : '' }}>
                                                                                                Fixed</option>
                                                                                            <option value="percent"
                                                                                                {{ $discount_type == 'percent' ? 'selected' : '' }}>
                                                                                                Percent</option>
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="delete-btn ms-auto delete-btn--static"
                                                                                            data-repeater-remove disabled>
                                                                                            <i
                                                                                                class='bx bxs-trash-alt'></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>

                                                                    </table>
                                                                    <button type="button" class="themeBtn ms-auto"
                                                                        data-repeater-create>Add
                                                                        <i class="bx bx-plus"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 my-2">
                                            <hr>
                                        </div>
                                        <div class="col-12 my-2" x-data="{ serviceFee: {{ $tour->enabled_custom_service_fee == '1' ? '1' : '0' }} }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Service fee:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="enebled_service_fee"
                                                                {{ $tour->enabled_custom_service_fee == '1' ? 'checked' : '' }}
                                                                name="tour[pricing][enabled_custom_service_fee]"
                                                                value="{{ $tour->enabled_custom_service_fee }}"
                                                                x-model="serviceFee"
                                                                @change="serviceFee = serviceFee ? 1 : 0">
                                                            <label class="form-check-label" for="enebled_service_fee">
                                                                Enable service fee
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12" x-show="serviceFee == 1">
                                                    <div class="form-fields mt-2">
                                                        <input step="0.01" min="0" type="number"
                                                            name="tour[pricing][service_fee_price]" class="field"
                                                            value="{{ old('tour[pricing][service_fee_price]', $tour->service_fee_price) }}"
                                                            data-error="Price">
                                                        @error('tour[pricing][service_fee_price]')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="form-fields mb-4">
                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                    <span class="title mb-0">Whatsapp Number:</span>
                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input class="form-check-input" data-toggle-switch
                                                            type="checkbox" id="enable-section"
                                                            {{ $tour->show_phone == '1' ? 'checked' : '' }}
                                                            value="1" name="tour[pricing][show_phone]">
                                                        <label class="form-check-label"
                                                            for="enable-section">Enabled</label>
                                                    </div>
                                                </div>
                                                <div data-flag-input-wrapper>
                                                    <input type="hidden" name="tour[pricing][phone_dial_code]"
                                                        data-flag-input-dial-code value="{{ $tour->phone_dial_code }}">
                                                    <input type="hidden" name="tour[pricing][phone_country_code]"
                                                        data-flag-input-country-code
                                                        value="{{ $tour->phone_country_code }}">
                                                    <input type="text" name="tour[pricing][phone_number]"
                                                        class="field flag-input" data-flag-input
                                                        value="{{ old('tour[pricing][phone_number]', $tour->phone_number) }}"
                                                        placeholder="Phone" data-error="phone" inputmode="numeric"
                                                        pattern="[0-9]*"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                        maxlength="15">
                                                </div>

                                                @error('tour[pricing][phone_number]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'availability'" class="availability-options">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Availability</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="col-12" x-data="{ fixedDate: '{{ $tour->is_fixed_date ? '1' : '0' }}' }">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="form-fields">
                                                    <div class="title">Fixed dates:</div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            {{ $tour->is_fixed_date ? 'checked' : '' }}
                                                            name="tour[availability][is_fixed_date]" id="fixed_date"
                                                            value="{{ $tour->is_fixed_date ? '1' : '0' }}"
                                                            x-model="fixedDate" @change="fixedDate = fixedDate ? 1 : 0">
                                                        <label class="form-check-label" for="fixed_date">
                                                            Enable Fixed Date
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" x-show="fixedDate == 1">
                                                <div class="row my-2">
                                                    <div class="col-md-4">
                                                        <div class="form-fields">
                                                            <label class="title">Start Date :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date"
                                                                name="tour[availability][start_date]" autocomplete="off"
                                                                value="{{ $tour->start_date }}">
                                                            @error('tour[availability][start_date]')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-fields">
                                                            <label class="title">End Date :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date"
                                                                name="tour[availability][end_date]" autocomplete="off"
                                                                value="{{ $tour->end_date }}">
                                                            @error('tour[availability][end_date]')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-fields">
                                                            <label class="title">Last Booking Date :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date"
                                                                name="tour[availability][last_booking_date]"
                                                                value="{{ $tour->last_booking_date }}">
                                                            @error('last_booking_date')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3" x-data="{ openHours: '{{ $tour->is_open_hours ? '1' : '0' }}' }">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="form-fields">
                                                    <div class="title">Open Hours:</div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="tour[availability][is_open_hours]"
                                                            {{ $tour->is_open_hours ? 'checked' : '' }} id="openHours"
                                                            value="{{ $tour->is_open_hours ? '1' : '0' }}"
                                                            x-model="openHours" @change="openHours = openHours ? 1 : 0">
                                                        <label class="form-check-label" for="openHours">
                                                            Enable Open Hours
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" x-show="openHours == 1">
                                                <div class="row my-2">
                                                    <div class="repeater-table form-fields">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Enable? </th>
                                                                    <th scope="col">Day of Week </th>
                                                                    <th scope="col">Open</th>
                                                                    <th scope="col">Close</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $days = [
                                                                        'Monday',
                                                                        'Tuesday',
                                                                        'Wednesday',
                                                                        'Thursday',
                                                                        'Friday',
                                                                    ];
                                                                    $timeSlots = [
                                                                        '00:00:00',
                                                                        '02:00:00',
                                                                        '03:00:00',
                                                                        '04:00:00',
                                                                        '05:00:00',
                                                                        '06:00:00',
                                                                        '07:00:00',
                                                                        '08:00:00',
                                                                        '09:00:00',
                                                                        '10:00:00',
                                                                        '11:00:00',
                                                                        '12:00:00',
                                                                        '13:00:00',
                                                                        '14:00:00',
                                                                        '15:00:00',
                                                                        '16:00:00',
                                                                        '17:00:00',
                                                                        '18:00:00',
                                                                        '19:00:00',
                                                                        '20:00:00',
                                                                        '21:00:00',
                                                                        '22:00:00',
                                                                        '23:00:00',
                                                                    ];

                                                                    $openHour = $tour->availability_open_hours
                                                                        ? json_decode(
                                                                            $tour->availability_open_hours,
                                                                            true,
                                                                        )
                                                                        : [];

                                                                    $openHour = array_replace(
                                                                        array_fill(0, count($days), [
                                                                            'enabled' => '',
                                                                            'open_time' => '',
                                                                            'close_time' => '',
                                                                        ]),
                                                                        $openHour,
                                                                    );
                                                                @endphp
                                                                @for ($i = 0; $i < count($days); $i++)
                                                                    @php
                                                                        $day = $days[$i];
                                                                        $dayData = $openHour[$i] ?? [
                                                                            'enabled' => '',
                                                                            'open_time' => '',
                                                                            'close_time' => '',
                                                                        ];
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    name="tour[availability][open_hours][{{ $i }}][enabled]"
                                                                                    id="{{ $day }}"
                                                                                    value="1"
                                                                                    {{ isset($dayData['enabled']) && $dayData['enabled'] === '1' ? 'checked' : '' }}>
                                                                                <label class="form-check-label"
                                                                                    for="{{ $day }}">
                                                                                    Enable
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                name="tour[availability][open_hours][{{ $i }}][day]"
                                                                                type="text"
                                                                                value="{{ $day }}"
                                                                                class="field" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <select class="field"
                                                                                name="tour[availability][open_hours][{{ $i }}][open_time]">
                                                                                <option value="">Select Time
                                                                                </option>
                                                                                @foreach ($timeSlots as $slot)
                                                                                    <option value="{{ $slot }}"
                                                                                        {{ $dayData['open_time'] === $slot ? 'selected' : '' }}>
                                                                                        {{ date('H:i', strtotime($slot)) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="field"
                                                                                name="tour[availability][open_hours][{{ $i }}][close_time]">
                                                                                <option value="">Select Time
                                                                                </option>
                                                                                @foreach ($timeSlots as $slot)
                                                                                    <option value="{{ $slot }}"
                                                                                        {{ $dayData['close_time'] === $slot ? 'selected' : '' }}>
                                                                                        {{ date('H:i', strtotime($slot)) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'addOn'" class="addOn-options">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Add On</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <div x-data="repeaterFormForAddOns({{ $tour->addOns->toJson() }})" x-init="initChoices()">
                                            <div class="repeater-table">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Section</th>
                                                            <th class="text-end">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(addOn, index) in formData.addOns"
                                                            :key="index">
                                                            <tr>
                                                                <td>
                                                                    <div class="form-fields">
                                                                        <label class="title text-dark">Heading</label>
                                                                        <input :name="`addOns[${index}][heading]`"
                                                                            type="text" class="field"
                                                                            x-model="addOn.heading">
                                                                    </div>
                                                                    <div class="form-fields">
                                                                        <label class="title text-dark">Select
                                                                            tours</label>
                                                                        <select :name="`addOns[${index}][tour_ids][]`"
                                                                            class="choices-select" multiple
                                                                            x-ref="select${index}">
                                                                            @foreach ($tours as $tourAddOn)
                                                                                <option :value="'{{ $tourAddOn->id }}'"
                                                                                    :selected="addOn.tour_ids.includes(
                                                                                        '{{ $tourAddOn->id }}')">
                                                                                    {{ $tourAddOn->title }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button type="button" @click="removeAddOn(index)"
                                                                        class="delete-btn delete-btn--static ms-auto">
                                                                        <i class="bx bxs-trash-alt"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <div class="mt-4">
                                                    <button type="button" @click="addAddOn()"
                                                        class="themeBtn ms-auto">
                                                        Add <i class="bx bx-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'status'" class="status-options">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tour[status][status]"
                                            id="publish" value="publish"
                                            {{ $tour->status == 'publish' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="tour[status][status]"
                                            id="draft" value="draft"
                                            {{ $tour->status == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            Draft
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Images</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-fields">
                                                <label class="title">Feature Image
                                                    :</label>
                                                <div class="upload" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box {{ empty($tour->featured_image) ? 'show' : '' }}"
                                                            data-upload-box>
                                                            <input type="file" name="featured_image"
                                                                {{ empty($tour->featured_image) ? '' : '' }}
                                                                data-error="Feature Image" id="featured_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i>
                                                            </div>
                                                            <label for="featured_image"
                                                                class="upload-box__btn themeBtn">Upload
                                                                Image</label>
                                                        </div>
                                                        <div class="upload-box__img {{ !empty($tour->featured_image) ? 'show' : '' }}"
                                                            data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="{{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                    alt="{{ $tour->featured_image_alt_text }}"
                                                                    class="imgFluid" data-upload-preview>
                                                            </a>
                                                            <input type="text" name="featured_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="{{ $tour->featured_image_alt_text }}">
                                                        </div>
                                                    </div>
                                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                                        Please
                                                        upload a
                                                        valid image file
                                                    </div>
                                                    @error('featured_image')
                                                        <div class="text-danger mt-2 text-center">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="dimensions text-center mt-3">
                                                    <strong>Dimensions:</strong> 270 &times; 260
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-fields">
                                                <label class="title">Promotional Image:</label>
                                                <div class="upload" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box {{ empty($tour->promotional_image) ? 'show' : '' }}"
                                                            data-upload-box>
                                                            <input type="file" name="promotional_image"
                                                                {{ empty($tour->promotional_image) ? '' : '' }}
                                                                data-error="Feature Image" id="promotional_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i>
                                                            </div>
                                                            <label for="promotional_image"
                                                                class="upload-box__btn themeBtn">Upload
                                                                Image</label>
                                                        </div>
                                                        <div class="upload-box__img {{ !empty($tour->promotional_image) ? 'show' : '' }}"
                                                            data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="{{ asset($tour->promotional_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset($tour->promotional_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                    alt="{{ $tour->promotional_image_alt_text }}"
                                                                    class="imgFluid" data-upload-preview>
                                                            </a>
                                                            <input type="text" name="promotional_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="{{ $tour->promotional_image_alt_text }}">
                                                        </div>
                                                    </div>
                                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                                        Please
                                                        upload a
                                                        valid image file
                                                    </div>
                                                    @error('promotional_image')
                                                        <div class="text-danger mt-2 text-center">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="dimensions text-center mt-3">
                                                    <strong>Dimensions:</strong> 360 &times; 155
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-fields">
                                                <label class="title">Gift Image:</label>
                                                <div class="upload" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box {{ empty($tour->gift_image) ? 'show' : '' }}"
                                                            data-upload-box>
                                                            <input type="file" name="gift_image"
                                                                data-error="Gift Image" id="gift_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i></div>
                                                            <label for="gift_image"
                                                                class="upload-box__btn themeBtn">Upload Image</label>
                                                        </div>
                                                        <div class="upload-box__img {{ !empty($tour->gift_image) ? 'show' : '' }}"
                                                            data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="{{ asset($tour->gift_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset($tour->gift_image ?? 'admin/assets/images/placeholder.png') }}"
                                                                    alt="{{ $tour->gift_image_alt_text }}"
                                                                    class="imgFluid" data-upload-preview>
                                                            </a>
                                                            <input type="text" name="gift_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="{{ $tour->gift_image_alt_text }}">
                                                        </div>
                                                    </div>
                                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                                        Please upload a valid image file
                                                    </div>
                                                    @error('gift_image')
                                                        <div class="text-danger mt-2 text-center">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="dimensions text-center mt-3">
                                                    <strong>Dimensions:</strong> 765 &times; 210
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Author Settings</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Author :</label>
                                        <select class="select2-select" name="tour[status][author_id]"
                                            data-error="Author">
                                            <option value="" selected>Select</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $tour->author_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tour[status][author_id]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Tour Featured</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                name="tour[status][is_featured]" id="is_featured" value="1"
                                                {{ $tour->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Enable featured
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-fields mt-3">
                                        <label class="title">Default State :</label>
                                        <select name="tour[status][featured_state]" class="field">
                                            <option value="" disabled
                                                {{ $tour->featured_state === null ? 'selected' : '' }}>Select</option>
                                            <option value="always"
                                                {{ $tour->featured_state === 'always' ? 'selected' : '' }}>Always
                                                Available</option>
                                            <option value="specific_dates"
                                                {{ $tour->featured_state === 'specific_dates' ? 'selected' : '' }}>Only
                                                available on specific Dates</option>
                                        </select>
                                        @error('tour[status][featured_state]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            @foreach ($attributes as $attribute)
                                @if (!$attribute->attributeItems->isEmpty())
                                    <div class="form-box">
                                        <div class="form-box__header">
                                            <div class="title">Attribute: {{ $attribute->name }}</div>
                                        </div>
                                        <div class="form-box__body">
                                            @foreach ($attribute->attributeItems as $index => $item)
                                                <div class="form-check mb-1">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="tour[status][attributes][{{ $attribute->id }}][]"
                                                        id="attribute-{{ $item->id }}-{{ $index }}"
                                                        value="{{ $item->id }}"
                                                        @if (
                                                            $tour->attributes->contains($attribute->id) &&
                                                                $item->tourAttributes &&
                                                                $item->tourAttributes->contains($attribute->id)) checked @endif>
                                                    <label class="form-check-label"
                                                        for="attribute-{{ $item->id }}-{{ $index }}">
                                                        {{ $item->item }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Ical</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Import url :</label>
                                        <input type="text" name="tour[status][ical_import_url]" class="field"
                                            placeholder="" value="{{ $tour->ical_import_url }}">
                                        @error('tour[status][ical_import_url]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Export url :</label>
                                        <input type="text" name="tour[status][ical_export_url]" class="field"
                                            placeholder="" value="{{ $tour->ical_export_url }}">
                                        @error('tour[status][ical_export_url]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'seo'" class="seo-options">
                            <x-seo-options :seo="$tour->seo ?? null" :resource="'tours'" :slug="$tour->slug" />
                        </div>
                        <button type="submit" class="themeBtn mt-4 ms-auto">Save Changes<i
                                class='bx bx-check'></i></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://choices-js.github.io/Choices/assets/styles/choices.min.css"
        crossorigin="anonymous" />
@endpush
@push('js')
    @php
        $itineraryPickupDropoff = json_decode($tour->itinerary_experience, true);
        $pickupDropoffData = [
            'pickup_dropoff_details' => $itineraryPickupDropoff['pickup_dropoff_details'] ?? [],
            'pickup_locations' => $itineraryPickupDropoff['pickup_locations'] ?? [],
            'dropoff_locations' => $itineraryPickupDropoff['dropoff_locations'] ?? [],
        ];
    @endphp
    <script>
        window.pickupDropoffData = {!! json_encode($pickupDropoffData) !!};

        function handlePickupDropoff(initData = window.pickupDropoffData || {}) {
            return {
                pickupLocations: initData.pickup_locations || [],
                dropoffLocations: initData.dropoff_locations || [],
                formData: {
                    pickup: initData.pickup_dropoff_details?.pickup || [],
                    dropoff: initData.pickup_dropoff_details?.dropoff || []
                },
                inheritFromPickup: initData.pickup_dropoff_details?.inheritFromPickup === 'on',

                syncDropoff() {
                    if (this.inheritFromPickup) {
                        this.formData.dropoff = JSON.parse(JSON.stringify(this.formData.pickup))
                        this.dropoffLocations = [...this.pickupLocations]
                    }
                },

                init() {
                    this.$watch('inheritFromPickup', value => {
                        if (value) {
                            this.syncDropoff()
                        } else {
                            this.formData.dropoff = []
                            this.dropoffLocations = []
                        }
                    })

                    this.$watch('formData.pickup', () => this.syncDropoff(), {
                        deep: true
                    })
                    this.$watch('pickupLocations', () => this.syncDropoff(), {
                        deep: true
                    })
                }
            }
        }

        function featuresManager() {
            return {
                features: @if ($tour->features)
                    @js(json_decode($tour->features))
                @else
                    [{
                        icon: '',
                        icon_color: '',
                        title: '',
                        content: ''
                    }]
                @endif ,
                addFeature() {
                    this.features.push({
                        icon: '',
                        icon_color: '',
                        title: '',
                        content: ''
                    });
                    this.$nextTick(() => {
                        document.querySelectorAll("[data-color-picker-container]").forEach(el => {
                            InitializeColorPickers(el);
                        });
                    });
                },
                removeFeature(index) {
                    this.features.splice(index, 1);
                }
            };
        }

        function repeaterFormForAddOns(addOns = []) {
            return {
                formData: {
                    addOns: addOns.length ? addOns : [{
                        heading: '',
                        tour_ids: []
                    }]
                },
                initChoices() {
                    this.$nextTick(() => {
                        document.querySelectorAll('.choices-select').forEach(el => {
                            if (el._choicesInstance) el._choicesInstance.destroy()
                            el._choicesInstance = new Choices(el, {
                                removeItemButton: true
                            })


                            const selectedValues = Array.from(el.querySelectorAll('option:checked')).map(
                                o => o.value)
                            selectedValues.forEach(val => {
                                el._choicesInstance.setChoiceByValue(val)
                            })
                        })
                    })
                },
                addAddOn() {
                    this.formData.addOns.push({
                        heading: '',
                        tour_ids: []
                    })
                    this.initChoices()
                },
                removeAddOn(index) {
                    this.formData.addOns.splice(index, 1)
                    this.initChoices()
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
    <script src="https://choices-js.github.io/Choices/assets/scripts/choices.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('admin/assets/js/tour-settings.js') }}"></script>
@endpush
