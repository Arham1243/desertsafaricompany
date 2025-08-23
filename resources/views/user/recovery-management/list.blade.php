@extends('user.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.recovery.index', $resource) }}
            <form id="bulkActionForm" method="POST"
                action="{{ route('user.bulk-actions', ['resource' => 'user-' . $resource]) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <div class="form-fields d-flex gap-3">
                                    <select class="field" id="bulkActions" name="bulk_actions" required>
                                        <option value="" disabled selected>Bulk Actions</option>
                                        <option value="restore">Restore</option>
                                        <option value="permanent_delete">Delete Permanently</option>
                                    </select>
                                    <button type="submit" onclick="confirmBulkAction(event)"
                                        class="themeBtn">Apply</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <div class="selection select-all-container"><input type="checkbox"
                                                    id="select-all"></div>
                                        </th>
                                        @foreach ($columns as $column => $heading)
                                            <th>{{ $heading }}</th>
                                        @endforeach
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container">
                                                    <input type="checkbox" class="bulk-item" name="bulk_select[]"
                                                        value="{{ $primaryColumn ? $item->{$primaryColumn} : $item->id }}">
                                                </div>
                                            </td>
                                            @foreach ($columns as $column => $heading)
                                                <td>
                                                    @if ($column === 'category')
                                                        {{ $item->category->name ?? 'N/A' }}
                                                    @elseif ($column === 'author')
                                                        {{ $item->user->full_name ?? 'N/A' }}
                                                    @elseif ($column === 'country')
                                                        {{ $item->country->name ?? 'N/A' }}
                                                    @elseif ($column === 'deleted_at')
                                                        {{ formatDateTime($item->deleted_at) }}
                                                    @else
                                                        {{ $item->$column ?? 'N/A' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td>
                                                <span class="badge rounded-pill bg-danger">deleted</span>
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
