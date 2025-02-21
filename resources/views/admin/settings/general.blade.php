@extends('admin.layouts.main')
@section('content')
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('admin.tours.create') }}
            <div class="custom-sec custom-sec--form">
                <div class="custom-sec__header">
                    <div class="section-content">
                        <h3 class="heading">Settings</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    @include('admin.settings.layouts.sidebar')
                </div>
                <div class="col-md-9">
                    <form action="{{ route('admin.settings.update', ['resource' => 'general']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $selectedPage = $settings->get('page_for_homepage');
                            $headerLogo = $settings->get('header_logo');
                            $headerLogoAltText = $settings->get('header_logo_alt_text');
                            $footerLogo = $settings->get('footer_logo');
                            $footerLogoAltText = $settings->get('footer_logo_alt_text');
                            $favicon = $settings->get('favicon');
                            $timezones = timezone_identifiers_list();
                            $selectedTimezone = $settings->get('app_timezone');
                            $appName = $settings->get('app_name');
                            $selectedCurrency = $settings->get('app_currency');
                            $footerCopyrightText = $settings->get('footer_copyright_text');
                            $currencies = [
                                '$' => ['name' => 'US Dollar', 'code' => 'USD'],
                                '€' => ['name' => 'Euro', 'code' => 'EUR'],
                                '£' => ['name' => 'British Pound', 'code' => 'GBP'],
                                '¥' => ['name' => 'Japanese Yen', 'code' => 'JPY'],
                                '₹' => ['name' => 'Indian Rupee', 'code' => 'INR'],
                                '₨' => ['name' => 'Pakistan Rupee', 'code' => 'PKR'],
                                '₣' => ['name' => 'Swiss Franc', 'code' => 'CHF'],
                                '₽' => ['name' => 'Russian Ruble', 'code' => 'RUB'],
                                '₺' => ['name' => 'Turkish Lira', 'code' => 'TRY'],
                                '₩' => ['name' => 'South Korean Won', 'code' => 'KRW'],
                                '₳' => ['name' => 'Argentine Peso', 'code' => 'ARS'],
                                '₫' => ['name' => 'Vietnamese Dong', 'code' => 'VND'],
                                '₪' => ['name' => 'Israeli New Shekel', 'code' => 'ILS'],
                                '₴' => ['name' => 'Ukrainian Hryvnia', 'code' => 'UAH'],
                                '฿' => ['name' => 'Thai Baht', 'code' => 'THB'],
                                'R$' => ['name' => 'Brazilian Real', 'code' => 'BRL'],
                                '₵' => ['name' => 'Ghanaian Cedi', 'code' => 'GHS'],
                                '₡' => ['name' => 'Costa Rican Colón', 'code' => 'CRC'],
                                'AED' => ['name' => 'UAE Dirham', 'code' => 'AED'],
                            ];
                        @endphp
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">App Settings</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">App Name</label>
                                            <input type="text" name="app_name" value="{{ $appName ?? '' }}"
                                                class="field">
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <div class="form-fields">
                                            <label class="title">App Currency</label>
                                            <select name="app_currency" placeholder="Select" class="select2-select">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($currencies as $code => $currency)
                                                    <option value="{{ $code }}"
                                                        {{ $selectedCurrency == $code ? 'selected' : '' }}>
                                                        {{ $currency['name'] }} ({{ $currency['code'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title">App timezone</label>
                                            <select name="app_timezone" placeholder="Select" class="select2-select">
                                                <option value="" disabled selected>Select</option>
                                                @foreach ($timezones as $timezone)
                                                    <option value="{{ $timezone }}"
                                                        {{ $selectedTimezone == $timezone ? 'selected' : '' }}>
                                                        {{ $timezone }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Page for Homepage</div>
                            </div>
                            <div class="form-box__body">
                                <div class="form-fields">
                                    <select name="page_for_homepage" placeholder="Select" class="select2-select">
                                        <option value="" selected disabled>Select</option>
                                        @foreach ($pages as $page)
                                            <option value="{{ $page->id }}"
                                                {{ $selectedPage == $page->id ? 'selected' : '' }}>
                                                {{ $page->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Logo</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-fields">
                                            <label class="title">Header Logo</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($headerLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="header_logo" data-error="Feature Image"
                                                            id="header_logo" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="header_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($headerLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($headerLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($headerLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="header_logo_alt_text" class="field"
                                                            placeholder="Enter alt text" value="{{ $headerLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 115 &times; 35
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-fields">
                                            <label class="title">Footer Logo</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($footerLogo) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="footer_logo" data-error="Feature Image"
                                                            id="footer_logo" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="footer_logo" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($footerLogo) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($footerLogo) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($footerLogo ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                        <input type="text" name="footer_logo_alt_text" class="field"
                                                            placeholder="Enter alt text"
                                                            value="{{ $footerLogoAltText }}">
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 115 &times; 35
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-fields">
                                            <label class="title">Favicon</label>
                                            <div class="upload" data-upload>
                                                <div class="upload-box-wrapper">
                                                    <div class="upload-box {{ empty($favicon) ? 'show' : '' }}"
                                                        data-upload-box>
                                                        <input type="file" name="favicon" data-error="Feature Image"
                                                            id="favicon" class="upload-box__file d-none"
                                                            accept="image/*" data-file-input>
                                                        <div class="upload-box__placeholder"><i class='bx bxs-image'></i>
                                                        </div>
                                                        <label for="favicon" class="upload-box__btn themeBtn">Upload
                                                            Image</label>
                                                    </div>
                                                    <div class="upload-box__img {{ !empty($favicon) ? 'show' : '' }}"
                                                        data-upload-img>
                                                        <button type="button" class="delete-btn" data-delete-btn><i
                                                                class='bx bxs-trash-alt'></i></button>
                                                        <a href="{{ asset($favicon) }}" class="mask"
                                                            data-fancybox="gallery">
                                                            <img src="{{ asset($favicon ?? 'admin/assets/images/loading.webp') }}"
                                                                alt="Uploaded Image" class="imgFluid" data-upload-preview>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div data-error-message class="text-danger mt-2 d-none text-center">Please
                                                    upload a
                                                    valid image file
                                                </div>
                                            </div>
                                            <div class="dimensions text-center mt-3">
                                                <strong>Dimensions:</strong> 16 &times; 16
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-box">
                            <div class="form-box__header">
                                <div class="title">Footer</div>
                            </div>
                            <div class="form-box__body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-fields">
                                            <label class="title">Footer Copyright Text</label>
                                            <input type="text" name="footer_copyright_text"
                                                value="{{ $footerCopyrightText ?? '' }}" class="field">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="themeBtn ms-auto ">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
