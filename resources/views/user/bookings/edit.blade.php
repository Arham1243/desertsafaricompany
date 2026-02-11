@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.bookings.edit', $booking) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Order ID: #{{ $booking->order_number }}</h3>
                    </div>
                    <div class="custom-sec__header justify-content-start gap-3">
                        Payment status:
                        <span
                            class="badge rounded-pill bg-{{ $booking->payment_status === 'paid'
                                ? 'success'
                                : ($booking->payment_status === 'partial'
                                    ? 'warning'
                                    : 'danger') }}">
                            {{ ucfirst($booking->payment_status ?? 'N/A') }}
                        </span>

                        @if ($booking->payment_status !== 'paid' && $booking->status !== 'cancelled' && ($booking->advance_amount ?? 0) == 0)
                            <a href="{{ route('user.bookings.pay', $booking->id) }}" class="themeBtn">
                                Pay Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-wrapper">
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Booking Summary</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    @php
                                        $bookedTours = getToursFromCart($booking->cart_data);
                                    @endphp

                                    <div class="col-12 mb-4">
                                        <ul class="list-group">
                                            @foreach ($bookedTours as $index => $tour)
                                                @php
                                                    $cartTourData = json_decode($booking->cart_data, true);
                                                    $tourDataDetails = $cartTourData['tours'][$tour->id] ?? [];
                                                    $tourData = $tourDataDetails['tourData'] ?? [];
                                                @endphp

                                                <li class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong style="font-size: 1.1rem;">Tour {{ $index + 1 }}: {{ $tour->title }}</strong>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <strong>Booking Date:</strong>
                                                    <span>{{ formatDate($tourDataDetails['start_date']) }}</span>
                                                </li>
                                                
                                                @if (!empty($tourData))
                                                    @foreach ($tourData as $rowIndex => $row)
                                                        @if (isset($row['original_price']) && isset($row['quantity']))
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <strong>Price per Person:</strong>
                                                                <span>{{ formatPrice($row['original_price']) }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <strong>Number of Persons:</strong>
                                                                <span>{{ $row['quantity'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center mb-4">
                                                                <strong>Subtotal:</strong>
                                                                <span>{{ formatPrice($row['original_price'] * $row['quantity']) }}</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @include('partials.booking-additional-display')

                                                @if ($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                                                    <li class="list-group-item">
                                                        <form action="{{ route('user.bookings.update', $booking->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                                            <div class="form-fields">
                                                                <label class="title">Update Start Date:</label>
                                                                <input onclick="this.showPicker()" onfocus="this.showPicker()"
                                                                    type="date" name="start_date" class="field"
                                                                    value="{{ $tourDataDetails['start_date'] }}">
                                                            </div>
                                                            <button type="submit" class="themeBtn mt-2">Update Date</button>
                                                        </form>
                                                    </li>
                                                @endif

                                                @if (!$loop->last)
                                                    <li class="list-group-item bg-light text-center">
                                                        <em>───────</em>
                                                    </li>
                                                @endif
                                            @endforeach

                                            <li class="list-group-item bg-light">
                                                <strong style="font-size: 1.1rem;">Payment Summary</strong>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Total Amount:</strong>
                                                <span>{{ formatPrice($booking->total_amount) }}</span>
                                            </li>
                                            @if ($booking->advance_amount > 0)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <strong>Advance Payment:</strong>
                                                    <span>{{ formatPrice($booking->advance_amount) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <strong>Remaining Amount:</strong>
                                                    <span>{{ formatPrice($booking->remaining_amount) }}</span>
                                                </li>
                                            @endif
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Payment Status:</strong>
                                                <span class="badge rounded-pill bg-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'partial' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($booking->payment_status ?? 'N/A') }}
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Booking Status:</strong>
                                                <span class="badge rounded-pill bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($booking->status ?? 'N/A') }}
                                                </span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Order Date:</strong>
                                                <span>{{ formatDateTime($booking->created_at) }}</span>
                                            </li>
                                            @if ($booking->payment_date)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <strong>Payment Date:</strong>
                                                    <span>{{ formatDateTime($booking->payment_date) }}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $bookingRequestData = json_decode($booking->request_data, true);
                        @endphp
                        @if ($bookingRequestData)
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Billing Details</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Name:</label>
                                                <input type="text" class="field"
                                                    value="{{ $bookingRequestData['name'] ?? '' }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Email:</label>
                                                <input type="text" class="field"
                                                    value="{{ $bookingRequestData['email'] ?? '' }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Phone:</label>
                                                <input type="text" class="field"
                                                    value="+{{ $bookingRequestData['phone_dial_code'] ?? '' }} {{ $bookingRequestData['phone_number'] ?? '' }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Country:</label>
                                                <input type="text" class="field"
                                                    value="{{ $bookingRequestData['country'] ?? '' }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">City:</label>
                                                <input type="text" class="field"
                                                    value="{{ $bookingRequestData['city'] ?? '' }}" readonly>
                                            </div>
                                        </div>

                                        @if (!empty($bookingRequestData['speical_request']))
                                            <div class="col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Special Request:</label>
                                                    <textarea rows="8" class="field" readonly>{{ $bookingRequestData['speical_request'] }}</textarea>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @php
                            $appliedCoupons = getCouponsFromCart($booking->cart_data);
                        @endphp
                        @if ($appliedCoupons->count())
                            <div class="form-box">
                                <div class="form-box__header d-flex justify-content-between align-items-center">
                                    <div class="title">Applied Coupons</div>
                                </div>
                                <div class="form-box__body">

                                    <div class="row">
                                        @foreach ($appliedCoupons as $index => $coupon)
                                            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Coupon {{ $index + 1 }}</strong>
                                                    @if (!empty($coupon->is_first_order) && $coupon->is_first_order)
                                                        <span class="badge bg-success ms-2">First Order Coupon</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Code:</label>
                                                    <input type="text" class="field" value="{{ $coupon->code }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Type:</label>
                                                    <input type="text" class="field"
                                                        value="{{ ucfirst($coupon->type) }}" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Amount:</label>
                                                    <input type="text" class="field" value="{{ $coupon->amount }}"
                                                        readonly>
                                                </div>
                                            </div>

                                            @if (!$loop->last)
                                                <div class="col-12">
                                                    <hr class="my-5">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="form-box mt-3">
                            <div class="form-box__header">
                                <div class="title">Driver</div>
                            </div>
                            <div class="form-box__body">
                                @if ($booking->driver)
                                    <div class="form-fields">
                                        <label class="title">Driver Name</label>
                                        <input type="text" class="field" value="{{ $booking->driver->name }}"
                                            readonly="">
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Driver Phone</label>
                                        <input type="text" class="field" value="{{ $booking->driver->phone }}"
                                            readonly="">
                                    </div>
                                @else
                                    Not Assigned
                                @endif

                            </div>
                        </div>

                        @if ($booking->status !== 'cancelled' && $booking->payment_status === 'pending')
                            <div class="form-box mt-3">
                                <div class="form-box__header">
                                    <div class="title">Cancel Booking</div>
                                </div>
                                <div class="form-box__body">
                                    <p style="margin-bottom: 15px; color: #666;">
                                        <strong>Note:</strong> Use this option only if the booking should not proceed.
                                    </p>
                                    <a href="{{ route('user.bookings.cancel', $booking->id) }}"
                                        onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');"
                                        class="themeBtn">Cancel Booking</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
