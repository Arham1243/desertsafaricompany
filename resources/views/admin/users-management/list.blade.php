@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            <div class="custom-sec">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">{{ isset($title) ? $title : '' }}</h3>
                    </div>
                </div>
                <div class="table-container table-responsive">
                    <table class="custom-table data-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Signup Method</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $user->signup_method }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ date('d-M-Y', strtotime($user->created_at)) }}</td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-{{ $user->is_active == 1 ? 'success' : 'danger' }}">
                                            {{ $user->is_active == 1 ? 'Active' : 'Non-Active' }}</span>
                                    </td>
                                    <td>
                                        <div class="dropstart">
                                            <button type="button" class="recent-act__icon dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <lord-icon src="https://cdn.lordicon.com/jpgpblwn.json" trigger="hover">
                                                </lord-icon>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.editUser', $user->id) }}">
                                                        <lord-icon
                                                            src="https://media.lordicon.com/icons/system/solid/92-visability.json"
                                                            trigger="loop" delay="2000">
                                                        </lord-icon>
                                                        View Details
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.suspendUser', $user->id) }}">


                                                        <lord-icon
                                                            src="https://media.lordicon.com/icons/wired/outline/2465-restriction.json"
                                                            trigger="loop" delay="2000"
                                                            colors="primary:#000,secondary:#000">
                                                        </lord-icon>


                                                        {{ $user->is_active != 0 ? 'Suspend' : 'Activate' }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        onclick="return confirm('Are you sure you want to delete')"
                                                        href="{{ route('admin.deleteUser', $user->id) }}">

                                                        <lord-icon src="https://cdn.lordicon.com/skkahier.json"
                                                            trigger="loop" delay="2000">
                                                        </lord-icon>

                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
