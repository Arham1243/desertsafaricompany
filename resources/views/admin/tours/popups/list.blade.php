@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-popups.index') }}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'tour-popups']) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                            <a href="{{ route('admin.tour-popups.create') }}" class="themeBtn">Add popup</a>
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
                                        <th>Main Heading</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        class="bulk-item" name="bulk_select[]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.tour-popups.edit', $item->id) }}"
                                                    class="link">{{ $item->main_heading }}</a>
                                            </td>
                                            <td>
                                                @php
                                                    $typeLabels = [
                                                        'info' => 'Informational',
                                                        'policy' => 'Policy / Terms',
                                                    ];
                                                @endphp
                                                {{ $typeLabels[$item->type] }}
                                            </td>
                                            <td>
                                                {{ formatDateTime($item->created_at) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill 
                                                bg-{{ $item->status === 'active'
                                                    ? 'success'
                                                    : ($item->status === 'inactive'
                                                        ? 'danger'
                                                        : ($item->status === 'draft'
                                                            ? 'warning'
                                                            : 'secondary')) }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.tour-popups.edit', $item->id) }}"
                                                    class="themeBtn"><i class='bx bxs-edit'></i>Edit</a>
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
