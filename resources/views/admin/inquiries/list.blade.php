@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.inquiries.index') }}

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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Package</th>
                                    <th>Persons</th>
                                    <th>Start Date</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inquiries as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.inquiries.edit', $item->id) }}"
                                                class="link">{{ $item->name ?? 'N/A' }}</a>
                                        </td>
                                        <td>{{ $item->email ?? 'N/A' }}</td>
                                        <td>{{ makePhoneNumber($item->phone_dial_code, $item->phone_number) }}</td>
                                        <td>{{ $item->package ?? 'N/A' }}</td>
                                        <td>{{ $item->persons ?? 'N/A' }}</td>
                                        <td>{{ formatDate($item->start_date) }}</td>
                                        <td>{{ formatDateTime($item->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('admin.inquiries.edit', $item->id) }}" class="themeBtn"><i
                                                    class='bx bxs-edit'></i>View Details</a>
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
