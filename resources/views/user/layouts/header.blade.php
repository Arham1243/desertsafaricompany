<div class="dashboard-header-wrapper">
    <div class="row g-0">
        <div class="col-md-9">
            <div class="dashboard-header">
                <div class="row justify-content-between">
                    <div class="col-md-4">
                        <div class="global-heading-wrapper">
                            <div id="sidebarToggle" class="sidebar-toggle"><i class='bx bx-menu'></i></div>
                            <h2>Dashboard</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-header  d-flex justify-content-end p-0">
                <div class="user-profile dropdown">
                    <div class="name">
                        <div class="name1">{{ Auth::user()->email }}</div>
                        <div class="role">{{ Auth::user()->full_name }}</div>
                    </div>
                    <div class="user-image-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                        role="button">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->full_name }}" class="imgFluid">
                        @else
                            <i class='bx bxs-user-circle'></i>
                        @endif
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('user.profile.index') }}" class="dropdown-item">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('user.profile.changePassword') }}" class="dropdown-item">Change
                                Password</a>
                        </li>
                        <li>
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button onclick="return confirm('Are you sure you want to logout?')" type="submit"
                                    class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
