@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tour-bookings.index') }}

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

    @php
        $selectedTour = \App\Models\Tour::where('id', $selectedTourId)->first();
        $bookingsJson = json_encode([
            [
                'order_id' => '12345',
                'booking_confirm_date' => '2024-10-01',
            ],
            [
                'order_id' => '12345',
                'booking_confirm_date' => '2024-10-05',
            ],
            [
                'order_id' => '12345',
                'booking_confirm_date' => '2024-10-10',
            ],
            [
                'order_id' => '12345',
                'booking_confirm_date' => '2024-10-10',
            ],
            [
                'order_id' => '12346',
                'booking_confirm_date' => '2024-10-02',
            ],
            [
                'order_id' => '12347',
                'booking_confirm_date' => '2024-10-03',
            ],
        ]);
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
        const eventsJson = {!! $bookingsJson !!};

        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const events = eventsJson.map(event => {
                return {
                    title: `Booking: ${event.order_id}`,
                    date: new Date(event.booking_confirm_date).toISOString().split('T')[
                        0],
                };
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                eventContent: function(arg) {
                    return {
                        html: `<span>${arg.event.title}</span>`
                    };
                }
            });

            calendar.render();
        });
    </script>
@endpush
