@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.bookings.edit', $booking) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Order ID: #{{ $booking->id }}</h3>
                    </div>
                    <div class="custom-sec__header justify-content-start gap-3">
                        Payment status:
                        <span
                            class="badge rounded-pill bg-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($booking->payment_status ?? 'N/A') }}
                        </span>
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
                                                    class="badge rounded-pill bg-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $booking->payment_status ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Registered User Details</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Name:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->user->full_name ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Email:</label>
                                            <input type="text" class="field" value="{{ $booking->user->email ?? '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Phone:</label>
                                            <input type="text" class="field" value="{{ $booking->user->phone ?? '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Country:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->user->country ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">City:</label>
                                            <input type="text" class="field" value="{{ $booking->user->city ?? '' }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Signup Method:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->user->signup_method ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Registration Date:</label>
                                            <input type="text" class="field"
                                                value="{{ $booking->user->created_at->format('d M Y') ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <div class="placeholder-user">
                                            <a href="{{ $booking->user->avatar ?? asset('admin/assets/images/placeholder.png') }}"
                                                data-fancybox="gallery" class="placeholder-user__img">
                                                <img src="{{ $booking->user->avatar ?? asset('admin/assets/images/placeholder.png') }}"
                                                    alt="{{ $booking->user->full_name ?? '' }}" class="imgFluid">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Billing Details</div>
                            </div>
                            <div class="form-box__body">
                                @php
                                    $bookingRequestData = json_decode($booking->request_data, true);
                                @endphp

                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">First Name:</label>
                                            <input type="text" class="field"
                                                value="{{ $bookingRequestData['first_name'] ?? '' }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Last Name:</label>
                                            <input type="text" class="field"
                                                value="{{ $bookingRequestData['last_name'] ?? '' }}" readonly>
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

                                    <div class="col-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">Address:</label>
                                            <input type="text" class="field"
                                                value="{{ $bookingRequestData['address'] ?? '' }}" readonly>
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
                        <div class="form-box">
                            <div class="form-box__header d-flex align-items-center gap-4">
                                <div class="title">Booked Tours</div>
                                <div class="title">{{ getTotalNoOfPeopleFromCart($booking->cart_data) }}</div>
                                <div class="title">{{ formatPrice($booking->total_amount) }}</div>
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
                                        <div class="col-md-6 col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title title--sm">
                                                    Start Date:
                                                </label>
                                                <div class="title text-dark">
                                                    {{ formatDate($tourDataDetails['start_date']) }}</div>
                                            </div>
                                        </div>
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
                                                <a href="{{ route('admin.tours.edit', $tour->id) }}" target="_blank"
                                                    class="themeBtn">
                                                    View Tour in Portal
                                                </a>
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

                        <div class="form-box">
                            <div class="form-box__header d-flex justify-content-between align-items-center">
                                <div class="title">Applied Coupons</div>
                            </div>
                            <div class="form-box__body">
                                @php
                                    $appliedCoupons = getCouponsFromCart($booking->cart_data);
                                @endphp

                                @if ($appliedCoupons->count())
                                    <div class="row">
                                        @foreach ($appliedCoupons as $index => $coupon)
                                            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>Coupon {{ $index + 1 }}</strong>
                                                    @if (!empty($coupon->is_first_order) && $coupon->is_first_order)
                                                        <span class="badge bg-success ms-2">First Order Coupon</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" target="_blank"
                                                    class="themeBtn">
                                                    View in Portal
                                                </a>
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
                                @else
                                    <p class="text-muted">No coupons applied to this booking.</p>
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
                                    <a href="{{ route('admin.bookings.cancel', $booking->id) }}"
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
