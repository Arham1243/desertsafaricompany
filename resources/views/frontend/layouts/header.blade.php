@if ((int) $settings->get('is_enabled_cookie_bar') === 1)
    <div class="cookie-consent hidden" id="cookie-consent"
        @if ($settings->get('cookie_bar_bg_color')) style="background-color: {{ $settings->get('cookie_bar_bg_color') }};" @endif>
        <div class="container">
            <div class="cookie-consent__container">
                <p class="cookie-consent__text"
                    @if ($settings->get('cookie_bar_text_color')) style="color: {{ $settings->get('cookie_bar_text_color') }};" @endif>
                    {!! $settings->get('cookie_bar_text') ??
                        'We use cookies to improve your experience. You can choose to accept all or reject non-essential cookies.' !!}
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

@if ((int) $settings->get('is_global_cta_enabled') === 1)
    <a href="tel:{{ sanitizePhoneNumber($settings->get('global_cta_number')) }}" class="global-cta"
        style="
        @if ($settings->get('global_cta_background_color')) background-color: {{ $settings->get('global_cta_background_color') }}; @endif
        @if ($settings->get('global_cta_text_color')) color: {{ $settings->get('global_cta_text_color') }}; @endif
    ">
        <div class="global-cta__number">{{ $settings->get('global_cta_number') }}</div>
        <div class="global-cta__text">{{ $settings->get('global_cta_text') }}</div>
    </a>
@endif

@if ((int) $settings->get('is_global_whatsapp_number_enabled') === 1)
    @php
        $dialCode = $settings->get('global_whatsapp_number_dial_code');
        $number = $settings->get('global_whatsapp_number');
        $globalWhatsappNumber = '+' . $dialCode . $number;
    @endphp

    <a target="_blank"
        href="https://api.whatsapp.com/send?phone={{ $globalWhatsappNumber }}&text=I%27m%20interested%20in%20your%20services"
        class="whatsapp-contact" style="display: flex !important;">
        <i class='bx bxl-whatsapp'></i>
    </a>
@endif

@php
    $headerLogo = App\Models\Setting::where('key', 'header_logo')->first()->value ?? null;
    $headerLogoAltText = App\Models\Setting::where('key', 'header_logo_alt_text')->first()->value ?? null;
