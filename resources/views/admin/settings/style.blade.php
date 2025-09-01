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
                    <form action="{{ route('admin.settings.update', ['resource' => 'style']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <label class="title">Global CSS Rules</label>
                                </div>
                            </div>
                            <div class="form-box__body">
                                <div class="form-fields mb-4">
                                    <label class="title">Paste only CSS, without &lt;style&gt; tags</label>
                                    <textarea name="global_styles" id="global_styles" class="field" rows="12">{{ $settings->get('global_styles') }}</textarea>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.15/mode/css/css.min.js"></script>
@endpush
@push('js')
    <script>
        const editor = CodeMirror.fromTextArea(document.getElementById("global_styles"), {
            mode: "css",
            theme: "default",
            lineNumbers: true,
            tabSize: 2,
            indentWithTabs: true
        });
    </script>
@endpush
