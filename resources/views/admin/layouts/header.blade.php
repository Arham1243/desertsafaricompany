<div class="dashboard-header-wrapper">
    <div class="row g-0">
        <div class="col-md-9">
            <div class="dashboard-header">
                <div class="row justify-content-between">
                    <div class="col-md-4">
                        <h2>Dashboard</h2>
                    </div>
                    <div class="col-md-5">
                        <form class="input-search">
                            <button class="search-icon"><i class='bx bx-search'></i></button>
                            <input type="text" placeholder="search" name="search">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-header  d-flex justify-content-between pe-2"
                style=" padding-left: 0 !important;  padding-right: 0.5rem !important; padding-bottom: 0 !important;">
                <div class="notifi-icon">
                    <i class='bx bxs-bell-ring bx-tada'></i>
                    <div class="notification-count">5</div>
                </div>
                <div class="user-profile">
                    <div class="name">
                        <div class="name1">{{ Auth::guard('admin')->user()->email }}</div>
                        <div class="role">{{ Auth::guard('admin')->user()->name }}</div>
                    </div>
                    <div class="user-image-icon">
                        <i class='bx bxs-user-circle'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
