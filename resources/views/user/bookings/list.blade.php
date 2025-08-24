@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.bookings.index') }}
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
                                    <th>User</th>
                                    <th>Payment Type</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                    <th>Order Created at</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $item)
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ route('user.bookings.edit', $item->id) }}"
                                                class="link">#{{ $item->id }}</a>
                                        </td>
                                        <td>
                                            @foreach (getToursFromCart($item->cart_data) as $tour)
                                                <a target="_blank" href="{{ $tour->detail_url }}"
                                                    class="link">{{ $tour->title }}</a> <br>
                                            @endforeach
                                        </td>
                                        <td>{{ $item->user->full_name ?? 'N/A' }} <br>
                                            {{ $item->user->email ?? 'N/A' }}</td>
                                        <td>
                                            {{ formatKey($item->payment_type) }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $item->payment_status === 'paid' ? 'success' : ($item->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ $item->payment_status }}
                                            </span>
                                        </td>
                                        <td>{{ formatDateTime($item->payment_date) }}</td>
                                        <td>{{ formatDateTime($item->created_at) }}</td>
                                        <td>
                                            <div class="dropstart bootsrap-dropdown">
                                                <button type="button" class="recent-act__icon dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-dots-horizontal-rounded'></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('user.bookings.edit', $item->id) }}">
                                                            <i class='bx bxs-show'></i>
                                                            View
                                                        </a>
                                                    </li>
                                                    @if ($item->payment_status !== 'paid')
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('user.bookings.pay', $item->id) }}">
                                                                <i class='bx bxs-credit-card'></i>
                                                                Pay Now
                                                            </a>
                                                        </li>
                                                    @endif
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
