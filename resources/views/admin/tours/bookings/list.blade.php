@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.bookings.index') }}
            <form id="bulkActionForm" method="POST"
                action="{{ route('admin.bulk-actions', ['resource' => 'tour-reviews']) }}">
                @csrf
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
                                        <th>User Name</th>
                                        <th>Payment Status</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->id }}</td>
                                            <td>
                                                @foreach (getToursFromCart($item->cart_data) as $tour)
                                                    <a target="_blank" href="{{ route('admin.tours.edit', $tour->id) }}"
                                                        class="link">{{ $tour->title }}</a> <br>
                                                @endforeach
                                            </td>
                                            <td>{{ $item->user->full_name ?? 'N/A' }} <br>
                                                {{ $item->user->email ?? 'N/A' }}</td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill bg-{{ $item->payment_status === 'paid' ? 'success' : ($item->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $item->payment_status }}
                                                </span>
                                            </td>
                                            <td>{{ formatDateTime($item->payment_date) }}</td>
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
            </form>
        </div>
    </div>
@endsection
