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
                    <form action="{{ route('admin.settings.update', ['resource' => 'blogs']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-box" x-data="{ enabled: {{ (int) $settings->get('is_enabled_blogs_you_may_also_like') === 1 ? 'true' : 'false' }} }">
                            <div class="form-box__header">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="title">Enable 'You may also like Sidebar'</div>
                                    <div class="form-check form-switch" data-enabled-text="Enabled"
                                        data-disabled-text="Disabled">
                                        <input type="hidden" name="is_enabled_blogs_you_may_also_like"
                                            :value="enabled ? 1 : 0">
                                        <input class="form-check-input" type="checkbox"
                                            id="is_enabled_blogs_you_may_also_like" x-model="enabled">
                                        <label class="form-check-label" for="is_enabled_blogs_you_may_also_like"
                                            x-text="enabled ? 'Enabled' : 'Disabled'"></label>
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
