@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.coupons.index') }}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'coupons']) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                            <a href="{{ route('admin.coupons.create') }}" class="themeBtn">Add new</a>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <form class="custom-form ">
                                    <div class="form-fields d-flex gap-3">
                                        <select class="field" id="bulkActions" name="bulk_actions" required>
                                            <option value="" disabled selected>Bulk Actions</option>
                                            <option value="active">Make Active</option>
                                            <option value="inactive">Make Inactive</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                        <button type="submit" onclick="confirmBulkAction(event)"
                                            class="themeBtn">Apply</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <div class="selection select-all-container"><input type="checkbox"
                                                    id="select-all">
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Amount</th>
                                        <th>Discount Type</th>
                                        <th>Expiry Date</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($isFirstOrderCoupon)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        value="{{ $isFirstOrderCoupon->id }}" disabled>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.coupons.edit', $isFirstOrderCoupon->id) }}"
                                                    class="link">{{ $isFirstOrderCoupon->name }}</a>
                                                <span class="d-block mt-2 badge rounded-pill bg-success"
                                                    style=" width: fit-content; "> First Order coupon </span>
                                            </td>
                                            <td>{{ $isFirstOrderCoupon->code }}</td>
                                            <td>{{ $isFirstOrderCoupon->amount }}</td>
                                            <td style="text-transform: capitalize">{{ $isFirstOrderCoupon->discount_type }}
                                            </td>
                                            <td>
                                                {{ !$isFirstOrderCoupon->no_expiry ? 'No Expiry' : formatDateTime($isFirstOrderCoupon->expiry_date) }}
                                            </td>
                                            <td>
                                                {{ formatDateTime($isFirstOrderCoupon->created_at) }}
                                            </td>
                                            <td>
                                                @php
                                                    $isExpired =
                                                        isset($isFirstOrderCoupon->expiry_date) &&
                                                        !empty($isFirstOrderCoupon->expiry_date) &&
                                                        \Carbon\Carbon::now()->gt(
                                                            \Carbon\Carbon::parse($isFirstOrderCoupon->expiry_date),
                                                        );
                                                @endphp
                                                <span
                                                    class="badge rounded-pill bg-{{ $isExpired ? 'warning' : ($isFirstOrderCoupon->status == 'active' ? 'success' : 'danger') }}">
                                                    {{ $isExpired ? 'Expired' : ucfirst($isFirstOrderCoupon->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.coupons.edit', $isFirstOrderCoupon->id) }}"
                                                    class="themeBtn"><i class='bx bxs-edit'></i>Edit</a>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        class="bulk-item" name="bulk_select[]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.coupons.edit', $item->id) }}"
                                                    class="link">{{ $item->name }}</a>
                                            </td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->amount }}</td>
                                            <td style="text-transform: capitalize">{{ $item->discount_type }}</td>
                                            <td>
                                                {{ !$item->no_expiry ? 'No Expiry' : formatDateTime($item->expiry_date) }}
                                            </td>
                                            <td>
                                                {{ formatDateTime($item->created_at) }}
                                            </td>
                                            <td>
                                                @php
                                                    $isExpired =
                                                        isset($item->expiry_date) &&
                                                        !empty($item->expiry_date) &&
                                                        \Carbon\Carbon::now()->gt(
                                                            \Carbon\Carbon::parse($item->expiry_date),
                                                        );
                                                @endphp
                                                <span
                                                    class="badge rounded-pill bg-{{ $isExpired ? 'warning' : ($item->status == 'active' ? 'success' : 'danger') }}">
                                                    {{ $isExpired ? 'Expired' : ucfirst($item->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.coupons.edit', $item->id) }}" class="themeBtn"><i
                                                        class='bx bxs-edit'></i>Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
