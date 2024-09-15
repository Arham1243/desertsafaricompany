<div class="table-container universal-table">
    <div class="custom-sec">
        <form method="POST" action="{{ route('admin.bulk-actions', ['resource' => 'blogs-tags']) }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-7">
                    <div class="custom-form">
                        <div class="form-fields d-flex gap-3">
                            <select class="field" name="bulk_actions" required>
                                <option value="" disabled selected>Bulk Actions</option>
                                <option value="delete">Delete</option>
                            </select>
                            <button class="themeBtn">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="no-sort">
                                <div class="selection select-all-container"><input type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    <div class="selection item-select-container"><input type="checkbox"
                                            class="bulk-item" name="bulk_select[]" value="{{ $tag->slug }}"></div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.blogs-tags.edit', $tag->slug) }}"
                                        class="link">{{ $tag->name }}</a>
                                </td>
                                <td>{{ $tag->slug }}</td>
                                <td>{{ $tag->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </form>
    </div>
</div>
