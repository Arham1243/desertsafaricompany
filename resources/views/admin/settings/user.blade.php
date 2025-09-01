@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.settings.index') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Settings</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'user']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_registration_enabled') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Disable Registration</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_registration_enabled" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="is_registration_enabled"
                                            x-model="enabled">
                                        <label class="form-check-label" for="is_registration_enabled"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_email_verification_enabled') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Email Verification</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_email_verification_enabled" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="is_email_verification_enabled"
                                            x-model="enabled">
                                        <label class="form-check-label" for="is_email_verification_enabled"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">reCapcha Options</div>
                            </div>
                            <div class="form-box__body">

                                <div class="row" x-data="{ googleEnabled: {{ $settings->get('is_google_recaptcha_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="is_google_recaptcha_enabled"
                                                    :value="googleEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">Google ReCaptcha:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="is_google_recaptcha_enabled_switch" value="1"
                                                        name="is_google_recaptcha_enabled" x-model="googleEnabled">
                                                    <label class="form-check-label"
                                                        for="is_google_recaptcha_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="googleEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Site Key</label>
                                            <input type="text" name="re_captcha_site_key"
                                                value="{{ $settings->get('re_captcha_site_key') ?? env('RE_CAPTCHA_SITE_KEY') }}"
                                                class="field">
                                            <a class="custom-link mt-2" href="https://www.google.com/recaptcha/admin">Learn
                                                how to get Site key</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button style=" position: sticky; bottom: 1rem; " class="themeBtn ms-auto ">Save Changes <i
                                class="bx bx-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/theme/material.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/htmlmixed/htmlmixed.min.js"></script>
@endpush

@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
    <script>
        document.querySelectorAll('[code-editor]').forEach(el => {
            const mode = el.getAttribute('data-mode') || 'javascript';
            CodeMirror.fromTextArea(el, {
                mode: mode,
                theme: 'material',
                lineNumbers: true,
                tabSize: 100,
                indentWithTabs: true,
                lineWrapping: true,
                styleActiveLine: true,
                matchBrackets: true
            });
        });
    </script>
@endpush
