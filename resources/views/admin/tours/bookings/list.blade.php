@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.bookings.index') }}
            <div class="table-container universal-table">
                <div class="custom-sec">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                        </div>
                        <form action="{{ route('admin.export', ['resource' => 'orders']) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to export?')">
                            @csrf
                            <input type="hidden" name="columns[]" value="booking_id">
                            <input type="hidden" name="columns[]" value="total_no_of_people">
                            <input type="hidden" name="columns[]" value="driver">
                            <input type="hidden" name="columns[]" value="tours">
                            <input type="hidden" name="columns[]" value="guest_name">
                            <input type="hidden" name="columns[]" value="guest_contact">
                            <input type="hidden" name="columns[]" value="total_amount">
                            <input type="hidden" name="columns[]" value="advance_amount">
                            <input type="hidden" name="columns[]" value="remaining_amount">
                            <button type="submit" class="themeBtn ms-auto"><i class='bx bxs-file-export'></i>Export as
                                Excel</button>
                        </form>
                    </div>
                    <form id="filter-form" class="w-full">
                        <div class="row w-full mb-4">
                            <div class="col-md-3">
                                <div class="form-fields">
                                    <label class="title">Booking Date:</label>
                                    <input type="date" name="start_date" class="field"
                                        value="{{ request('start_date') }}" onclick="this.showPicker()"
                                        onfocus="this.showPicker()" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-fields">
                                    <label class="title">Order Created Date:</label>
                                    <input type="date" name="created_at" class="field"
                                        value="{{ request('created_at') }}" onclick="this.showPicker()"
                                        onfocus="this.showPicker()" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-fields">
                                    <label class="title">Order Status:</label>
                                    <select name="status" class="field">
                                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>
                                            Select</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            Confirmed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-fields">
                                    <label class="title">Payment Status:</label>
                                    <select name="payment_status" class="field">
                                        <option value="" {{ request('payment_status') == '' ? 'selected' : '' }}>
                                            Select</option>
                                        <option value="pending"
                                            {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                            Paid</option>
                                        <option value="cancelled"
                                            {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                        </option>
                                        <option value="failed"
                                            {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                            </div>
                            @if (request()->hasAny(['start_date', 'created_at', 'status', 'payment_status']))
                                <div class="col-md-3 mt-4">
                                    <a href="{{ route('admin.bookings.index') }}" class="themeBtn">
                                        <i class='bx bx-refresh'></i> Reset Filters
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Tour</th>
                                    <th>User</th>
                                    <th>Driver</th>
                                    <th>Payment Type</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                    <th>Booking Status</th>
                                    <th>Created at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $item)
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ route('admin.bookings.edit', $item->id) }}"
                                                class="link">#{{ $item->id }}</a>
                                        </td>
                                        <td>
                                            @foreach (getToursFromCart($item->cart_data) as $tour)
                                                <div>
                                                    <strong>{{ $tour->title }}</strong><br>
                                                    <small style="color: #666;">
                                                        Date:
                                                        {{ formatDate(getTourStartDate($item->cart_data, $tour->id)) }}
                                                        &bull;
                                                        {{ $item->total_no_of_people }} pax
                                                    </small>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($item->user)
                                                {{ $item->user->full_name ?? 'N/A' }} <br>
                                                {{ $item->user->email ?? 'N/A' }}
                                            @else
                                                Guest Checkout
                                                {{ $item->guest_email ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->driver)
                                                {{ $item->driver->name }}
                                            @else
                                                Not Assigned
                                            @endif
                                        </td>
                                        <td>
                                            {{ formatKey($item->payment_type) }}
                                        </td>
                                        <td>{{ formatPrice($item->total_amount) }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $item->payment_status === 'paid' ? 'success' : ($item->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ $item->payment_status }}
                                            </span>
                                        </td>
                                        <td>{{ formatDateTime($item->payment_date) }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $item->status === 'confirmed' ? 'success' : ($item->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>{{ formatDateTime($item->created_at) }}</td>
                                        <td>
                                            <div class="dropstart bootsrap-dropdown">
                                                <button type="button" class="recent-act__icon dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ route('admin.bookings.edit', $item->id) }}"
                                                            title="View Page">
                                                            <i class='bx bxs-show'></i>
                                                            View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.bookings.edit', $item->id) }}">
                                                            <i class='bx bx-car'></i>
                                                            Assign Driver
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filter-form');

            if (filterForm) {
                filterForm.querySelectorAll('input, select').forEach(element => {
                    element.addEventListener('change', () => {
                        filterForm.submit();
                    });
                });
            }
        });
    </script>
@endpush
