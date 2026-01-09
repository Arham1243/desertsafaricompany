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
                    </div>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Tour</th>
                                    <th>No of People</th>
                                    <th>User</th>
                                    <th>Payment Type</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                    <th>Booking Status</th>
                                    <th>Status</th>
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
                                                {{ $tour->title }} <br>
                                            @endforeach
                                        </td>
                                        <td>{{ getTotalNoOfPeopleFromCart($item->cart_data) }} <br>
                                        <td>{{ $item->user->full_name ?? 'N/A' }} <br>
                                            {{ $item->user->email ?? 'N/A' }}</td>
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
                                            <a href="{{ route('admin.bookings.edit', $item->id) }}" class="themeBtn"><i
                                                    class='bx bxs-edit'></i>View</a>
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
