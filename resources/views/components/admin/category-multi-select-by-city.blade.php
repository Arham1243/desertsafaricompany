@props([
    'cities' => [],
    'categories' => [],
    'fieldName' => '',
    'selectedCategoryIds' => [],
    'isCategoryRequired' => false,
    'cityLabel' => 'Filter by City',
    'categoryLabel' => 'Select Category',
    'citySelectId' => 'filter-categories-by-city-' . Str::random(5),
    'categorySelectId' => 'filter-categories-by-city-' . Str::random(5),
])

<div x-data="categoryFilter()" x-init="init()" class="row">
    <div class="col-md-6 col-12 mt-4">
        <div class="form-fields mb-4">
            <label class="title">{{ $cityLabel }}:</label>
            <select class="select2-select" id="{{ $citySelectId }}">
                <option value="" selected disabled>Select</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}">
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-12 mt-4">
        <div class="form-fields">
            <label class="title">{{ $categoryLabel }} @if ($isCategoryRequired)
                    <span class="text-danger"> *</span>
                @endif:</label>
            <select placeholder="Select" data-error="Category" {{ $isCategoryRequired ? 'data-required' : '' }}
                name="{{ $fieldName }}" x-html="categoryOptions" class="select2-select" data-error="Category"
                should-sort="false" multiple id="{{ $categorySelectId }}">
                {!! renderCategoriesMulti($categories, $selectedCategoryIds) !!}
            </select>
            @error($fieldName)
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

@push('js')
    <script>
        function categoryFilter() {
            return {
                categoryOptions: `<option value="">Select</option>`,
                categorySelect: null,
                selectedCategoryIds: @json($selectedCategoryIds),

                init() {
                    this.categorySelect = document.getElementById('{{ $categorySelectId }}');
                    const citySelect = document.getElementById('{{ $citySelectId }}');

                    $(citySelect).off('change').on('change', (e) => {
                        const newCity = e.target.value || '';
                        if (this.selectedCity !== newCity) {
                            this.selectedCity = newCity;
                            this.fetchCategories();
                        }
                    });

                    this.fetchCategories();
                },

                fetchCategories() {
                    this.categorySelect.disabled = true;

                    fetch(`{{ url('admin/tour-categories/city') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                city_id: this.selectedCity || ''
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.length) {
                                this.categoryOptions = ``;
                            } else {
                                this.categoryOptions = this.buildOptions(data);
                            }
                            this.$nextTick(() => {
                                $(this.categorySelect).select2('destroy').select2();
                                this.categorySelect.disabled = false;
                            });
                        });
                },

                buildOptions(categories) {
                    const map = {},
                        roots = [];

                    categories.forEach(cat => {
                        cat.children = [];
                        map[cat.id] = cat;
                    });

                    categories.forEach(cat => {
                        if (cat.parent_category_id && map[cat.parent_category_id]) {
                            map[cat.parent_category_id].children.push(cat);
                        } else {
                            roots.push(cat);
                        }
                    });

                    const build = (cats, level = 0) => {
                        return cats.flatMap(cat => {
                            const padding = '&nbsp;&nbsp;'.repeat(level) + '-'.repeat(level);
                            const selected = this.selectedCategoryIds.includes(cat.id) ? 'selected' : '';
                            return [`<option value="${cat.id}" ${selected}>${padding} ${cat.name}</option>`, ...
                                build(cat.children, level + 1)
                            ];
                        });
                    };

                    return `<option value="" disabled>Select</option>` + build(roots).join('');
                }
            }
        }
    </script>
@endpush
