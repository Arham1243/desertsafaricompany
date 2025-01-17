@php
    $sectionContent = $pageSection ? json_decode($pageSection->content) : null;
@endphp
<div class="row">
    <div class="col-lg-12 pt-3 pb-4">
        <div class="form-fields">
            <div class="d-flex align-items-center gap-3 mb-3">
                <label class="title title--sm mb-0">Title:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-fields">
                    <label class="title">Text <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[title]" class="field" placeholder="" data-error="Title"
                        value="{{ $sectionContent->title ?? '' }}" maxlength="39">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>
                            Text Color <span class="text-danger">*</span>:
                        </div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                            Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[title_text_color]" data-color-picker-input
                            value="{{ $sectionContent->title_text_color ?? '#000000' }}" inputmode="text" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-lg-12 pt-3 pb-4">
        <div class="form-fields">
            <div class="d-flex align-items-center gap-3 mb-3">
                <label class="title title--sm mb-0">Description:</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="form-fields">
                    <label class="title">Text <span class="text-danger">*</span> :</label>
                    <textarea name="content[description]" rows="3" class="field" maxlength="128">{{ $sectionContent->description ?? '' }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>
                            Text Color <span class="text-danger">*</span>:
                        </div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                            Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[description_text_color]"
                            data-color-picker-input value="{{ $sectionContent->description_text_color ?? '#000000' }}"
                            inputmode="text" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-lg-12 mb-4 pt-3">
        <div class="form-fields">
            <label class="title title--sm mb-3">QR Code:</label>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-fields">
                    <label class="title">QR Code Image <span class="text-danger">*</span>:</label>
                    <div class="upload upload--sm mx-0" data-upload>
                        <div class="upload-box-wrapper">
                            <div class="upload-box {{ empty($sectionContent->qr_code_image) ? 'show' : '' }}"
                                data-upload-box>
                                <input type="file" name="content[qr_code_image]" data-error="Feature Image"
                                    id="qr_code_image" class="upload-box__file d-none" accept="image/*"
                                    data-file-input />
                                <div class="upload-box__placeholder">
                                    <i class="bx bxs-image"></i>
                                </div>
                                <label for="qr_code_image" class="upload-box__btn themeBtn">Upload
                                    Image</label>
                            </div>
                            <div class="upload-box__img {{ !empty($sectionContent->qr_code_image) ? 'show' : '' }}"
                                data-upload-img>
                                <button type="button" class="delete-btn" data-delete-btn="">
                                    <i class="bx bxs-edit-alt"></i>
                                </button>
                                <a href="{{ asset($sectionContent->qr_code_image ?? 'admin/assets/images/loading.webp') }}"
                                    class="mask" data-fancybox="gallery">
                                    <img src="{{ asset($sectionContent->qr_code_image ?? 'admin/assets/images/loading.webp') }}"
                                        alt="Uploaded Image" class="imgFluid"
                                        data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                        data-upload-preview="" />
                                </a>
                                <input type="text" name="content[qr_code_image_alt_text]" class="field"
                                    placeholder="Enter alt text"
                                    value="{{ $sectionContent->qr_code_image_alt_text ?? 'Alt Text' }}">
                            </div>
                        </div>
                        <div data-error-message class="text-danger mt-2 d-none text-center">
                            Please upload a valid image file
                        </div>
                        <div class="dimensions text-center mt-3">
                            <strong>Dimensions:</strong> 180 &times; 180
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-lg-12 mb-4 pt-3">
        <div class="form-fields">
            <label class="title title--sm mb-3">Input Field:</label>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-fields">
                    <label class="title">Label Text <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[label_title]" class="field" placeholder=""
                        data-error="label_Title" value="{{ $sectionContent->label_title ?? '' }}" maxlength="39" />
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>Label Text Color <span class="text-danger">*</span>:</div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[label_text_color]"
                            data-color-picker-input value="{{ $sectionContent->label_text_color ?? '#000000' }}"
                            inputmode="text" />
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="form-fields">
                    <label class="title">Button Text <span class="text-danger">*</span> :</label>
                    <input type="text" value="{{ $sectionContent->btn_text ?? '' }}" name="content[btn_text]"
                        class="field" placeholder="" data-error="Button Text" maxlength="8">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>
                            Button Background Color <span class="text-danger">*</span>:
                        </div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                            Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[btn_background_color]"
                            data-color-picker-input value="{{ $sectionContent->btn_background_color ?? '#1c4d99' }}"
                            data-error="background Color" inputmode="text" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>
                            Button Text Color <span class="text-danger">*</span>:
                        </div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                            Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[btn_text_color]"
                            data-color-picker-input value="{{ $sectionContent->btn_text_color ?? '#ffffff' }}"
                            data-error="background Color" inputmode="text" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-lg-12 mb-4 pt-3">
        <div class="form-fields">
            <label class="title title--sm mb-3">Download App:</label>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-fields">
                    <label class="title">Text <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[download_title]" class="field" placeholder=""
                        data-error="label_Title" value="{{ $sectionContent->download_title ?? '' }}"
                        maxlength="17" />
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="form-fields">
                    <div class="title d-flex align-items-center gap-2">
                        <div>Text Color <span class="text-danger">*</span>:</div>
                        <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color Codes</a>
                    </div>
                    <div class="field color-picker" data-color-picker-container>
                        <label for="color-picker" data-color-picker></label>
                        <input id="color-picker" type="text" name="content[download_title_text_color]"
                            data-color-picker-input
                            value="{{ $sectionContent->download_title_text_color ?? '#000000' }}" inputmode="text" />
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="form-fields">
                    <label class="title">App store link <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[app_store_link]" class="field" placeholder=""
                        data-error="app_store_link" value="{{ $sectionContent->app_store_link ?? '' }}">
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="form-fields">
                    <label class="title">play store link <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[play_store_link]" class="field" placeholder=""
                        data-error="play_store_link" value="{{ $sectionContent->play_store_link ?? '' }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-fields">
                    <label class="title">Huawei link <span class="text-danger">*</span> :</label>
                    <input type="text" name="content[huawei_link]" class="field" placeholder=""
                        data-error="huawei_link" value="{{ $sectionContent->huawei_link ?? '' }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <hr>
    </div>
    <div class="col-lg-12 mb-4 pt-3">
        <div class="form-fields">
            <label class="title title--sm mb-3">Background Style:</label>
            <div x-data="{ background_type: '{{ isset($sectionContent->background_type) ? $sectionContent->background_type : 'background_image' }}' }">
                <div class="d-flex align-items-center gap-5 px-4">
                    <div class="form-check p-0">
                        <input class="form-check-input" type="radio" name="content[background_type]"
                            id="nomral" x-model="background_type" name="content[background_type]"
                            value="background_image" checked />
                        <label class="form-check-label" for="nomral">Background Image</label>
                    </div>
                    <div class="form-check p-0">
                        <input class="form-check-input" type="radio" name="content[background_type]"
                            id="slider_carousel" x-model="background_type" name="content[background_type]"
                            value="background_color" />
                        <label class="form-check-label" for="slider_carousel">Background Color</label>
                    </div>
                </div>
                <div x-show="background_type === 'background_image'">
                    <div class="row pt-4">
                        <div class="col-md-4 mb-4">
                            <div class="form-fields">
                                <label class="title">Background Image <span class="text-danger">*</span>:</label>
                                <div class="upload upload--sm mx-0" data-upload>
                                    <div class="upload-box-wrapper">
                                        <div class="upload-box {{ empty($sectionContent->background_image) ? 'show' : '' }}"
                                            data-upload-box>
                                            <input type="file" name="content[background_image]"
                                                data-error="Feature Image" id="background_image"
                                                class="upload-box__file d-none" accept="image/*" data-file-input />
                                            <div class="upload-box__placeholder">
                                                <i class="bx bxs-image"></i>
                                            </div>
                                            <label for="background_image" class="upload-box__btn themeBtn">Upload
                                                Image</label>
                                        </div>
                                        <div class="upload-box__img {{ !empty($sectionContent->background_image) ? 'show' : '' }}"
                                            data-upload-img>
                                            <button type="button" class="delete-btn" data-delete-btn="">
                                                <i class="bx bxs-edit-alt"></i>
                                            </button>
                                            <a href="{{ asset($sectionContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                class="mask" data-fancybox="gallery">
                                                <img src="{{ asset($sectionContent->background_image ?? 'admin/assets/images/loading.webp') }}"
                                                    alt="Uploaded Image" class="imgFluid"
                                                    data-placeholder="{{ asset('admin/assets/images/loading.webp') }}"
                                                    data-upload-preview="" />
                                            </a>
                                        </div>
                                    </div>
                                    <div data-error-message class="text-danger mt-2 d-none text-center">
                                        Please upload a valid image file
                                    </div>
                                    <div class="dimensions text-center mt-3">
                                        <strong>Dimensions:</strong> 1350 &times; 350
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="background_type === 'background_color'">
                    <div class="row pt-4">
                        <div class="col-md-12">
                            <div class="form-fields">
                                <div class="title d-flex align-items-center gap-2">
                                    <div>
                                        Select Background Color <span class="text-danger">*</span>:
                                    </div>
                                    <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get Color
                                        Codes</a>
                                </div>
                                <div class="field color-picker" data-color-picker-container>
                                    <label for="color-picker" data-color-picker></label>
                                    <input id="color-picker" type="text" name="content[background_color]"
                                        data-color-picker-input value="{{ $sectionContent->background_color ?? '' }}"
                                        placeholder="#000000" data-error="background Color" inputmode="text" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
