@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-availability.index') }}

            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-box">
                        <div class="form-box__header">
                            <div class="title">Tours</div>
                        </div>
                        <div class="form-box__body p-0">
                            <ul class="settings">
                                @php
                                    $selectedTourId = null;
                                @endphp
                                @foreach ($tours as $tour)
                                    @php
                                        if (request('tour_id') == $tour->id || (!request('tour_id') && $loop->first)) {
                                            $selectedTourId = $tour->id;
                                        }
                                    @endphp
                                    <li class="settings-item">
                                        <a href="{{ Request::url() . '?tour_id=' . $tour->id }}"
                                            data-tour-id="{{ $tour->id }}"
                                            class="settings-item__link 
                                               @if ($selectedTourId == $tour->id) ) 
                                                   active @endif">
                                            {{ $tour->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="form-box form-box--calendar">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" id="eventModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        Date Information:
                        <div id="selectedDateInfo"></div>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.tour-availability.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="tour_id" id="tourId" value="{{ $selectedTourId }}">
                        <input type="hidden" name="start_date" id="startDate">
                        <input type="hidden" name="end_date" id="endDate">
                        <div class="row align-items-center mt-3">
                            <div class="col-md-6">
                                <div class="form-fields">
                                    <label class="title">Date Range:</label>
                                    <input type="text" name="dates" class="field date-range-picker" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available_for_booking"
                                        id="available_for_booking" checked value="1">
                                    <label class="form-check-label" for="available_for_booking">Available for
                                        booking?</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-fields">
                                    <label class="title">Max Guest <span class="text-danger">*</span>:</label>
                                    <input type="number" min="0" name="max_guest" class="field" id="maxGuest"
                                        required="">
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="repeater-table form-fields">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Name</th>
                                                <th scope="col">Min</th>
                                                <th scope="col">Max</th>
                                                <th scope="col">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" name="adult[name]" class="field" value="adult"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="adult[min]" class="field"
                                                        id="adultMin">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="adult[max]" class="field"
                                                        id="adultMax">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0" name="adult[price]"
                                                        class="field" id="adultPrice">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="child[name]" class="field"
                                                        value="child" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="child[min]"
                                                        class="field" id="childMin">
                                                </td>
                                                <td>
                                                    <input type="number" min="0" name="child[max]"
                                                        class="field" id="childMax">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0"
                                                        name="child[price]" class="field" id="childPrice">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="themeBtn bg-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="themeBtn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $selectedTour = \App\Models\Tour::where('id', $selectedTourId)->first();
        $availabilitiesJson = $selectedTour ? $selectedTour->availabilities->toJson() : '[]';
    @endphp
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/fullcalendar.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/momentjs/2.29.1/moment.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        const availabilities = {!! $availabilitiesJson !!};

        function formatDate(date) {
            return new Date(date).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function getDateRange(startDate, endDate) {
            const dates = [];
            let currentDate = new Date(startDate);
            const endDateObj = new Date(endDate);

            while (currentDate <= endDateObj) {
                dates.push(new Date(currentDate));
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return dates;
        }

        function setModalData(date, event) {
            $('#selectedDateInfo').html(formatDate(date));
            $('#startDate').val(date);
            $('#endDate').val(date);

            if (event) {
                $('#maxGuest').val(event.extendedProps.max_guest);
                $('#adultMin').val(event.extendedProps.min_adult);
                $('#adultMax').val(event.extendedProps.max_adult);
                $('#adultPrice').val(event.extendedProps.adult_price);
                $('#childMin').val(event.extendedProps.min_child);
                $('#childMax').val(event.extendedProps.max_child);
                $('#childPrice').val(event.extendedProps.child_price);
                $('#available_for_booking').prop('checked', event.extendedProps.available_for_booking == 1);
            } else {
                $('#maxGuest, #adultMin, #adultMax, #adultPrice, #childMin, #childMax, #childPrice').val('');
                $('#available_for_booking').prop('checked', false);
            }
        }

        function initializeDateRangePicker(selectedDate) {
            $('.date-range-picker').daterangepicker({
                startDate: selectedDate,
                endDate: selectedDate,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                opens: 'left'
            }).on('apply.daterangepicker', function(ev, picker) {
                $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
                $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const events = availabilities.flatMap(av => {
                return getDateRange(av.start_date, av.end_date).map(date => ({
                    title: `Adult: ${av.adult_price}<br>Child: ${av.child_price}<br>Max Guests: ${av.max_guest}`,
                    date: date.toISOString().split('T')[0],
                    extendedProps: {
                        max_guest: av.max_guest,
                        min_adult: av.min_adult,
                        max_adult: av.max_adult,
                        adult_price: av.adult_price,
                        min_child: av.min_child,
                        max_child: av.max_child,
                        child_price: av.child_price,
                        available_for_booking: av.available_for_booking,
                        start_date: av.start_date,
                        end_date: av.end_date,
                    }
                }));
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                eventContent: function(arg) {
                    return {
                        html: arg.event.extendedProps.available_for_booking === 0 ?
                            '<span class="not-available">Not Available</span>' :
                            `<span>${arg.event.title}</span>`
                    };
                },
                dateClick: function(info) {
                    const selectedDate = info.dateStr;
                    const clickedEvent = events.find(event => event.date === selectedDate);
                    setModalData(selectedDate, clickedEvent);
                    $('#eventModal').modal('show');
                    initializeDateRangePicker(selectedDate);
                },
                eventClick: function(info) {
                    const selectedDate = new Date(info.event.startStr).toISOString().split('T')[0];
                    setModalData(selectedDate, info.event);
                    $('#eventModal').modal('show');
                    initializeDateRangePicker(selectedDate);
                }
            });

            calendar.render();
        });

        $(document).ready(function() {
            $('#eventModal').on('hidden.bs.modal', function() {
                $('#available_for_booking').prop('checked', false);
                $('#maxGuest, #adultMin, #adultMax, #adultPrice, #childMin, #childMax, #childPrice, #startDate, #endDate')
                    .val('');
            });
        });
    </script>
@endpush
