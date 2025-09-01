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
                    <form action="{{ route('admin.settings.update', ['resource' => 'online_chat']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="title">Live Chat Integration</label>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="form-fields mb-4">
                                    <label class="title mb-0">Paste your chat widget script below</label>
                                    <small class="text-muted d-block mb-3">
                                        This code will be injected right before the closing <code>&lt;/body&gt;</code> tag.
                                    </small>
                                    <textarea code-editor name="online_chat" class="field code-editor" rows="12">{{ $settings->get('online_chat') }}</textarea>
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
