@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.coupons.edit', $coupon) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
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
                                                    value="{{ old('code', $coupon->code) }}" data-required
                                                    data-error="Code">
                                                @error('code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Name <span class="text-danger">*</span> :</label>
                                                <input type="text" name="name" class="field"
                                                    value="{{ old('name', $coupon->name) }}">
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Discount Type <span class="text-danger">*</span>
                                                    :</label>
                                                <select name="discount_type" class="field" data-required data-error="Name">
                                                    <option value="" selected disabled>Select</option>
                                                    <option value="percentage"
                                                        {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>
                                                        Percentage</option>
                                                    <option value="fixed"
                                                        {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                        Fixed</option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Enter Amount or Percentage <span
                                                        class="text-danger">*</span> :</label>
                                                <input type="number" name="amount" class="field"
                                                    value="{{ old('amount', $coupon->amount) }}" data-required
                                                    data-error="Amount">
                                                @error('amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Minimum Order Amount :</label>
                                                <input type="number" name="minimum_order_amount" class="field"
                                                    value="{{ old('minimum_order_amount', $coupon->minimum_order_amount) }}">
                                                @error('minimum_order_amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4" x-data="{ hasExpiry: {{ old('no_expiry', $coupon->no_expiry) === 0 ? 'false' : 'true' }} }">
                                            <div class="form-fields">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="no_expiry"
                                                        name="no_expiry" value="1" x-model="hasExpiry"
                                                        :value="!hasExpiry ? 1 : 0">
                                                    <label class="form-check-label" for="no_expiry">Has Expiry</label>
                                                </div>

                                                <template x-if="hasExpiry">
                                                    <div>
                                                        <label class="title">Expiry Date <span class="text-danger">*</span>
                                                            :</label>
                                                        <input type="datetime-local" name="expiry_date" class="field"
                                                            value="{{ old('expiry_date', $coupon->expiry_date) }}"
                                                            data-required data-error="Expiry Date"
                                                            min="{{ now()->format('Y-m-d\TH:i') }}"
                                                            x-init="$el.addEventListener('click', () => $el.showPicker && $el.showPicker())">

                                                        @error('expiry_date')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </template>
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
                                        <input class="form-check-input" type="radio" name="status" id="active"
                                            value="active"
                                            {{ old('status', $coupon->status) == 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive"
                                            {{ old('status', $coupon->status) == 'inactive' ? 'checked' : '' }}>
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
