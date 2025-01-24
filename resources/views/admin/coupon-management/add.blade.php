@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.coupons.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data" id="validation-form">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Coupon Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Code <span class="text-danger">*</span> :</label>
                                                <input type="text" name="code" class="field"
                                                    value="{{ old('code') }}" data-required data-error="Code">
                                                @error('code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Name <span class="text-danger">*</span> :</label>
                                                <input type="text" name="name" class="field"
                                                    value="{{ old('name') }}" data-required data-error="Name">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Amount <span class="text-danger">*</span> :</label>
                                                <input type="text" name="amount" class="field"
                                                    value="{{ old('amount') }}" data-required data-error="Amount">
                                                @error('amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Discount Type <span class="text-danger">*</span>
                                                    :</label>
                                                <select name="discount_type" class="field" data-required data-error="Name">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="percentage">Percentage</option>
                                                    <option value="fixed">Fixed</option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Minimum Order Amount <span
                                                        class="text-danger">*</span> :</label>
                                                <input type="text" name="minimum_order_amount" class="field"
                                                    value="{{ old('minimum_order_amount') }}" data-required
                                                    data-error="Minimum Order Amount">
                                                @error('minimum_order_amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Expiry Date <span class="text-danger">*</span>
                                                    :</label>
                                                <input type="date" name="expiry_date" class="field"
                                                    value="{{ old('expiry_date') }}" data-required data-error="Expiry Date"
                                                    min="{{ now()->toDateString() }}">
                                                @error('expiry_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Publish</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active" checked
                                            value="active">
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive">
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
