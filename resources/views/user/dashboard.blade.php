@extends('user.layouts.main')
@section('content')
    <div class="col-md-9">
        <div class="dashboard-content">
            <div class="revenue">
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Quick Links</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('user.bookings.index') }}" class="revenue-card">
                            <div class="revenue-card__icon"><i class='bx bx-lg bxs-calendar-check'></i></div>
                            <div class="revenue-card__content">
                                <div class="title">View all your booked tours</div>
                                <div class="num">My Bookings</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
