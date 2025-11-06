@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.index') }}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'tours']) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                            <a href="{{ route('admin.tours.create') }}" class="themeBtn">Add Tour</a>
                        </div>
                        <div class="row align-items-end justify-content-between mb-4">
                            <div class="col-md-4">
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
                            <div class="col-md-8">
                                @php
                                    $filteredCategory = isset($_GET['category']) ? $_GET['category'] : null;
                                    $filteredCity = isset($_GET['city']) ? $_GET['city'] : null;
                                @endphp
                                <form id="filter-form" class="w-full">
                                    <div class="row w-full">
                                        <div class="col-md-4">
                                            <div class="form-fields">
                                                <label class="title">Search by Category:</label>
                                                <select onchange="document.getElementById('filter-form').submit()"
                                                    name="category" class="select2-select" should-sort='false'
                                                    id="search-by-categpry">
                                                    <option value="" disabled selected>Select Category
                                                    </option>
                                                    @php
                                                        renderCategories($tourCategories, $filteredCategory);
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-fields">
                                                <label class="title">Search by City:</label>
                                                <select onchange="document.getElementById('filter-form').submit()"
                                                    name="city" class="select2-select" should-sort='false'
                                                    id="search-by-city">
                                                    <option value="" disabled selected>Select City
                                                    </option>
                                                    @foreach ($tourCities as $city)
                                                        <option value="{{ $city->id }}"
                                                            {{ $filteredCity == $city->id ? 'selected' : '' }}>
                                                            {{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
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
                                        <th>City</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tours as $item)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        class="bulk-item" name="bulk_select[]" value="{{ $item->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.tours.edit', $item->id) }}"
                                                    class="link">{{ $item->title }}</a> <br />
                                                <a target="_blank" style="font-size: 0.76rem;"
                                                    href="{{ buildTourDetailUrl($item) }}"
                                                    class="link">{{ buildTourDetailUrl($item) }}</a>
                                            </td>
                                            <td>{{ $item->city->name ?? 'N/A' }}</td>
                                            <td>{{ $item->category->name ?? 'N/A' }}</td>
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
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.tours.edit', $item->id) }}">
                                                                <i class='bx bxs-edit'></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.tours.duplicate', $item->id) }}"
                                                                onclick="return confirm('Are you sure you want to duplicate this tour?')">
                                                                <i class='bx bxs-copy'></i>
                                                                Duplicate </a>
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
