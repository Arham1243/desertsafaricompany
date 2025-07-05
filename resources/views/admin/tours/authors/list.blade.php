<div class="table-container universal-table">
    <div class="custom-sec">
        <form id="bulkActionForm" method="POST"
            action="{{ route('admin.bulk-actions', ['resource' => 'tour-authors']) }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-7">
                    <div class="custom-form">
                        <div class="form-fields d-flex gap-3">
                            <select class="field" id="bulkActions" name="bulk_actions" required>
                                <option value="" disabled selected>Bulk Actions</option>
                                <option value="delete">Delete</option>
                                <option value="active">Make Active</option>
                                <option value="inactive">Make Inactive</option>
                            </select>
                            <button type="submit" onclick="confirmBulkAction(event)" class="themeBtn">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-5 mb-3">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="no-sort">
                                <div class="selection select-all-container"><input type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($systemAuthor)
                            <tr>
                                <td>
                                    <div class="selection item-select-container">
                                        <input type="checkbox" disabled style="pointer-events: none;">
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.tour-authors.edit', $systemAuthor->id) }}" class="link">
                                        {{ $systemAuthor->name }}
                                    </a>
                                </td>
                                <td class="p-0">
                                    <span class="badge rounded-pill bg-warning">
                                        default
                                    </span>
                                </td>
                                <td>{{ formatDateTime($systemAuthor->created_at) }}</td>
                            </tr>
                        @endif
                        @foreach ($authors as $author)
                            <tr>
                                <td>
                                    <div class="selection item-select-container">
                                        <input type="checkbox" class="bulk-item" name="bulk_select[]"
                                            value="{{ $author->id }}">
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.tour-authors.edit', $author->id) }}" class="link">
                                        {{ $author->name }}
                                    </a>
                                </td>
                                <td class="p-0">
                                    <span
                                        class="badge rounded-pill bg-{{ $author->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $author->status }}
                                    </span>
                                </td>
                                <td>{{ formatDateTime($author->created_at) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
