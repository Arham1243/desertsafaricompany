@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.blogs.index') }}
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'blogs']) }}">
                @csrf
                <div class="table-container universal-table">
                    <div class="custom-sec">
                        <div class="custom-sec__header">
                            <div class="section-content">
                                <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                            </div>
                            <a href="{{ route('admin.blogs.create') }}" class="themeBtn">Add new blog</a>
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
                                        <th>Name</th>
                                        <th>Likes</th>
                                        <th>Dislikes</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blogs as $blog)
                                        <tr>
                                            <td>
                                                <div class="selection item-select-container"><input type="checkbox"
                                                        class="bulk-item" name="bulk_select[]" value="{{ $blog->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.blogs.edit', $blog->id) }}"
                                                    class="link">{{ $blog->title }}</a>
                                            </td>
                                            <td>{{ $blog->likes_count }}</td>
                                            <td>{{ $blog->dislikes_count }}</td>
                                            <td>{{ $blog->category->name ?? 'N/A' }}</td>
                                            <td>
                                                {{ formatDateTime($blog->created_at) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge rounded-pill bg-{{ $blog->status == 'publish' ? 'success' : 'warning' }} ">
                                                    {{ $blog->status }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="themeBtn"><i
                                                        class='bx bxs-edit'></i>Edit</a> --}}
                                                <div class="dropstart bootsrap-dropdown">
                                                    <button type="button" class="recent-act__icon dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class='bx bx-dots-horizontal-rounded'></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.blogs.edit', $blog->id) }}">
                                                                <i class='bx bxs-edit'></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.blogs.duplicate', $blog->id) }}"
                                                                onclick="return confirm('Are you sure you want to duplicate this blog?')">
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
