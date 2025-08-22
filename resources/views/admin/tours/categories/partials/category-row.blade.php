<tr>
    <td>
        <div class="selection item-select-container">
            <input type="checkbox" class="bulk-item" name="bulk_select[]" value="{{ $category->id }}">
        </div>
    </td>
    <td>
        <a href="{{ route('admin.tour-categories.edit', $category->id) }}" class="link">
            {!! str_repeat('&nbsp;&nbsp;', $level) !!} {{ str_repeat('-', $level) }} {{ $category->name }}
        </a>
    </td>
    <td class="p-0">
        <span class="badge rounded-pill bg-{{ $category->status == 'publish' ? 'success' : 'warning' }}">
            {{ $category->status }}
        </span>
    </td>
    <td>{{ formatDateTime($category->created_at) }}</td>
    <td>
        <div class="dropstart bootsrap-dropdown">
            <button type="button" class="recent-act__icon dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class='bx bx-dots-horizontal-rounded'></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.tour-categories.edit', $category->id) }}">
                        <i class='bx bxs-edit'></i>
                        Edit
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.tour-categories.duplicate', $category->id) }}"
                        onclick="return confirm('Are you sure you want to duplicate this category?')">
                        <i class='bx bxs-copy'></i>
                        Duplicate </a>
                </li>
            </ul>
        </div>
    </td>
</tr>

@foreach ($category->children as $child)
    @include('admin.tours.categories.partials.category-row', ['category' => $child, 'level' => $level + 1])
@endforeach
