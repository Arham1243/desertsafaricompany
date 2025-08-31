@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.pages.index') }}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'pages']) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                            <a href="{{ route('admin.pages.create') }}" class="themeBtn">Add New page</a>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <form class="custom-form ">
                                    <div class="form-fields d-flex gap-3">
                                        <select class="field" id="bulkActions" name="bulk_actions" required>
                                            <option value="" disabled selected>Bulk Actions</option>
                                            <option value="publish">Publish</option>
                                            <option value="draft">Move to Draft</option>
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
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $item)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        class="bulk-item" name="bulk_select[]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pages.edit', $item->id) }}"
                                                    class="link">{{ $item->title }}</a><br />

                                                @if ($item->id == $homepage_id)
                                                    <a target="_blank" style="font-size: 0.76rem;"
                                                        href="{{ url('/') }}" class="link">{{ url('/') }}</a>
                                                    <span
                                                        class="badge rounded-pill bg-success d-inline-block ms-5">Home</span>
                                                @else
                                                    <a target="_blank" style="font-size: 0.76rem;"
                                                        href="{{ buildUrl(url('/'), 'page/' . $item->slug) }}"
                                                        class="link">{{ buildUrl(url('/'), 'page/' . $item->slug) }}</a>
                                                @endif
                                            </td>
                                            <td>{{ formatDateTime($item->created_at) }}</td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill bg-{{ $item->status == 'publish' ? 'success' : 'warning' }} ">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="dropstart bootsrap-dropdown">
                                                    <button type="button" class="recent-act__icon dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" target="_blank"
                                                                href="{{ route('frontend.page.show', $item->slug) }}{{ $item->status === 'draft' ? '?viewer=admin' : '' }}"
                                                                title="View Page">
                                                                <i class='bx bxs-show'></i>
                                                                View page
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.pages.edit', $item->id) }}">
                                                                <i class='bx bxs-edit'></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.pages.page-builder', $item->id) }}">
                                                                <i class='bx bxs-paint'></i>
                                                                Page Builder
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
            </form>
        </div>
    </div>
@endsection
