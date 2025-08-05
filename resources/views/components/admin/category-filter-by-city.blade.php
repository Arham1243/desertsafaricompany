@props([
    'cities' => [],
    'categories' => [],
    'fieldName' => '',
    'selectedCityId' => null,
    'selectedCategoryId' => null,
    'citySelectId' => 'filter-categories-by-city-' . Str::random(5),
])

<div x-data="categoryFilter()" x-init="init()" class="row">
    <div class="col-md-6 col-12 mt-4">
        <div class="form-fields mb-4">
            <label class="title">City:</label>
            <select class="select2-select" id="{{ $citySelectId }}">
                <option value="">Select City</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ $selectedCityId == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6 col-12 mt-4">
        <div class="form-fields">
            <label class="title">Select Category:</label>
            <select name="{{ $fieldName }}" x-html="categoryOptions" class="select2-select" data-error="Category"
                should-sort="false">
                <option value="" disabled>Select Category</option>
                {!! renderCategories($categories, $selectedCategoryId) !!}
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
                selectedCity: '{{ $selectedCityId }}',
                categoryOptions: `<option value="" disabled>Select Category</option>`,

                init() {
                    const citySelect = document.getElementById('{{ $citySelectId }}');
                    $(citySelect).off('change').on('change', (e) => {
                        const newCity = e.target.value;
                        if (this.selectedCity !== newCity) {
                            this.selectedCity = newCity;
                            this.fetchCategories();
                        }
                    });

                    if (this.selectedCity) {
                        this.fetchCategories();
                    } else {
                        initializeSelect2();
                    }
                },

                fetchCategories() {
                    if (!this.selectedCity) {
                        this.categoryOptions = `<option value="" disabled selected>Select Category</option>`;
                        this.$nextTick(() => initializeSelect2());
                        return;
                    }

                    fetch(`{{ url('admin/tour-categories/city') }}/${this.selectedCity}`)
                        .then(res => res.json())
                        .then(data => {
                            this.categoryOptions = this.buildOptions(data);
                            this.$nextTick(() => initializeSelect2());
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
                            const selected = cat.id == '{{ $selectedCategoryId }}' ? 'selected' : '';
                            const option =
                                `<option value="${cat.id}" ${selected}>${padding} ${cat.name}</option>`;
                            return [option, ...build(cat.children, level + 1)];
                        });
                    };

                    return `<option value="" disabled>Select Category</option>` + build(roots).join('');
                }
            }
        }
    </script>
@endpush
