@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.create') }}
            <form action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data" id="validation-form">
                @csrf
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            <div class="permalink">
                                <div class="title">Permalink:</div>
                                <div class="title">
                                    <div class="full-url">{{ buildUrl(url('/'), 'tours/') }}</div>
                                    <input value="#" type="button" class="link permalink-input" data-field-id="slug">
                                    <input type="hidden" id="slug" name="tour[general][slug]">
                                </div>
                            </div>
                        </div>
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
                                                    value="{{ old('tour[general][title]') }}" placeholder=""
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
                                                <textarea class="editor" name="tour[general][content]" data-placeholder="content" data-error="Content"> {{ old('tour[general][content]') }} </textarea>
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
                                                    class="field" value="15" data-error="description_line_limit">
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
                                                    data-error="Category">
                                                    <option value="" disabled selected>Select Category</option>
                                                    @php
                                                        renderCategories($categories);
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
                                                    <span class="title mb-0">Badge icon: <a class="p-0 ps-2 nav-link"
                                                            href="//boxicons.com" target="_blank">boxicons</a></span>

                                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                                        data-disabled-text="Disabled">
                                                        <input class="form-check-input" data-toggle-switch type="checkbox"
                                                            id="enable-badge-section" value="1"
                                                            name="tour[badge][is_enabled]" checked>
                                                        <label class="form-check-label"
                                                            for="enable-badge-section">Enabled</label>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <input type="text" name="tour[badge][icon_class]" class="field"
                                                        value="{{ old('tour[badge][icon_class]', 'bx bx-badge-check') }}"
                                                        placeholder="" oninput="showIcon(this)">
                                                    <i class="bx bx-badge-check bx-md" data-preview-icon></i>
                                                </div>
                                                @error('tour[badge][icon_class]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12  mt-4">
                                            <div class="form-fields">
                                                <label class="title">Badge Name:</label>
                                                <input type="text" name="tour[badge][name]" class="field"
                                                    value="{{ old('tour[badge][name]') }}" placeholder="">
                                                @error('tour[badge][name]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Badge background Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get
                                                        Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="color-picker" data-color-picker></label>
                                                    <input id="color-picker" type="text"
                                                        name="tour[badge][background_color]" data-color-picker-input
                                                        value="#edab56" inputmode="text">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mt-4">
                                            <div class="form-fields">
                                                <div class="title d-flex align-items-center gap-2">
                                                    <div>Badge Icon Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get
                                                        Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="color-picker" data-color-picker></label>
                                                    <input id="color-picker" type="text"
                                                        name="tour[badge][icon_color]" data-color-picker-input
                                                        value="#000000" inputmode="text">
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
                                        <div class="col-md-12 mt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3">Include:</label>
                                                <div class="mb-4">
                                                    <label class="title">Title</label>
                                                    <input type="text" name="exclusions_inclusions_heading[inclusions]"
                                                        class="field">
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
                                                            <tr data-repeater-item>
                                                                <td>
                                                                    <input name="tour[general][inclusions][]"
                                                                        type="text" class="field">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        data-repeater-remove disabled>
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-fields">
                                                <label class="title title--sm mb-3">Exclude:</label>
                                                <div class="mb-4">
                                                    <label class="title">Title</label>
                                                    <input type="text" name="exclusions_inclusions_heading[exclusions]"
                                                        class="field">
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
                                                            <tr data-repeater-item>
                                                                <td>
                                                                    <input name="tour[general][exclusions][]"
                                                                        type="text" class="field">
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        data-repeater-remove disabled>
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-4">
                                            <div class="form-fields">
                                                <label
                                                    class=" d-flex align-items-center mb-3 justify-content-between"><span
                                                        class="title title--sm mb-0">Tour Information:</span>

                                                </label>
                                                <div x-data="{
                                                    formData: {
                                                        sections: []
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
                                                            <tr data-repeater-item>
                                                                <td>
                                                                    <textarea name="tour[general][faq][question][]" class="field"rows="2"></textarea>
                                                                </td>
                                                                <td>
                                                                    <textarea name="tour[general][faq][answer][]" class="field"rows="2"></textarea>
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        data-repeater-remove disabled>
                                                                        <i class='bx bxs-trash-alt'></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="button" class="themeBtn ms-auto"
                                                        data-repeater-create>Add
                                                        <i class="bx bx-plus"></i></button>
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
                                                            data-fancybox="gallery" class="themeBtn p-1"
                                                            title="Section Preivew"><i class='bx  bxs-show'></i></a>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="form-fields">
                                                <input type="hidden" name="tour[general][banner_type]" value="1">
                                                <div class="title">
                                                    <div>Banner Image :</div>
                                                </div>

                                                <div class="upload upload--banner" data-upload>
                                                    <div class="upload-box-wrapper">
                                                        <div class="upload-box show" data-upload-box>
                                                            <input type="file" name="banner_image"
                                                                data-error="Banner Image" id="banner_featured_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i>
                                                            </div>
                                                            <label for="banner_featured_image"
                                                                class="upload-box__btn themeBtn">Upload
                                                                Image</label>
                                                        </div>
                                                        <div class="upload-box__img" data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="#" class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                                    alt="Uploaded Image" class="imgFluid"
                                                                    data-upload-preview>
                                                            </a>
                                                            <input type="text" name="banner_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="Alt Text">
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
                                                    value="{{ old('tour[general][video_link]') }}">
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
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'location'" class="location-options">
                            <div class="form-box" x-data="{ locationType: 'normal_location' }">
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
                                                        {{ old('city_ids') == $city->id ? 'selected' : '' }}>
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
                                                class="field"
                                                value="{{ old('tour[location][normal_location][address]') }}"
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
                                                        data-fancybox="gallery" class="themeBtn p-1"
                                                        title="Section Preivew"><i class='bx  bxs-show'></i></a>
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
                                                    <tbody data-repeater-list>
                                                        <tr data-repeater-item>

                                                            <td class="w-25">
                                                                <input name="tour[location][normal_itinerary][days][]"
                                                                    type="text" class="field" placeholder="Day">
                                                                <br>
                                                                <input name="tour[location][normal_itinerary][title][]"
                                                                    type="text" class="field mt-3"
                                                                    placeholder="Title">
                                                            </td>
                                                            <td>
                                                                <textarea name="tour[location][normal_itinerary][description][]" class="field"rows="2"></textarea>
                                                            </td>
                                                            <td class="w-25">
                                                                <div class="upload upload--sm" data-upload>
                                                                    <div class="upload-box-wrapper">
                                                                        <div class="upload-box show" data-upload-box>

                                                                            <div class="upload-box__placeholder"><i
                                                                                    class='bx bxs-image'></i>
                                                                            </div>
                                                                            <label for="itinerary_featured_image"
                                                                                class="upload-box__btn themeBtn">Upload
                                                                                Image</label>
                                                                            <input type="file"
                                                                                name="tour[location][normal_itinerary][featured_image][]"
                                                                                id="itinerary_featured_image"
                                                                                class="upload-box__file d-none"
                                                                                accept="image/*" data-file-input>
                                                                        </div>
                                                                        <div class="upload-box__img" data-upload-img>
                                                                            <button type="button" class="delete-btn"
                                                                                data-delete-btn><i
                                                                                    class='bx bxs-trash-alt'></i></button>
                                                                            <a href="#" class="mask"
                                                                                data-fancybox="gallery">
                                                                                <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                                                    alt="Uploaded Image" class="imgFluid"
                                                                                    data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                                                                    data-upload-preview>
                                                                            </a>
                                                                            <input type="text"
                                                                                name="tour[location][normal_itinerary][featured_image_alt_text][]"
                                                                                class="field"
                                                                                placeholder="Enter alt text"
                                                                                value="Alt Text">
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
                                                    </tbody>
                                                </table>
                                                <button type="button" class="themeBtn ms-auto" data-repeater-create>Add
                                                    <i class="bx bx-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div x-show="locationType === 'itinerary_experience'">
                                        <div class="plan-itenirary">
                                            <div class="form-fields">
                                                <label class="d-flex align-items-center mb-3 justify-content-between">
                                                    <span class="title title--sm mb-0">Plan Itinerary
                                                        Experience:</span>
                                                    <span class="title d-flex align-items-center gap-1">
                                                        Section Preview:
                                                        <a href="{{ asset('admin/assets/images/itinerary-exp.png') }}"
                                                            data-fancybox="gallery" class="themeBtn p-1"
                                                            title="Section Preivew"><i class='bx  bxs-show'></i></a>
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
                                                    class="field" value="{{ old('itinerary_experience[map_iframe]') }}">
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
                                                    <div class="col-md-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title">Pickup Row Icon:
                                                                <a class="p-0 ps-2 nav-link" href="//boxicons.com"
                                                                    target="_blank">boxicons</a>
                                                            </label>
                                                            <div class="d-flex align-items-center gap-3">
                                                                <input type="text" x-model="pickupIconClass"
                                                                    name="itinerary_experience[pickup_dropoff_details][pickup_icon_class]"
                                                                    style="width: 42% !important;" class="field"
                                                                    placeholder="">
                                                                <i :class="`${pickupIconClass} bx-md`"></i>
                                                            </div>
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
                                                                            x-model.lazy="entry.city"
                                                                            :name="`itinerary_experience[pickup_dropoff_details][pickup][${index}][city]`">
                                                                            <option value="">Select Location</option>
                                                                            <template
                                                                                x-for="loc in pickupLocations.filter(l => l.trim() && !formData.pickup.some((entry, i) => i !== index && entry.city === l))"
                                                                                :key="loc">
                                                                                <option :value="loc"
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
                                                        <div class="form-fields d-flex align-items-center gap-4 mt-3">
                                                            <label class="title title--sm mb-0">Dropoff
                                                                Locations:</label>
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

                                                    <div class="col-md-12 mb-4" x-show="!inheritFromPickup">
                                                        <div class="form-fields">
                                                            <label class="title">Dropoff Row Icon:
                                                                <a class="p-0 ps-2 nav-link" href="//boxicons.com"
                                                                    target="_blank">boxicons</a>
                                                            </label>
                                                            <div class="d-flex align-items-center gap-3">
                                                                <input type="text" x-model="dropoffIconClass"
                                                                    name="itinerary_experience[pickup_dropoff_details][dropoff_icon_class]"
                                                                    style="width: 42% !important;" class="field"
                                                                    placeholder="">
                                                                <i :class="`${dropoffIconClass} bx-md`"></i>
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
                                                                            :name="`itinerary_experience[pickup_dropoff_details][dropoff][${index}][city]`">

                                                                            <option value="">Select Location</option>
                                                                            <template
                                                                                x-for="(loc, index) in dropoffLocations.filter(l => l.trim() && !formData.dropoff.some((loc, i) => i !== index && loc.city === l))"
                                                                                :key="loc">
                                                                                <option :value="loc"
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
                                                    <tbody id="itinerary-table-body" data-sortable-body></tbody>
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

                                            <div class="form-check d-none" id="add-stop-btn">
                                                <input class="form-check-input" type="checkbox"
                                                    name="itinerary_experience[enable_sub_stops]"
                                                    id="itinerary_experience_enabled_sub_stops" value="1">
                                                <label class="form-check-label"
                                                    for="itinerary_experience_enabled_sub_stops">Add
                                                    Sub Stops</label>
                                            </div>

                                            <div class="form-fields mt-4 d-none" id="itinerary_experience_sub_stops">
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
                                                            <tr data-repeater-item>
                                                                <td>
                                                                    <select
                                                                        name="itinerary_experience[stops][sub_stops][main_stop][]"
                                                                        class="field main-stop-dropdown">
                                                                        <option value="" selected>Select</option>
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
                                                    value="{{ old('tour[pricing][regular_price]') }}" data-error="Price">
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
                                                    value="{{ old('tour[pricing][sale_price]') }}"
                                                    data-error="Sale Price">
                                                @error('tour[pricing][sale_price]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 my-2">
                                            <hr>
                                        </div>
                                        <div class="col-12" x-data="{ personType: '0' }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Person Types:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="tour[pricing][is_person_type_enabled]"
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
                                                    <div x-data="{ tourType: 'normal' }">
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
                                                                        <tbody data-repeater-list>
                                                                            <tr data-repeater-item>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="tour[pricing][normal][person_type][]"
                                                                                        class="field"
                                                                                        placeholder="Eg: Adult">
                                                                                    <br>
                                                                                    <div class="mt-3">
                                                                                        <input type="text"
                                                                                            name="tour[pricing][normal][person_description][]"
                                                                                            class="field"
                                                                                            placeholder="Description">
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][normal][min_person][]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][normal][max_person][]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" step="0.01"
                                                                                        min="0"
                                                                                        name="tour[pricing][normal][price][]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                                        data-repeater-remove disabled>
                                                                                        <i class='bx bxs-trash-alt'></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
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
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
                                                                                    <input type="number" step="0.01"
                                                                                        min="0"
                                                                                        name="tour[pricing][private][car_price]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][private][min_person]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][private][max_person]"
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
                                                                        @endphp
                                                                        <tbody data-repeater-list>
                                                                            <tr data-repeater-item>
                                                                                <td>
                                                                                    <select
                                                                                        name="tour[pricing][water][time][]"
                                                                                        class="field"
                                                                                        data-error="Desert Activities Time">
                                                                                        <option value="">Select Time
                                                                                        </option>
                                                                                        @foreach ($waterMints as $waterMint)
                                                                                            <option
                                                                                                value="{{ $waterMint }}">

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
                                                                                        type="number" class="field"
                                                                                        placeholder="Price" step="0.01"
                                                                                        min="0"
                                                                                        data-error="Desert Activities Price">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                                        data-repeater-remove disabled>
                                                                                        <i class='bx bxs-trash-alt'></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
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
                                                                                        {{ $i == 10 ? 'selected' : '' }}>
                                                                                        {{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Discount (%) :</label>
                                                                            <input type="number"
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
                                                                                        {{ $i == 10 ? 'selected' : '' }}>
                                                                                        {{ $i }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-fields">
                                                                            <label class="title">Discount (%) :</label>
                                                                            <input type="number"
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
                                                                                <th class="text-end" scope="col">Remove
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody data-repeater-list>
                                                                            <tr data-repeater-item>
                                                                                <td>
                                                                                    <textarea rows="6" name="tour[pricing][promo][promo_title][]" class="field" placeholder="E.g., Adult"></textarea>
                                                                                </td>
                                                                                <td style="width: 35%"
                                                                                    calculate-promo-price>
                                                                                    <div>
                                                                                        <input
                                                                                            name="tour[pricing][promo][original_price][]"
                                                                                            type="number" class="field"
                                                                                            placeholder="Price"
                                                                                            step="0.01" min="0">
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
                                        <div class="col-12" x-data="{ extraPrice: '0' }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Extra Price:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="tour[pricing][is_extra_price_enabled]"
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
                                                                        <tbody data-repeater-list>
                                                                            <tr data-repeater-item>
                                                                                <td>
                                                                                    <input type="text"
                                                                                        name="tour[pricing][extra_price][0][name]"
                                                                                        class="field"
                                                                                        placeholder="Extra Price Name">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" step="0.01"
                                                                                        min="0"
                                                                                        name="tour[pricing][extra_price][0][price]"
                                                                                        class="field">
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                                        data-repeater-remove disabled>
                                                                                        <i class='bx bxs-trash-alt'></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
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
                                                                        <tbody data-repeater-list>
                                                                            <tr data-repeater-item>
                                                                                <td>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <input type="number"
                                                                                                min="0"
                                                                                                name="tour[pricing][discount][people_from][]"
                                                                                                class="field"
                                                                                                placeholder="from">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <input type="number"
                                                                                                min="0"
                                                                                                name="tour[pricing][discount][people_to][]"
                                                                                                class="field"
                                                                                                placeholder="to">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min="0"
                                                                                        name="tour[pricing][discount][discount][]"
                                                                                        class="field" placeholder="">
                                                                                </td>
                                                                                <td>
                                                                                    <select class="field"
                                                                                        name="tour[pricing][discount][type][]">
                                                                                        <option value="" selected>
                                                                                            Select</option>
                                                                                        <option value="fixed">Fixed
                                                                                        </option>
                                                                                        <option value="percent">Percent
                                                                                        </option>
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                                        data-repeater-remove disabled>
                                                                                        <i class='bx bxs-trash-alt'></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
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
                                        <div class="col-12 my-2" x-data="{ serviceFee: '0' }">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <div class="form-fields">
                                                        <div class="title title--sm mb-0">Service fee:</div>
                                                    </div>
                                                    <div class="form-fields">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="enebled_service_fee" value="1"
                                                                name="tour[pricing][enabled_custom_service_fee]"
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
                                                            value="{{ old('tour[pricing][service_fee_price]') }}"
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
                                                        <input class="form-check-input" data-toggle-switch checked
                                                            type="checkbox" id="enable-section" checked value="1"
                                                            name="tour[pricing][show_phone]">
                                                        <label class="form-check-label"
                                                            for="enable-section">Enabled</label>
                                                    </div>
                                                </div>
                                                <div data-flag-input-wrapper>
                                                    <input type="hidden" name="tour[pricing][phone_dial_code]"
                                                        data-flag-input-dial-code value="971">
                                                    <input type="hidden" name="tour[pricing][phone_country_code]"
                                                        data-flag-input-country-code value="ae">
                                                    <input type="text" name="tour[pricing][phone_number]"
                                                        class="field flag-input" data-flag-input
                                                        value="{{ old('tour[pricing][phone_number]') }}"
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
                                    <div class="col-12" x-data="{ fixedDate: '0' }">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="form-fields">
                                                    <div class="title">Fixed dates:</div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="tour[availability][is_fixed_date]" id="fixed_date"
                                                            value="0" x-model="fixedDate"
                                                            @change="fixedDate = fixedDate ? 1 : 0">
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
                                                            <label class="title">Start Date
                                                                :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date"
                                                                name="tour[availability][start_date]"
                                                                autocomplete="off">
                                                            @error('availability[start_date]')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-fields">
                                                            <label class="title">End Date
                                                                :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date"
                                                                name="tour[availability][end_date]" autocomplete="off">
                                                            @error('availability[end_date]')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-fields">
                                                            <label class="title">Last Booking Date
                                                                :</label>
                                                            <input readonly type="text" class="field date-picker"
                                                                placeholder="Select a date" autocomplete="off"
                                                                name="tour[availability][last_booking_date]">
                                                            @error('availability[last_booking_date]')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3" x-data="{ openHours: '0' }">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="form-fields">
                                                    <div class="title">Open Hours:</div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="tour[availability][is_open_hours]" id="openHours"
                                                            value="0" x-model="openHours"
                                                            @change="openHours = openHours ? 1 : 0">
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

                                                                @endphp
                                                                @for ($i = 0; $i < count($days); $i++)
                                                                    @php
                                                                        $day = $days[$i];
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    name="tour[availability][open_hours][{{ $i }}][enabled]"
                                                                                    id="{{ $day }}"
                                                                                    value="1">
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
                                                                            <select
                                                                                name="tour[availability][open_hours][{{ $i }}][open_time]"
                                                                                class="field">
                                                                                <option value="">Select Time
                                                                                </option>
                                                                                @foreach ($timeSlots as $slot)
                                                                                    <option value="{{ $slot }}">
                                                                                        {{ date('H:i', strtotime($slot)) }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select
                                                                                name="tour[availability][open_hours][{{ $i }}][close_time]"
                                                                                class="field">
                                                                                <option value="">Select Time
                                                                                </option>
                                                                                @foreach ($timeSlots as $slot)
                                                                                    <option value="{{ $slot }}">
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
                                        <div x-data="repeaterFormForAddOns()" x-init="initChoices()">
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
                                                                            type="text" class="field">
                                                                    </div>
                                                                    <div class="form-fields">
                                                                        <label class="title text-dark">Select
                                                                            tours</label>
                                                                        <select :name="`addOns[${index}][tour_ids][]`"
                                                                            multiple class="choices-select">
                                                                            @foreach ($tours as $tour)
                                                                                <option value="{{ $tour->id }}">
                                                                                    {{ $tour->title }}
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
                                            id="publish" checked value="publish">
                                        <label class="form-check-label" for="publish">
                                            Publish
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="tour[status][status]"
                                            id="draft" value="draft">
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
                                                        <div class="upload-box show" data-upload-box>
                                                            <input type="file" name="featured_image"
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
                                                        <div class="upload-box__img" data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="#" class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                                    alt="Uploaded Image" class="imgFluid"
                                                                    data-upload-preview>
                                                            </a>
                                                            <input type="text" name="featured_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="Alt Text">
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
                                                        <div class="upload-box show" data-upload-box>
                                                            <input type="file" name="promotional_image"
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
                                                        <div class="upload-box__img" data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="#" class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                                    alt="Uploaded Image" class="imgFluid"
                                                                    data-upload-preview>
                                                            </a>
                                                            <input type="text" name="promotional_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="Alt Text">
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
                                                        <div class="upload-box show" data-upload-box>
                                                            <input type="file" name="gift_image"
                                                                data-error="Gift Image" id="gift_image"
                                                                class="upload-box__file d-none" accept="image/*"
                                                                data-file-input>
                                                            <div class="upload-box__placeholder"><i
                                                                    class='bx bxs-image'></i></div>
                                                            <label for="gift_image"
                                                                class="upload-box__btn themeBtn">Upload Image</label>
                                                        </div>
                                                        <div class="upload-box__img" data-upload-img>
                                                            <button type="button" class="delete-btn" data-delete-btn><i
                                                                    class='bx bxs-trash-alt'></i></button>
                                                            <a href="#" class="mask" data-fancybox="gallery">
                                                                <img src="{{ asset('admin/assets/images/loading.webp') }}"
                                                                    alt="Uploaded Image" class="imgFluid"
                                                                    data-upload-preview>
                                                            </a>
                                                            <input type="text" name="gift_image_alt_text"
                                                                class="field" placeholder="Enter alt text"
                                                                value="Alt Text">
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
                                            @foreach ($users as $users)
                                                <option value="{{ $users->id }}"
                                                    {{ old('tour[status][author_id]') == $users->id ? 'selected' : '' }}>
                                                    {{ $users->full_name }}
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
                                                name="tour[status][is_featured]" id="is_featured" value="1">
                                            <label class="form-check-label" for="is_featured">
                                                Enable featured
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-fields mt-3">
                                        <label class="title">Default State :</label>
                                        <select name="tour[status][featured_state]" class="field">

                                            <option value="" selected disabled>Select</option>
                                            <option value="always">Always Available</option>
                                            <option value="specific_dates">Only available on specific Dates</option>
                                        </select>
                                        @error('tour[status][featured_state]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (!$attributes->isEmpty())
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
                                                            value="{{ $item->id }}">
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
                            @endif

                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Ical</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Import url :</label>
                                        <input type="text" name="tour[status][ical_import_url]" class="field"
                                            placeholder="">
                                        @error('tour[status][ical_import_url]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Export url :</label>
                                        <input type="text" name="tour[status][ical_export_url]" class="field"
                                            placeholder="">
                                        @error('tour[status][ical_export_url]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-show="optionTab === 'seo'" class="seo-options">
                            <x-seo-options :seo="$seo ?? null" :resource="'tours'" />
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
    <script>
        function featuresManager() {
            return {
                features: [{
                    icon: '',
                    icon_color: '',
                    title: '',
                    content: ''
                }],
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

        function repeaterFormForAddOns() {
            return {
                formData: {
                    addOns: [{
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


        function handlePickupDropoff() {
            return {
                pickupLocations: [],
                dropoffLocations: [],
                formData: {
                    pickup: [],
                    dropoff: []
                },
                pickupIconClass: 'bx bx-refresh',
                dropoffIconClass: 'bx bx-refresh',
                inheritFromPickup: true,

                syncDropoff() {
                    if (this.inheritFromPickup) {
                        this.formData.dropoff = JSON.parse(JSON.stringify(this.formData.pickup))
                        this.dropoffLocations = [...this.pickupLocations]
                        this.dropoffIconClass = this.pickupIconClass
                    }
                },

                init() {
                    this.$watch('inheritFromPickup', value => {
                        if (value) {
                            this.syncDropoff()
                        } else {
                            this.formData.dropoff = []
                            this.dropoffLocations = []
                            this.dropoffIconClass = 'bx bx-refresh'
                        }
                    })

                    this.$watch('formData.pickup', () => this.syncDropoff(), {
                        deep: true
                    })
                    this.$watch('pickupLocations', () => this.syncDropoff(), {
                        deep: true
                    })
                    this.$watch('pickupIconClass', () => this.syncDropoff())
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
