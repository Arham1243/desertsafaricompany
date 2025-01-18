@if (isset($content->is_form_enabled))
    @if ($content->form_type === 'normal')
        <div class="col-md-12">
            <form action="{{ route('tours.search') }}" class="banner-search auto-submit-form" id="destination-wrapper">
                <i class="bx bx-search"></i>
                <select multiple placeholder="Where are you going?" class="banner-search__input" name="resource_id"
                    id="destination" style="width: 100%"></select>
                <input type="hidden" name="resource_type" id="resource_type">
            </form>
        </div>
    @elseif($content->form_type === 'date_selection')
        <div class="col-md-9">
            <div class="date-search">
                <form action="{{ route('tours.search') }}" class="date-search__btns"
                    style="background-color: {{ $content->date_selection_background_color ?? '' }}">
                    <input type="hidden" name="resource_type" id="resource_type">
                    <label for="destination" id="destination-wrapper" class="date-search__btn">
                        <div class="first-half">
                            <i class='bx bxs-map'></i>
                        </div>
                        <div class="second-half">
                            <span class="top-label">Going to</span>
                            <div class="content">
                                <select multiple placeholder="Where are you going?" name="resource_id" id="destination"
                                    style="width: 100%"> </select>
                            </div>
                        </div>
                    </label>
                    <button class="date-search__btn date-range-picker" type="button">
                        <div class="first-half">
                            <i class='bx bxs-calendar'></i>
                        </div>
                        <div class="second-half">
                            <span class="top-label">Tour Date</span>
                            <div class="content d-flex align-items-center">
                                <div class="date-wrapper">
                                    <input readonly="" type="text" class="date" name="start_date"
                                        id="startDate" />
                                </div>
                                <div class="date-wrapper">
                                    <input readonly="" type="text" class="date" name="end_date"
                                        id="endDate" />
                                </div>
                            </div>
                        </div>
                    </button>
                    <button type="submit" class="primary-btn">
                        <i class="bx bx-search"></i><span>Search</span>
                    </button>
                </form>
            </div>
        </div>
    @elseif($content->form_type === 'single_date_selection')
        <div class="col-md-9">
            <div class="date-search">
                <form action="{{ route('tours.search') }}" class="date-search__btns"
                    style="background-color: {{ $content->single_date_selection_background_color ?? '' }}">
                    <input type="hidden" name="resource_type" id="resource_type">
                    <label for="destination" id="destination-wrapper" class="date-search__btn">
                        <div class="first-half">
                            <i class='bx bxs-map'></i>
                        </div>
                        <div class="second-half">
                            <span class="top-label">Going to</span>
                            <div class="content">
                                <select multiple placeholder="Where are you going?" name="resource_id" id="destination"
                                    style="width: 100%"> </select>
                            </div>
                        </div>
                    </label>
                    <button class="date-search__btn date-single-picker" type="button">
                        <div class="first-half">
                            <i class='bx bxs-calendar'></i>
                        </div>
                        <div class="second-half">
                            <span class="top-label">Tour Date</span>
                            <div class="content d-flex align-items-center">
                                <div class="date-wrapper">
                                    <input readonly="" type="text" class="date" name="start_date"
                                        id="startDate" />
                                </div>
                            </div>
                        </div>
                    </button>
                    <button type="submit" class="primary-btn">
                        <i class="bx bx-search"></i><span>Search</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
@endif


@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        function initializeDateRangePicker(selectedDate) {
            $("#startDate").val(selectedDate);
            $("#endDate").val(selectedDate);
            $(".date-range-picker")
                .daterangepicker({
                    locale: {
                        format: "MMM D, YYYY",
                    },
                    opens: "center",
                })
                .on("apply.daterangepicker", function(ev, picker) {
                    $("#startDate").val(picker.startDate.format("MMM D, YYYY"));
                    $("#endDate").val(picker.endDate.format("MMM D, YYYY"));
                });
        }

        function initializeSingleDatePicker(selectedDate) {
            $("#startDate").val(selectedDate);
            $(".date-single-picker")
                .daterangepicker({
                    singleDatePicker: true,
                    autoApply: true,
                    locale: {
                        format: "MMM D, YYYY",
                    },
                    opens: "center",
                    parentEl: ".date-single-picker",
                })
                .on("apply.daterangepicker", function(ev, picker) {
                    $("#startDate").val(picker.startDate.format("MMM D, YYYY"));
                });
        }

        const todayForSearch = new Date();
        const formattedDate = todayForSearch.toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });

        document.addEventListener("DOMContentLoaded", function() {
            initializeDateRangePicker(formattedDate);
            initializeSingleDatePicker(formattedDate);
        });

        $('#destination').select2({
            placeholder: 'Where are you going?',
            dropdownParent: $('#destination-wrapper'),
            minimumInputLength: 1,
            maximumSelectionLength: 1,
            ajax: {
                url: '{{ route('search.suggestions') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data, params) {
                    // Pass the search term in `params` to use later
                    return {
                        results: Object.values(data.results).map(item => ({
                            id: item.id,
                            type: item.type,
                            text: item.text,
                            searchTerm: params.term // Attach the term to the result
                        }))
                    };
                },
                cache: true
            },
            templateResult: function(data) {
                if (data.text) {
                    // Use the passed search term
                    var query = data.searchTerm || '';

                    // Escape special characters in the query
                    var escapedTerm = query.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');

                    // Highlight matching parts
                    var highlightedText = data.text.replace(new RegExp('(' + escapedTerm + ')', 'gi'),
                        '<strong class="highlighted">$1</strong>');

                    return $('<span>').html(highlightedText);
                }
                return null;
            },
            templateSelection: function(data) {
                return data.text || data.id;
            }
        });

        $('#destination').on('select2:select', function(e) {
            var selectedData = e.params.data;
            var resourceType = selectedData.type;
            $('#resource_type').val(resourceType);
            $('.auto-submit-form').submit();
        });

        $('#destination-wrapper').on('click focus', function() {
            $('#destination').select2('open');
        });
    </script>
@endpush
