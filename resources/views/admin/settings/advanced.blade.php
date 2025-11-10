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
                    <form action="{{ route('admin.settings.update', ['resource' => 'advanced']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Social Login</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row" x-data="{ googleEnabled: {{ $settings->get('is_google_login_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="is_google_login_enabled"
                                                    :value="googleEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">google:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="is_google_login_enabled_switch" value="1"
                                                        name="is_google_login_enabled" x-model="googleEnabled">
                                                    <label class="form-check-label"
                                                        for="is_google_login_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="googleEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Google Client ID</label>
                                            <input type="text" name="google_client_id"
                                                value="{{ $settings->get('google_client_id') ?? env('GOOGLE_CLIENT_ID') }}"
                                                class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="googleEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">Google Client Secret</label>
                                            <input type="text" name="google_client_secret"
                                                value="{{ $settings->get('google_client_secret') ?? env('GOOGLE_CLIENT_SECRET') }}"
                                                class="field">
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5">
                                <div class="row" x-data="{ facebookEnabled: {{ $settings->get('is_facebook_login_enabled') ? 'true' : 'false' }} }">
                                    <div class="col-12 mb-2">
                                        <div class="form-fields d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <input type="hidden" name="is_facebook_login_enabled"
                                                    :value="facebookEnabled ? 1 : 0">
                                                <div class="title title--sm mb-0">facebook:</div>
                                                <div class="form-check form-switch" data-enabled-text="Enabled"
                                                    data-disabled-text="Disabled">
                                                    <input data-toggle-switch class="form-check-input" type="checkbox"
                                                        id="is_facebook_login_enabled_switch" value="1"
                                                        name="is_facebook_login_enabled" x-model="facebookEnabled">
                                                    <label class="form-check-label"
                                                        for="is_facebook_login_enabled_switch">Enabled</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="facebookEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">facebook Client ID</label>
                                            <input type="text" name="facebook_client_id"
                                                value="{{ $settings->get('facebook_client_id') ?? env('FACEBOOK_CLIENT_ID') }}"
                                                class="field">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12" x-show="facebookEnabled" x-transition>
                                        <div class="form-fields">
                                            <label class="title text-dark">facebook Client Secret</label>
                                            <input type="text" name="facebook_client_secret"
                                                value="{{ $settings->get('facebook_client_secret') ?? env('FACEBOOK_CLIENT_SECRET') }}"
                                                class="field">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_enabled_cookie_bar') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Cookie Consent Bar Settings</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_enabled_cookie_bar" :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox" id="enable-cookie-bar"
                                            x-model="enabled">
                                        <label class="form-check-label" for="enable-cookie-bar"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-box__body" x-show="enabled" x-transition>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm">Consent Text</label>
                                            <span class=" mb-3 small text-muted d-inline-flex align-items-center gap-2">
                                                <span>To add a link:</span>
                                                <code class="text-nowrap text-lowercase">&lt;a
                                                    href="//google.com"
                                                    target="_blank"&gt;Text&lt;/a&gt;</code>
                                                <button class="themeBtn copy-btn py-1 px-2" type="button"
                                                    text-to-copy='&lt;a href="//google.com" target="_blank"&gt;Text&lt;/a&gt;'>
                                                    Copy
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title text-dark mb-2">Text</label>
                                            <textarea rows="7" type="text" name="cookie_bar_text" class="field"
                                                placeholder="Enter cookie consent text">{{ $settings->get('cookie_bar_text') ?? 'We use cookies to improve your experience. You can choose to accept all or reject non-essential cookies.' }}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-fields">
                                                    <div class="text-dark title d-flex align-items-center gap-2">
                                                        <div>Background Color:</div>
                                                        <a class="p-0 nav-link" href="//html-color-codes.info"
                                                            target="_blank">
                                                            Get Color Codes
                                                        </a>
                                                    </div>
                                                    <div class="field color-picker" data-color-picker-container>
                                                        <label for="cookie-bar-bg-color" data-color-picker></label>
                                                        <input id="cookie-bar-bg-color" type="text"
                                                            data-color-picker-input name="cookie_bar_bg_color"
                                                            value="{{ $settings->get('cookie_bar_bg_color') ?? '#ffffff' }}"
                                                            inputmode="text">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-fields">
                                                    <div class="text-dark title d-flex align-items-center gap-2">
                                                        <div>Text Color:</div>
                                                        <a class="p-0 nav-link" href="//html-color-codes.info"
                                                            target="_blank">Get
                                                            Color
                                                            Codes</a>
                                                    </div>
                                                    <div class="field color-picker" data-color-picker-container>
                                                        <label for="cookie-bar-text-color" data-color-picker></label>
                                                        <input id="cookie-bar-text-color" type="text"
                                                            data-color-picker-input name="cookie_bar_text_color"
                                                            value="{{ $settings->get('cookie_bar_text_color') ?? '#333333' }}"
                                                            inputmode="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-5">

                                        <div class="col-12">
                                            <div class="form-fields">
                                                <label class="title title--sm">Accept Button</label>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="text-dark title mb-2">Button Text</label>
                                                <input type="text" name="cookie_bar_accept_text" class="field"
                                                    value="{{ $settings->get('cookie_bar_accept_text') ?? 'Accept All' }}"
                                                    placeholder="Enter Accept button text">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Background:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="cookie-bar-accept-bg" data-color-picker></label>
                                                    <input id="cookie-bar-accept-bg" type="text"
                                                        data-color-picker-input name="cookie_bar_accept_bg_color"
                                                        value="{{ $settings->get('cookie_bar_accept_bg_color') ?? '#1c4d99' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Text Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="cookie-bar-accept-text" data-color-picker></label>
                                                    <input id="cookie-bar-accept-text" type="text"
                                                        data-color-picker-input name="cookie_bar_accept_text_color"
                                                        value="{{ $settings->get('cookie_bar_accept_text_color') ?? '#ffffff' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-5">

                                        <div class="col-12">
                                            <div class="form-fields">
                                                <label class="title title--sm">Reject Button</label>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="text-dark title mb-2">Button Text</label>
                                                <input type="text" name="cookie_bar_reject_text" class="field"
                                                    value="{{ $settings->get('cookie_bar_reject_text') ?? 'Reject' }}"
                                                    placeholder="Enter Reject button text">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Background:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="cookie-bar-reject-bg" data-color-picker></label>
                                                    <input id="cookie-bar-reject-bg" type="text"
                                                        data-color-picker-input name="cookie_bar_reject_bg_color"
                                                        value="{{ $settings->get('cookie_bar_reject_bg_color') ?? '#ffffff' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-fields">
                                                <div class="text-dark title d-flex align-items-center gap-2">
                                                    <div>Text Color:</div>
                                                    <a class="p-0 nav-link" href="//html-color-codes.info"
                                                        target="_blank">Get Color
                                                        Codes</a>
                                                </div>
                                                <div class="field color-picker" data-color-picker-container>
                                                    <label for="cookie-bar-reject-text" data-color-picker></label>
                                                    <input id="cookie-bar-reject-text" type="text"
                                                        data-color-picker-input name="cookie_bar_reject_text_color"
                                                        value="{{ $settings->get('cookie_bar_reject_text_color') ?? '#1c4d99' }}"
                                                        inputmode="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="title">Global Script Tags</div>
                                    </div>
                                </div>

                                <div class="form-box__body">
                                    <div class="form-fields mb-4">
                                        <label class="title mb-0">
                                            Header Scripts
                                        </label>
                                        <small class="text-muted d-block mb-3">
                                            Scripts added here will appear inside the <code>&lt;head&gt;</code>
                                        </small>
                                        <textarea code-editor name="header_scripts" class="field" rows="12">{{ $settings->get('header_scripts') }}</textarea>
                                    </div>

                                    <div class="form-fields">
                                        <label class="title mb-0">
                                            Footer Scripts
                                        </label>
                                        <small class="text-muted d-block mb-3">
                                            Scripts added here will appear before the closing <code>&lt;/body&gt;</code> tag
                                        </small>
                                        <textarea code-editor name="footer_scripts" class="field" rows="12">{{ $settings->get('footer_scripts') }}</textarea>
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

        document.addEventListener('click', e => {
            if (e.target.matches('.copy-btn')) {
                const text = e.target.getAttribute('text-to-copy')
                if (text) navigator.clipboard.writeText(text)
            }
        })
    </script>
@endpush
