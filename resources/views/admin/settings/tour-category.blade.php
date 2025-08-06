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
                    <form action="{{ route('admin.settings.update', ['resource' => 'tour-category']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Color Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-fields">
                                            <label class="title title--sm d-flex align-items-center gap-2 mb-3">
                                                Content Color Settings:
                                                <a href="{{ asset('admin/assets/images/tour-category-settings/content-section.png') }}"
                                                    data-fancybox="gallery" title="section preview"
                                                    class="themeBtn section-preview-image section-preview-image--sm"><i
                                                        class="bx bxs-show"></i></a></label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="title d-flex align-items-center gap-2">
                                                <div>Editor Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="heading-color-picker" data-color-picker></label>
                                                <input id="heading-color-picker" type="text" data-color-picker-input
                                                    name="tour_category_content_color"
                                                    value="{{ $settings->get('tour_category_content_color') ?? '#243064' }}"
                                                    inputmode="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-fields">
                                            <div class="title d-flex align-items-center gap-2">
                                                <div>Read More/Read Less Text Color:</div>
                                                <a class="p-0 nav-link" href="//html-color-codes.info" target="_blank">Get
                                                    Color Codes</a>
                                            </div>
                                            <div class="field color-picker" data-color-picker-container>
                                                <label for="heading-color-picker" data-color-picker></label>
                                                <input id="heading-color-picker" type="text" data-color-picker-input
                                                    name="tour_category_content_read_more_color"
                                                    value="{{ $settings->get('tour_category_content_read_more_color') ?? '#1c4d99' }}"
                                                    inputmode="text">
                                            </div>
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

@push('js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js"></script>
@endpush
