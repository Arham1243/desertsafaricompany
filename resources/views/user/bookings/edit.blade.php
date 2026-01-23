@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.bookings.edit', $booking) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Order ID: #{{ $booking->id }}</h3>
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
                                <div class="title">Payment Details</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Total Amount:</label>
                                            <input type="text" class="field"
                                                value="{{ number_format($booking->total_amount ?? 0, 2) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Payment Type:</label>
                                            <input type="text" class="field" value="{{ $booking->payment_type ?? '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Payment Date:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->payment_date ? formatDateTime($booking->payment_date) : '' }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Order Date:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->created_at ? formatDateTime($booking->created_at) : '' }}"
                                                readonly>
                                        </div>
                                    </div>



                                    @if ($booking->advance_amount)
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Advance Amount:</label>
                                                <input type="text" class="field" value="{{ $booking->advance_amount }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($booking->paid_amount)
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Paid Amount:</label>
                                                <input type="text" class="field" value="{{ $booking->paid_amount }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($booking->remaining_amount)
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Remaining Amount:</label>
                                                <input type="text" class="field"
                                                    value="{{ $booking->remaining_amount }}" readonly>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Booking Status:</label>
                                            <div>
                                                <span
                                                    class="badge rounded-pill bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $booking->status ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Payment Status:</label>
                                            <div>
                                                <span
                                                    class="badge rounded-pill bg-{{ $booking->payment_status === 'paid'
                                                        ? 'success'
                                                        : ($booking->payment_status === 'partial'
                                                            ? 'warning'
                                                            : 'danger') }}">
                                                    {{ $booking->payment_status ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
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
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Booked Tours</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    @php
                                        $bookedTours = getToursFromCart($booking->cart_data);
                                    @endphp

                                    @foreach ($bookedTours as $index => $tour)
                                        @php
                                            $cartTourData = json_decode($booking->cart_data, true);
                                            $tourDataDetails = $cartTourData['tours'][$tour->id] ?? [];
                                            $tourData = $tourDataDetails['tourData'] ?? [];
                                        @endphp
                                        @if ($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                                            <form action="{{ route('user.bookings.update', $booking->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                                                <div class="col-md-12 col-12 mb-2">
                                                    <div class="form-fields">
                                                        <label class="title">Start Date:</label>
                                                        <input onclick="this.showPicker()" onfocus="this.showPicker()"
                                                            type="date" name="start_date" class="field"
                                                            value="{{ $tourDataDetails['start_date'] }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 col-12 mb-4">
                                                    <button type="submit" class="themeBtn">Update</button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="col-md-12 col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Start Date:</label>
                                                    <input type="text" class="field"
                                                        value="{{ formatDate($tourDataDetails['start_date']) }}" readonly>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title title--sm">
                                                    Total Price:
                                                </label>
                                                <div class="title text-dark">{!! formatPrice($tourDataDetails['total_price']) !!}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                            <strong>Tour {{ $index + 1 }}</strong>
                                            <div class="d-flex gap-2">
                                                <a href="{{ $tour->detail_url }}" target="_blank" class="themeBtn">
                                                    View on Website
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Title:</label>
                                                <input type="text" class="field" value="{{ $tour->title }}"
                                                    readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12 mb-4">
                                            <div class="form-fields d-flex align-items-center gap-2">
                                                <div class="flex-grow-1">
                                                    <label class="title">Tour Url:</label>
                                                    <input type="text" class="field" value="{{ $tour->detail_url }}"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!empty($tourData))
                                            <div class="col-md-12 col-12 mb-4">
                                                <div class="form-fields">
                                                    <label class="title">Tour Data:</label>
                                                    <ul class="list-group mt-3">
                                                        @foreach ($tourData as $rowIndex => $row)
                                                            @foreach ($row as $key => $value)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                    <span>
                                                                        @if (is_numeric($value) && str_contains($key, 'price'))
                                                                            {{ formatPrice($value) }}
                                                                        @elseif (is_bool($value))
                                                                            {{ $value ? 'Yes' : 'No' }}
                                                                        @elseif (is_array($value))
                                                                            {{ collect($value)->flatten()->join(', ') }}
                                                                        @else
                                                                            {!! $value !!}
                                                                        @endif
                                                                    </span>
                                                                </li>
                                                            @endforeach

                                                            @if (!$loop->last)
                                                                <li class="list-group-item text-center bg-light">
                                                                    <em>────────────</em>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>

                                                </div>
                                            </div>
                                        @endif

                                        @include('partials.booking-additional-display')

                                        @if (!$loop->last)
                                            <div class="col-12">
                                                <hr class="my-5">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

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
