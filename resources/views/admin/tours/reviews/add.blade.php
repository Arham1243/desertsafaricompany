@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-reviews.edit', $review) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Edit Review: {{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.tour-reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Review Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Title</label>
                                        <input type="text" name="title" class="field"
                                            value="{{ old('title', $review->title) }}" placeholder="Title"
                                            data-error="Title">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-fields">
                                        <label class="title">for select field</label>
                                        <select name="iso_alpha2" class="field select2-select" data-error="ISO Alpha-2 Code"
                                            data-required>
                                            <option value="" selected disabled>Select Country</option>

                                        </select>
                                        @error('iso_alpha2')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header d-flex align-items-center justify-content-between">
                                    <div class="title">Status</div>
                                    @if ($review->status === 'pending')
                                        <span class="badge rounded-pill bg-warning">
                                            {{ $review->status }}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active"
                                            value="active"
                                            {{ old('status', $review->status ?? '') == 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive"
                                            {{ old('status', $review->status ?? '') == 'inactive' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactive">
                                            Inactive
                                        </label>
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
