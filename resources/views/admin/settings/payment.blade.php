@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.create') }}
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
                    <form action="{{ route('admin.settings.update', ['resource' => 'payment']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $stripeSecretKey = $settings->get('stripe_secret_key');
                            $tabbySecretKey = $settings->get('tabby_secret_key');
                        @endphp

                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Payment Secret Keys</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Stripe Secret key</label>
                                            <input type="text" name="stripe_secret_key"
                                                value="{{ $stripeSecretKey ?? '' }}" class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title">Tabby Secret key</label>
                                            <input type="text" name="tabby_secret_key"
                                                value="{{ $tabbySecretKey ?? '' }}" class="field">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="themeBtn ms-auto ">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
