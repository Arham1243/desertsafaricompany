<a href="javascript:void(0)" class="whatsapp-contact"><i class='bx bxl-whatsapp'></i></a>

<header class="header">
    <div class="container">
        <div class="header-main">
            <div class="header-content">
                <div class="header-logo">
                    <a href="{{ route('index') }}"> <img
                            src='{{ asset($logo->img_path ?? 'frontend/assets/images/logo (1).webp') }}' alt='image'
                            class='imgFluid' width="112.03" height="33.69"></a>
                </div>
                <div class="header-nav">
                    <ul>
                        <li><a href="{{ route('tours.index') }}">Tours</a></li>
                        <li><a href="#">Local Guide</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </div>
            </div>
            <div class="header-btns">
                <ul class="header-btns__list">
                    <li class="header-btns__item">
                        @include('frontend.auth.popup')
                    </li>

                    <li class="header-btns__item">
                        <a href="{{ route('wishlist') }}" title="Wishlist" class="li__link">
                            <div class="header-btns__icon">
                                <i class='bx bx-heart'></i>
                            </div>
                            <span>Wishlist</span>
                        </a>
                    </li>
                    <li class="header-btns__item">
                        <a href="{{ route('cart') }}" title="Cart" class="li__link">
                            <div class="header-btns__icon">
                                <i class='bx bx-cart'></i>
                            </div>
                            <span>Cart</span>
                        </a>
                    </li>
                    <li class="header-btns__item">
                        <a href="#" title="Profile" class="li__link">
                            <div class="header-btns__icon">
                                <i class='bx bx-user'></i>
                            </div>
                            <span>Profile</span>
                        </a>
                        <div class="drop-down">
                            <ul class="drop-down__list">
                                @if (!Auth::check())
                                    <li>
                                        <a href="javascript:void(0)" class="loginBtn"><i
                                                class='bx bx-log-in-circle'></i>Log in or sign up</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="#">Currency</a>
                                </li>
                                <li>
                                    <a href="#">Language</a>
                                </li>
                                <li>
                                    <a href="#"><i class='bx bx-sun'></i>Appearance</a>
                                </li>
                                <li>
                                    <a href="#"><i class='bx bx-help-circle'></i>Support</a>
                                </li>
                                <li>
                                    <a href="#"><i class='bx bx-mobile-alt'></i>Download the app</a>
                                </li>
                                @if (Auth::check())
                                    <li>
                                        <a onclick="return confirm('Are you sure you want to Logout?')"
                                            href="{{ route('auth.logout') }}"><i
                                                class='bx bx-log-out-circle'></i>Logout</a>
                                    </li>
                                @endif
                            </ul>
                        </div>

            </div>


            <a href="javascript:void(0)" class="header-main__menu" onclick="openSideBar()">
                <i class="bx bx-menu"></i>
            </a>

        </div>

    </div>


</header>

<div class="sideBar" id="sideBar">
    <a href="javascript:void(0)" class="sideBar__close" onclick="closeSideBar()">×</a>
    <a href="{{ route('index') }}" class="sideBar__logo">
        <img alt="Logo" class="imgFluid" src="{{ asset('frontend/frontend/assets/images/logo (1).webp') }}">
    </a>
    <ul class="sideBar__nav">
        <li><a href="{{ route('tours.index') }}">Tours</a></li>
        <li><a href="#">Local Guide</a></li>
        <li><a href="#">Help</a></li>

        <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
        <li><a href="{{ route('cart') }}">Cart</a></li>
        <li class="drop-down--toggle"><a class="dropdown-click-link" href="javascript:void(0)">Profile<i
                    class="bx bx-chevron-down"></i></a>
            <div class="toggle-wrapper">
                <div class="drop-down drop-down--alt">
                    <ul class="drop-down__list">
                        @if (!Auth::check())
                            <li>
                                <a href="javascript:void(0)" class="loginBtn"><i class='bx bx-log-in-circle'></i>Log
                                    in or sign up</a>
                            </li>
                        @else
                            <li>
                                <a onclick="return confirm('Are you sure you want to Logout?')"
                                    href="{{ route('auth.logout') }}"><i class='bx bx-log-out-circle'></i>Logout</a>
                            </li>
                        @endif
                        <li>
                            <a href="#"><i class='bx bx-log-in-circle'></i>Log in or sign up</a>
                        </li>
                        <div class="drop-down__list--line"></div>
                        <li>
                            <a href="#">Currency</a>
                        </li>
                        <div class="drop-down__list--line"></div>
                        <li>
                            <a href="#">Language</a>
                        </li>
                        <div class="drop-down__list--line"></div>
                        <li>
                            <a href="#"><i class='bx bx-sun'></i>Appearance</a>
                        </li>
                        <div class="drop-down__list--line"></div>
                        <li>
                            <a href="#"><i class='bx bx-help-circle'></i>Support</a>
                        </li>
                        <div class="drop-down__list--line"></div>
                        <li>
                            <a href="#"><i class='bx bx-mobile-alt'></i>Download the app</a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>

    </ul>
</div>
