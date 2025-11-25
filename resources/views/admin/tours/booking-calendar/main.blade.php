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
                        <div class="form-box__body form-box__body-scroll  p-0">
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
                                               @if ($selectedTourId == $tour->id) active @endif">
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
        $bookingsJson = json_encode($bookings ?? []);
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
                    title: `Order #${event.order_id}`,
                    date: event.booking_confirm_date,
                    extendedProps: {
                        customer: event.customer_name,
                        price: event.total_price,
                        status: event.payment_status,
                        method: event.payment_type
                    },
                    backgroundColor: '#00376b',
                    borderColor: '#00376b'
                };
            });

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: events,
                eventContent: function(arg) {
                    const statusText = arg.event.extendedProps.method ?
                        `${arg.event.extendedProps.status} (${arg.event.extendedProps.method})` :
                        arg.event.extendedProps.status;

                    return {
                        html: `<div style="padding: 2px 4px;">
                   <div style="font-weight: bold; font-size: 12px;">${arg.event.title}</div>
                   <div style="font-size: 11px;">${arg.event.extendedProps.customer}</div>
                   <div style="font-size: 11px; text-transform:capitalize;">${statusText}</div>
               </div>`
                    };
                },
                eventClick: function(info) {
                    const orderId = info.event.title.replace('Order #', '');
                    window.open(`{{ route('admin.bookings.index') }}/${orderId}/edit`, '_blank');
                }
            });

            calendar.render();
        });
    </script>
@endpush
