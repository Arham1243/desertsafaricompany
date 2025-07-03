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
</tr>

@foreach ($category->children as $child)
    @include('admin.tours.categories.partials.category-row', ['category' => $child, 'level' => $level + 1])
@endforeach