@endphp
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
                    @php
                        $header_menu = getSortedHeaderMenu($settings->get('header_menu'));
                    @endphp
                    @if (!empty($header_menu))
                        <ul>
                            @foreach ($header_menu as $menu)
                                <li><a href="{{ sanitizedLink($menu['url']) }}">{{ $menu['name'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
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
                                            <a href="{{ route('user.bookings.index') }}"><i
                                                    class='bx bx-receipt'></i>My Bookings</a>
                                        </li>
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
    @if (!empty($header_menu))
        <ul class="sideBar__nav">
            @foreach ($header_menu as $menu)
                <li><a href="{{ sanitizedLink($menu['url']) }}">{{ $menu['name'] }}</a></li>
            @endforeach
        </ul>
    @endif
    @if ($settings->get('is_registration_enabled') && (int) $settings->get('is_registration_enabled') === 1)
        @php
            $is_enabled_login_button =
                $settings->get('is_enabled_login_button') && (int) $settings->get('is_enabled_login_button') === 1;
            $login_button_text_color = $settings->get('login_button_text_color')
                ? $settings->get('login_button_text_color')
                : null;
            $login_button_text = $settings->get('login_button_text') ? $settings->get('login_button_text') : null;
            $login_button_bg_color = $settings->get('login_button_bg_color')
                ? $settings->get('login_button_bg_color')
                : null;
        @endphp
        @if ($is_enabled_login_button)
            <a href="javascript:void(0)" open-vue-login-popup class="primary-btn w-75 mx-auto mt-4"
                @if ($login_button_text_color || $login_button_bg_color) style="{{ $login_button_text_color ? "color: {$login_button_text_color};" : '' }} {{ $login_button_bg_color ? "background-color: {$login_button_bg_color};" : '' }}" @endif>
                <span><b>{{ $login_button_text }}</b></span>
            </a>
        @endif
    @endif
</div>

@if (Auth::check())
    @if (!Auth::user()->hasCompletedProfile())
        @php
            $countries = [
                'afghanistan',
                'albania',
                'algeria',
                'american samoa',
                'andorra',
                'angola',
                'anguilla',
                'antigua and barbuda',
                'argentina',
                'armenia',
                'aruba',
                'australia',
                'austria',
                'azerbaijan',
                'bahamas',
                'bahrain',
                'bangladesh',
                'barbados',
                'belarus',
                'belgium',
                'belize',
                'benin',
                'bermuda',
                'bhutan',
                'bolivia',
                'bosnia and herzegovina',
                'botswana',
                'brazil',
                'british indian ocean territory',
                'brunei',
                'bulgaria',
                'burkina faso',
                'burundi',
                'cambodia',
                'cameroon',
                'canada',
                'cape verde',
                'cayman islands',
                'central african republic',
                'chad',
                'chile',
                'china',
                'colombia',
                'comoros',
                'congo',
                'costa rica',
                'croatia',
                'cuba',
                'cyprus',
                'czech republic',
                'denmark',
                'djibouti',
                'dominica',
                'dominican republic',
                'ecuador',
                'egypt',
                'el salvador',
                'equatorial guinea',
                'eritrea',
                'estonia',
                'eswatini',
                'ethiopia',
                'fiji',
                'finland',
                'france',
                'gabon',
                'gambia',
                'georgia',
                'germany',
                'ghana',
                'greece',
                'grenada',
                'guatemala',
                'guinea',
                'guinea bissau',
                'guyana',
                'haiti',
                'honduras',
                'hungary',
                'iceland',
                'india',
                'indonesia',
                'iran',
                'iraq',
                'ireland',
                'israel',
                'italy',
                'jamaica',
                'japan',
                'jordan',
                'kazakhstan',
                'kenya',
                'kiribati',
                'kuwait',
                'kyrgyzstan',
                'laos',
                'latvia',
                'lebanon',
                'lesotho',
                'liberia',
                'libya',
                'liechtenstein',
                'lithuania',
                'luxembourg',
                'madagascar',
                'malawi',
                'malaysia',
                'maldives',
                'mali',
                'malta',
                'marshall islands',
                'mauritania',
                'mauritius',
                'mexico',
                'micronesia',
                'moldova',
                'monaco',
                'mongolia',
                'montenegro',
                'morocco',
                'mozambique',
                'myanmar',
                'namibia',
                'nauru',
                'nepal',
                'netherlands',
                'new zealand',
                'nicaragua',
                'niger',
                'nigeria',
                'north macedonia',
                'norway',
                'oman',
                'pakistan',
                'palau',
                'palestine',
                'panama',
                'papua new guinea',
                'paraguay',
                'peru',
                'philippines',
                'poland',
                'portugal',
                'qatar',
                'romania',
                'russia',
                'rwanda',
                'saudi arabia',
                'senegal',
                'serbia',
                'seychelles',
                'sierra leone',
                'singapore',
                'slovakia',
                'slovenia',
                'solomon islands',
                'somalia',
                'south africa',
                'spain',
                'sri lanka',
                'sudan',
                'suriname',
                'sweden',
                'switzerland',
                'syria',
                'taiwan',
                'tajikistan',
                'tanzania',
                'thailand',
                'timor leste',
                'togo',
                'tonga',
                'trinidad and tobago',
                'tunisia',
                'turkey',
                'turkmenistan',
                'tuvalu',
                'uganda',
                'ukraine',
                'united arab emirates',
                'united kingdom',
                'united states',
                'uruguay',
                'uzbekistan',
                'vanuatu',
                'venezuela',
                'vietnam',
                'yemen',
                'zambia',
                'zimbabwe',
            ];
            sort($countries);
        @endphp

        <div class="global-popup-wrapper detail-popup open" id="user-details-popup">
            <div class="global-popup">
                <div class="global-popup__header">
                    <div class="title">Pleae complete your profile</div>
                    <div class="close-icon popup-close-icon" id="close-popup" detail-popup-close>
                        <i class="bx bx-x"></i>
                    </div>
                </div>

                <div class="global-popup__content">
                    <form action="{{ route('frontend.update-profile') }}" method="POST">
                        @csrf
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="field">
                                    <label class="title">Phone number</label>
                                    <input id="phone_number" type="text" name="phone" class="field"
                                        inputmode="numeric" pattern="[0-9]*"
                                        value="{{ old('phone', Auth::user()->phone) }}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field">
                                    <label class="title">Age</label>
                                    <input id="age" type="text" name="age" class="field"
                                        value="{{ old('age', Auth::user()->age) }}">
                                    @error('age')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field">
                                    <label class="title">Country</label>
                                    <select id="country" name="country" required>
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country }}"
                                                {{ strtolower(Auth::user()->country) == strtolower($country) ? 'selected' : '' }}>
                                                {{ ucwords($country) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field">
                                    <label class="title">City</label>
                                    <input value="{{ old('city', Auth::user()->city) }}" id="city"
                                        type="text" required name="city">
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="field">
                                    <button class="ms-auto primary-btn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endif
