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
                    <form action="{{ route('admin.settings.update', ['resource' => 'seo']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Seo Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Title Limit:</label>
                                            <input type="number" name="seo_title_limit"
                                                value="{{ $settings->get('seo_title_limit') ?? '60' }}" class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Description Limit:</label>
                                            <input type="number" name="seo_description_limit"
                                                value="{{ $settings->get('seo_description_limit') ?? '160' }}" class="field">
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

