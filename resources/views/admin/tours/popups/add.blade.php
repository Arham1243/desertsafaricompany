    @extends('admin.layouts.main')
    @section('content')
        <div class="col-md-12" x-data="{ type: '{{ old('type') }}' }" x-init="$watch('type', () => {
            setTimeout(() => {
                tinymce.remove('.editor');
                initializeEditors($el.querySelector('form'));
            }, 0);
        })">

            <div class="dashboard-content">
                {{ Breadcrumbs::render('admin.tour-popups.create') }}

                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">{{ $title ?? 'Add Detail Popup' }}</h3>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.tour-popups.store') }}" method="POST" enctype="multipart/form-data"
                    id="validation-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-wrapper">
                                <div class="form-box">
                                    <div class="form-box__header d-flex align-items-center justify-content-between">
                                        <div class="title">Popup Content</div>
                                        <span class="title d-flex align-items-center gap-1">Section Preview:
                                            <template x-if="type === 'policy'">
                                                <a href="{{ asset('admin/assets/images/cancellation-policy-popup.png') }}"
                                                    data-fancybox="gallery" class="themeBtn p-1" title="Preview"><i
                                                        class='bx bxs-show'></i></a>
                                            </template>
                                            <template x-if="type === 'info'">
                                                <a href="{{ asset('admin/assets/images/reserve-now-and-pay-later-popup.png') }}"
                                                    data-fancybox="gallery" class="themeBtn p-1" title="Preview"><i
                                                        class='bx bxs-show'></i></a>
                                            </template>
                                        </span>
                                    </div>

                                    <div class="form-box__body">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title" style="text-transform: initial">Popup Trigger Text
                                                        (e.g. Free cancellation, Reserve Now and Pay Later) <span
                                                            class="text-danger">*</span> :</label>
                                                    <input type="text" name="popup_trigger_text" class="field"
                                                        value="{{ old('popup_trigger_text') }}" data-error="Trigger Text"
                                                        data-required>
                                                    @error('popup_trigger_text')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">User-Facing Label <span
                                                            class="text-danger">*</span>
                                                        :</label>
                                                    <input type="text" name="user_showing_text" class="field"
                                                        value="{{ old('user_showing_text') }}" data-error="User Label"
                                                        data-required>
                                                    @error('user_showing_text')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Type <span class="text-danger">*</span>:</label>
                                                    <select name="type" data-error="Type" data-required class="field"
                                                        x-model="type">
                                                        <option value="" disabled
                                                            {{ old('type') ? '' : 'selected' }}>Select Type</option>
                                                        <option value="info">Informational</option>
                                                        <option value="policy">Policy / Terms</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Main Heading <span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="main_heading" class="field"
                                                        value="{{ old('main_heading') }}" data-error="Main Heading"
                                                        data-required>
                                                    @error('main_heading')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <template x-if="type === 'policy'">
                                                <div class="row w-100">
                                                    <div class="col-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title">Sub Heading :</label>
                                                            <input type="text" name="content[sub_heading]" class="field"
                                                                value="{{ old('content.sub_heading') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title" style="text-transform: initial">First
                                                                Condition (e.g. "24 hours prior"):</label>
                                                            <input type="text" name="content[condition_1]" class="field"
                                                                value="{{ old('content.condition_1') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title" style="text-transform: initial">First
                                                                Outcome (e.g. "100% refund"):</label>
                                                            <input type="text" name="content[outcome_1]" class="field"
                                                                value="{{ old('content.outcome_1') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title" style="text-transform: initial">Second
                                                                Condition (e.g. "Within 24 hours"):</label>
                                                            <input type="text" name="content[condition_2]"
                                                                class="field" value="{{ old('content.condition_2') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-12 mb-4">
                                                        <div class="form-fields">
                                                            <label class="title" style="text-transform: initial">Second
                                                                Outcome (e.g. "No refund"):</label>
                                                            <input type="text" name="content[outcome_2]"
                                                                class="field" value="{{ old('content.outcome_2') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Content :</label>
                                                    <textarea class="editor" name="content[editor_content]" data-placeholder="content">
                                                        {!! old('content.editor_content') !!}
                                                    </textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="seo-wrapper">
                                <div class="form-box">
                                    <div class="form-box__header">
                                        <div class="title">Publish</div>
                                    </div>
                                    <div class="form-box__body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="active"
                                                value="active"
                                                {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" name="status" id="inactive"
                                                value="inactive" {{ old('status') === 'inactive' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                        <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
