<a href="javascript:void(0)" class="goUp">Whatsapp<i class='bx bx-message-rounded'></i></i></a>

<div class="login-wrapper {{ isset($openLoginPopup) ? 'open' : '' }}" id="loginPopup">
    <div class="loginSignup-popup signUpStep show for-check-email">
        <a href="javascript:void(0)" class="loginSignup-popup__close closePopup" title="close"><i class='bx bx-x'></i></a>
        <h3>Log in or sign up</h3>
        <p>Check out more easily and access your tickets on any device with your GetYourGuide account.</p>
        <div class="loginSignup-popup__buttons">
            <a href="{{ route('auth.socialite', ['social' => 'google']) }}" class="loginSignup-popup__icons">
                <div class="loginSignup-popup__img">
                    <img src="{{ asset('frontend/assets/images/google-removebg-preview.webp') }}" alt='image'
                        class='imgFluid' loading='lazy' width="27" height="27">
                </div>
            </a>

            <a href="{{ route('auth.socialite', ['social' => 'facebook']) }}" class="loginSignup-popup__icons">
                <div class="loginSignup-popup__img">
                    <img src="{{ asset('frontend/assets/images/scale_1200-removebg-preview.webp') }}" alt='image'
                        class='imgFluid' loading='lazy' width="27" height="27">
                </div>
            </a>
        </div>
        <form id="checkEmailForm">
            <div class="loginSignup-popup__email">
                <input type="email" placeholder="Email address" class="check-fields" required name="email">
            </div>
            <button type="submit" class="loginSignup-popup__btn disabled">Continue with email</button>
        </form>
    </div>

    <form action="{{ route('auth.perfrom-auth') }}" method="POST"
        class="authForm loginSignup-popup signUpStep for-sign-up">
        @csrf
        <input type="hidden" name="auth_type" value="sign_up">
        <a href="javascript:void(0)" class="loginSignup-popup__close closePopup" title="close"><i
                class='bx bx-x'></i></a>
        <h3>Create an account</h3>
        <div class="prev-data">
            <input class="prev-data__email" name="email" value="" readonly>
            <a href="{{ request()->url() }}" class="changeEmailButton">Change</a>
        </div>
        <div class="loginSignup-popup__email">
            <input type="text" placeholder="Full name" class="check-fields" name="full_name" required>
        </div>
        <div class="loginSignup-popup__email">
            <div class="password-field">
                <input type="password" placeholder="Password" class="check-fields" name="password" required>
                <button type="button" class="password-field__show"><i class='bx bxs-show'></i></button>
            </div>
        </div>
        <button class="loginSignup-popup__btn disabled">Create an account</button>
        <div class="info">By creating an account, you agree to our <a href="{{ route('privacy_policy') }}">Privacy
                Policy</a>.
            See our <a href="{{ route('terms_conditions') }}">Terms and Conditions</a>.</div>
    </form>

    <form action="{{ route('auth.perfrom-auth') }}" method="POST"
        class="authForm loginSignup-popup signUpStep for-login">
        <input type="hidden" name="auth_type" value="login">
        @csrf
        <a href="javascript:void(0)" class="loginSignup-popup__close closePopup" title="close"><i
                class='bx bx-x'></i></a>
        <h3>Welcome back!</h3>
        <div class="prev-data">
            <input class="prev-data__email" name="email" value="" readonly>
            <a href="{{ route('index') }}" class="changeEmailButton">Change</a>
        </div>
        <div class="loginSignup-popup__email">
            <div class="password-field">
                <input type="password" placeholder="Password" class="check-fields" name="password" required>
                <button type="button" class="password-field__show"><i class='bx bxs-show'></i></button>
            </div>
        </div>
        <div class="info d-flex align-items-center justify-content-between">
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label for="remember">Remember me</label>
            </div>
            <a href="javascript:void(0)" id="forgotPassword">Forgot password?</a>
        </div>

        <button class="loginSignup-popup__btn disabled">Log in</button>

    </form>


    <form action="{{ route('send_reset_password_link') }}" method="POST"
        class="authForm loginSignup-popup signUpStep for-forgot-password">
        <input type="hidden" name="auth_type" value="login">
        @csrf
        <a href="javascript:void(0)" class="loginSignup-popup__close closePopup" title="close"><i
                class='bx bx-x'></i></a>
        <h3>Reset Password
        </h3>
        <p>Enter the email address associated with your account and we'll send you a link to reset your password.

        </p>
        <div class="loginSignup-popup__email">

            <input type="email" placeholder="Email address" class="check-fields" name="email" required>

        </div>
        <button class="loginSignup-popup__btn disabled">Send Reset Link</button>

    </form>
</div>




<header class="header">
    <div class="container">
        <div class="header-main">
            <div class="header-content">
                <div class="header-logo">
                    <a href="{{ route('index') }}"> <img
                            src='{{ asset($logo->img_path ?? 'assets/images/logo (1).webp') }}' alt='image'
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
                        @if (!Auth::check())
                            <a href="javascript:void(0)" title="Become a supplier"
                                class="item__become-supplier loginBtn">
                                <span><b>Login</b> or <b> SignUp </b></span>
                            </a>
                        @else
                            <a href="{{ route('auth.logout') }}"
                                onclick="return confirm('Are you sure you want to Logout?')" title="Logout"
                                class="item__become-supplier">
                                <span><b>Logout</b></span>
                            </a>
                        @endif
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
        <img alt="Logo" class="imgFluid" src="{{ asset('frontend/assets/images/logo (1).webp') }}">
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
