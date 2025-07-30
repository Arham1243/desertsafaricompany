<div class="col-12">
    <div class="dashboard-header-wrapper">
        <div class="row g-0">
            <div class="col-md-9">
                <div class="dashboard-header">
                    <div class="row g-0 justify-content-between">
                        <div class="col-md-4">
                            <div class="global-heading-wrapper">
                                <div id="sidebarToggle" class="sidebar-toggle"><i class='bx bx-menu'></i></div>
                                <h2>Dashboard</h2>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="notifi-icon ms-auto">
                                <i class='bx bxs-bell'></i>
                                <div class="notification-count">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-header  d-flex justify-content-end p-0">
                    <div class="user-profile dropdown">
                        <div class="name">
                            <div class="name1">{{ Auth::guard('admin')->user()->email }}</div>
                            <div class="role">{{ Auth::guard('admin')->user()->name }}</div>
                        </div>
                        <div class="user-image-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
                            role="button">
                            <i class='bx bxs-user-circle'></i>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
