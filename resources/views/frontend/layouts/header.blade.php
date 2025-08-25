@if ((int) $settings->get('is_enabled_cookie_bar') === 1)
    <div class="cookie-consent hidden" id="cookie-consent">
        <div class="container">
            <div class="cookie-consent__container">
                <p class="cookie-consent__text"
                    @if ($settings->get('cookie_bar_text_color')) style="color: {{ $settings->get('cookie_bar_text_color') }};" @endif>
                    {{ $settings->get('cookie_bar_text') ?? 'We use cookies to improve your experience. You can choose to accept all or reject non-essential cookies.' }}
                </p>
                <div class="cookie-consent__buttons">
                    <button type="button" class="cookie-consent__button cookie-consent__button--accept"
                        onclick="handleCookieConsent(true)"
                        @if ($settings->get('cookie_bar_accept_bg_color') || $settings->get('cookie_bar_accept_text_color')) style="
                        @if ($settings->get('cookie_bar_accept_bg_color')) background-color: {{ $settings->get('cookie_bar_accept_bg_color') }}; @endif
                        @if ($settings->get('cookie_bar_accept_text_color')) color: {{ $settings->get('cookie_bar_accept_text_color') }}; @endif "
                            @endif>
                        {{ $settings->get('cookie_bar_accept_text') ?? 'Accept All' }}
                    </button>
                    <button type="button" class="cookie-consent__button cookie-consent__button--reject"
                        onclick="handleCookieConsent(false)"
                        @if ($settings->get('cookie_bar_reject_bg_color') || $settings->get('cookie_bar_reject_text_color')) style="
                        @if ($settings->get('cookie_bar_reject_bg_color')) background-color: {{ $settings->get('cookie_bar_reject_bg_color') }}; @endif
                        @if ($settings->get('cookie_bar_reject_text_color')) color: {{ $settings->get('cookie_bar_reject_text_color') }}; @endif "
                            @endif>
                        {{ $settings->get('cookie_bar_reject_text') ?? 'Reject' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

@php
    $headerLogo = App\Models\Setting::where('key', 'header_logo')->first()->value ?? null;
    $headerLogoAltText = App\Models\Setting::where('key', 'header_logo_alt_text')->first()->value ?? null;
    $globalWhatsappNumber = App\Models\Setting::where('key', 'whatsapp_number')->first()->value ?? null;
@endphp
@if ($globalWhatsappNumber && preg_match('/^\+?\d{10,15}$/', $globalWhatsappNumber))
    <a href="tel:{{ $globalWhatsappNumber }}" class="whatsapp-contact"><i class='bx bxl-whatsapp'></i></a>
@endif
<header class="header" id="header">
    <div class="container">
        <div class="header-main">
            <div class="header-content">
                <div class="header-logo">
                    <a href="{{ route('frontend.index') }}">
                        <img src="{{ asset($headerLogo ?? 'admin/assets/images/placeholder-logo.png') }}"
                            alt="{{ $headerLogoAltText ?? 'logo' }}" class='imgFluid' width="112.03"
                            height="33.69"></a>
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
                    <li class="header-btns__item login-li">
                        @include('frontend.vue.main', [
                            'appId' => 'login-popup',
                            'appComponent' => 'popup',
                            'appJs' => 'popup',
                        ])
                    </li>
                    <li class="header-btns__item">
                        <a href="{{ route('tours.favorites.index') }}" title="Wishlist" class="li__link">

                            <div class="header-btns__icon">
                                <i class='bx bx-heart'></i>
                            </div>
                            <span>Wishlist</span>
                        </a>
                    </li>
                    <li class="header-btns__item">
                        <a href="{{ route('cart.index') }}" title="Cart" class="li__link">
                            <div class="header-btns__icon">
                                @if (Auth::check() && isset($cart['tours']) && count($cart['tours']) > 0)
                                    <span class="total">
                                        {{ count($cart['tours']) }}
                                    </span>
                                @endif
                                <i class='bx bx-cart'></i>
                            </div>
                            <span>Cart</span>
                        </a>
                    </li>
                    @if (Auth::check())
                        <li class="header-btns__item">
                            <a href="{{ route('user.dashboard') }}" title="Profile" class="li__link">
                                <div class="header-btns__icon">
                                    <i class='bx bx-user'></i>
                                </div>
                                <span>Profile</span>
                            </a>
                            <div class="drop-down">
                                <ul class="drop-down__list">
                                    @if (Auth::check())
                                        <li>
                                            <a href="{{ route('user.profile.changePassword') }}"><i
                                                    class='bx bx-lock'></i>Change Password</a>
                                        </li>
                                        <li>
                                            <a onclick="return confirm('Are you sure you want to Logout?')"
                                                href="{{ route('auth.logout') }}"><i
                                                    class='bx bx-log-out-circle'></i>Logout</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>


            <a href="javascript:void(0)" class="header-main__menu" onclick="openSideBar()">
                <i class="bx bx-menu"></i>
            </a>

        </div>

    </div>


</header>

<div class="sideBar" id="sideBar">
    <a href="javascript:void(0)" class="sideBar__close" onclick="closeSideBar()"><i class='bx bx-x'></i></a>
    <a href="{{ route('frontend.index') }}" class="sideBar__logo">
        <img class="imgFluid" src="{{ asset($headerLogo ?? 'admin/assets/images/placeholder-logo.png') }}"
            alt='{{ $headerLogoAltText ?? 'logo' }}'>
    </a>
    <ul class="sideBar__nav">
        <li><a href="{{ route('tours.index') }}">Tours</a></li>
        <li><a href="#">Local Guide</a></li>
        <li><a href="#">Help</a></li>
    </ul>
    <a href="javascript:void(0)" class="primary-btn w-75 mx-auto mt-4" open-vue-login-popup>
        <span><b>Login</b> or <b> SignUp </b></span>
    </a>
</div>
