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
                    <form action="{{ route('admin.settings.update', ['resource' => 'social-media']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $socialMediaPlatforms = [
                                ['platform' => 'facebook_url', 'label' => 'Facebook'],
                                ['platform' => 'twitter_url', 'label' => 'Twitter'],
                                ['platform' => 'instagram_url', 'label' => 'Instagram'],
                                ['platform' => 'linkedin_url', 'label' => 'LinkedIn'],
                            ];
                        @endphp

                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Social Media</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    @foreach ($socialMediaPlatforms as $socialMedia)
                                        <div class="col-md-12 mb-4">
                                            @php
                                                $socialMediaValue = $settings->get($socialMedia['platform']);
                                            @endphp
                                            <div class="form-fields">
                                                <label class="title">{{ $socialMedia['label'] }} Url:</label>
                                                <input type="url" name="{{ $socialMedia['platform'] }}"
                                                    value="{{ $socialMediaValue ?? '' }}" class="field">
                                            </div>
                                        </div>
                                    @endforeach
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
