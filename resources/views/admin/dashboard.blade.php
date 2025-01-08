@extends('admin.layouts.main')
@section('content')
    <div class="col-md-9">
        <div class="dashboard-content">
            <div class="revenue">
                <div class="row">
                    <div class="col-md-4">
                        <div class="revenue-card">
                            <div class="revenue-card__icon"><i class='bx bxs-group'></i></div>
                            <div class="revenue-card__content">
                                <div class="title">Verified vendors</div>
                                <div class="num">{{ count($users) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="revenue-card">
                            <div class="revenue-card__icon"><i class='bx bxs-plane'></i></div>
                            <div class="revenue-card__content">
                                <div class="title">Active Tours</div>
                                <div class="num">{{ count($tours) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="revenue-card">
                            <div class="revenue-card__icon"><i class='bx bx-dollar'></i></div>
                            <div class="revenue-card__content">
                                <div class="title">Monthly Online Payments</div>
                                <div class="num">{{ env('APP_CURRENCY') }}100000</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-sec">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">recent posts</h3>
                    </div>
                    <div class="custom-filters">
                        <div class="view-all">Sort by:</div>
                        <select name="sort_by" class="button-dropdown">
                            <option value="Latest">Latest</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="custom-card">
                            <a href="#" class="custom-card__img">
                                <div class="rating">
                                    <i class='bx bxs-star'></i>
                                    <div class="participant">4,9</div>
                                </div>
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="" class="imgFluid">
                            </a>
                            <div class="custom-card__content">
                                <div class="title">Bali, Indonesia</div>
                                <div class="duration">10 Days, 9 Nights</div>

                                <div class="card-bottom">
                                    <div class="price">$1,800</div>
                                    <a href="#" class="themeBtn">View </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-card">
                            <a href="#" class="custom-card__img">

                                <div class="rating">
                                    <i class='bx bxs-star'></i>
                                    <div class="participant">4,9</div>
                                </div>
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="" class="imgFluid">
                            </a>
                            <div class="custom-card__content">
                                <div class="title">Bali, Indonesia</div>
                                <div class="duration">10 Days, 9 Nights</div>

                                <div class="card-bottom">
                                    <div class="price">$1,800</div>
                                    <a href="#" class="themeBtn">View </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="custom-card">
                            <a href="#" class="custom-card__img">

                                <div class="rating">
                                    <i class='bx bxs-star'></i>
                                    <div class="participant">4,9</div>
                                </div>
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="" class="imgFluid">
                            </a>
                            <div class="custom-card__content">
                                <div class="title">Bali, Indonesia</div>
                                <div class="duration">10 Days, 9 Nights</div>

                                <div class="card-bottom">
                                    <div class="price">$1,800</div>
                                    <a href="#" class="themeBtn">View </a>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="custom-sec">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Today Bookings</h3>
                    </div>
                    <a href="#" class="custom-link">View All</a>
                </div>
                <div class="table-container table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Destination</th>
                                <th>Date & Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-danger">Cancelled</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-success">Completed</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-success">Completed</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Pending Bookings</h3>
                    </div>
                    <a href="#" class="custom-link">View All</a>
                </div>
                <div class="table-container table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Destination</th>
                                <th>Date & Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Elena Rodriguez</td>
                                <td>Santorini, Greece</td>
                                <td>15 - 22 Apr 2024</td>
                                <td>$2,500</td>
                                <td>
                                    <span class="badge rounded-pill bg-warning">Pending</span>
                                </td>
                                <td>
                                    <div class="dropstart bootsrap-dropdown">
                                        <button type="button" class="recent-act__icon dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">
                                                    <i class='bx bxs-show'></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)">


                                                    <i class='bx bx-x'></i>

                                                    Suspend
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    onclick="return confirm('Are you sure you want to delete')"
                                                    href="javascript:void(0)">

                                                    <i class='bx bxs-trash-alt'></i>

                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="pe-2">
            <div class="recent-act">
                <div class="recent-act__header">
                    <div class="title">Recent Inquiry</div>
                </div>
                <ul class="activity-log">
                    <li class="activity-log__item ">
                        <span class="icon"><i class='bx bx-calendar'></i></span>
                        <div class="info">
                            <a href="#" class="title line-hide-1">Bill Trevor (admin) updated the - Bill Trevor </a>
                            <div class="date">23 May 2024</div>
                        </div>
                    </li>
                    <li class="activity-log__item ">
                        <span class="icon"><i class='bx bx-calendar'></i></span>
                        <div class="info">
                            <a href="#" class="title line-hide-1">Bill Trevor (admin) updated the - Bill Trevor </a>
                            <div class="date">23 May 2024</div>
                        </div>
                    </li>
                    <li class="activity-log__item ">
                        <span class="icon"><i class='bx bx-calendar'></i></span>
                        <div class="info">
                            <a href="#" class="title line-hide-1">Bill Trevor (admin) updated the - Bill Trevor </a>
                            <div class="date">23 May 2024</div>
                        </div>
                    </li>
                    <li class="activity-log__item ">
                        <span class="icon"><i class='bx bx-calendar'></i></span>
                        <div class="info">
                            <a href="#" class="title line-hide-1">Bill Trevor (admin) updated the - Bill Trevor </a>
                            <div class="date">23 May 2024</div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="custom-box mt-4">
                <div class="custom-box__header">
                    <div class="title">Top Destinations</div>
                    <div class="dropstart bootsrap-dropdown">
                        <button type="button" class="recent-act__icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class='bx bx-dots-horizontal-rounded'></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class='bx bx-list-ul'></i>
                                    Show by Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">

                                    <i class='bx bxs-group'></i>Show by Inquiries
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="custom-box__body">
                    <div class="customers">
                        <div class="info-wrapper">
                            <div class="customers-count">245,930</div>
                            <span>Total Customers</span>
                        </div>
                        <a href="#" class="go-arrow"><i class='bx bx-right-arrow-alt'></i></a>
                    </div>
                    <ul class="chips">
                        <li class="chip">
                            <div class="chip__img">
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="Image" class="imgFluid">
                            </div>
                            <div class="chip-content">
                                <div class="chip-content__title  line-hide-1">Bali, Indonesia</div>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="55"
                                            style="width: 55%" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="progress-value">55%</div>
                                </div>
                            </div>
                        </li>
                        <li class="chip">
                            <div class="chip__img">
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="Image" class="imgFluid">
                            </div>
                            <div class="chip-content">
                                <div class="chip-content__title  line-hide-1">Bali, Indonesia</div>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="35"
                                            style="width: 35%" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="progress-value">35%</div>
                                </div>
                            </div>
                        </li>
                        <li class="chip">
                            <div class="chip__img">
                                <img src="{{ asset('admin/assets/images/quad.jpg') }}" alt="Image" class="imgFluid">
                            </div>
                            <div class="chip-content">
                                <div class="chip-content__title  line-hide-1">Bali, Indonesia</div>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="29"
                                            style="width: 29%" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="progress-value">29%</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <a href="#" class="custom-link mt-3 text-center d-block">View All</a>
                </div>
            </div>
        </div>
    </div>
@endsection
