@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-attributes.edit', $attribute) }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Edit Attribute: {{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.tour-attributes.update', $attribute->id) }}" method="POST"
                enctype="multipart/form-data" id="validation-form">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Attribute Content</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-fields">
                                        <label class="title">Name <span class="text-danger">*</span> :</label>
                                        <input type="text" name="name" class="field"
                                            value="{{ old('name', $attribute->name) }}" placeholder="Name"
                                            data-error="Name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-fields">
                                        <label class="title">Items:</label>
                                        {{-- <div class="repeater-table" data-repeater>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Title</th>
                                                        <th class="text-end" scope="col">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody data-manual-repeater-list>
                                                    @php
                                                        $items = old(
                                                            'items',
                                                            $attribute->attributeItems->pluck('item')->toArray() ?? [],
                                                        );
                                                        $items = count($items) > 0 ? $items : [''];
                                                    @endphp
                                                    @foreach ($items as $index => $item)
                                                        <tr data-manual-repeater-item>
                                                            <td>
                                                                <input name="items[]" type="text" class="field"
                                                                    value="{{ old('items.' . $index, $item) }}">
                                                                @error("items.$index")
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="delete-btn ms-auto delete-btn--static"
                                                                    data-manual-repeater-remove>
                                                                    <i class='bx bxs-trash-alt'></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button type="button" class="themeBtn ms-auto" data-manual-repeater-create>
                                                Add <i class="bx bx-plus"></i>
                                            </button>
                                        </div> --}}
                                        <div class="repeater-table" data-repeater-manual="items">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Title</th>
                                                        <th class="text-end" scope="col">Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody data-manual-repeater-list id="item-list">
                                                    @php
                                                        $items = old(
                                                            'items',
                                                            $attribute->attributeItems
                                                                ->map(function ($item) {
                                                                    return ['id' => $item->id, 'item' => $item->item];
                                                                })
                                                                ->toArray() ?? [],
                                                        );
                                                        $items =
                                                            count($items) > 0 ? $items : [['id' => '', 'item' => '']];
                                                    @endphp

                                                    @foreach ($items as $index => $item)
                                                        <tr data-manual-repeater-item data-index="{{ $index }}">
                                                            <td>
                                                                <input name="items[{{ $index }}][item]"
                                                                    type="text" class="field"
                                                                    value="{{ old('items.' . $index . '.item', $item['item']) }}"
                                                                    data-field="item" />
                                                                @error('items.' . $index . '.item')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="items[{{ $index }}][id]"
                                                                    value="{{ old('items.' . $index . '.id', $item['id']) }}" />
                                                                @if ($item['id'])
                                                                    <a href="{{ route('admin.tour-attribute-item.delete', $item['id']) }}"
                                                                        onclick="return confirm('Are you sure you want to delete?')"
                                                                        class="delete-btn ms-auto delete-btn--static">
                                                                        <i class="bx bxs-trash-alt"></i>
                                                                    </a>
                                                                @else
                                                                    <button type="button"
                                                                        class="delete-btn ms-auto delete-btn--static"
                                                                        data-manual-repeater-remove>
                                                                        <i class="bx bxs-trash-alt"></i>
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button type="button" class="themeBtn ms-auto" data-manual-repeater-create>
                                                Add <i class="bx bx-plus"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="seo-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Status</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="active"
                                            value="active"
                                            {{ old('status', $attribute->status ?? '') == 'active' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="inactive"
                                            {{ old('status', $attribute->status ?? '') == 'inactive' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="inactive">
                                            Inactive
                                        </label>
                                    </div>
                                    <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const repeaterContainers = document.querySelectorAll('[data-repeater-manual]');

            repeaterContainers.forEach(container => {
                const itemList = container.querySelector('[data-manual-repeater-list]');
                const addButton = container.querySelector('[data-manual-repeater-create]');

                addButton.addEventListener('click', function() {
                    const rows = itemList.querySelectorAll('[data-manual-repeater-item]');
                    const index = rows.length; // Use the number of current rows as the index
                    const newRow = createNewRow(index);

                    itemList.appendChild(newRow);
                });

                // Event delegation for remove buttons
                itemList.addEventListener('click', function(event) {
                    if (event.target.closest('[data-manual-repeater-remove]')) {
                        const row = event.target.closest('[data-manual-repeater-item]');
                        row.remove();
                    }
                });
            });

            function createNewRow(index) {
                const row = document.createElement('tr');
                row.setAttribute('data-manual-repeater-item', '');
                row.setAttribute('data-index', index);
                row.innerHTML = `
            <td>
                <input name="items[${index}][item]" type="text" class="field" value="" data-field="item" />
                <div class="text-danger"></div>
            </td>
            <td>
                <input type="hidden" name="items[${index}][id]" value="" />
                <button type="button" class="delete-btn ms-auto delete-btn--static" data-manual-repeater-remove>
                    <i class="bx bxs-trash-alt"></i>
                </button>
            </td>
        `;
                return row;
            }
        });
    </script>
@endpush
