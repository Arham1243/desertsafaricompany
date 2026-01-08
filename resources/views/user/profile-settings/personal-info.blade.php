@extends('user.layouts.main')
@section('content')
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
    <div class="col-md-12">
        <div class="dashboard-content">
            {{ Breadcrumbs::render('user.profile.index') }}
            <form action="{{ route('user.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                id="validation-form">
                @csrf
                @method('PATCH')
                <div class="custom-sec custom-sec--form">
                    <div class="custom-sec__header">
                        <div class="section-content">
                            <h3 class="heading">Edit: Personal Information</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-wrapper">
                            <div class="form-box">
                                <div class="form-box__header">
                                    <div class="title">Information</div>
                                </div>
                                <div class="form-box__body">
                                    <div class="row">
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Name</label>
                                                <input type="text" name="full_name" class="field"
                                                    value="{{ old('full_name', $user->full_name) }}">
                                                @error('full_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Email</label>
                                                <input type="email" readonly class="field"
                                                    value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Phone</label>
                                                <input type="text" name="phone" class="field"
                                                    value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                               <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Age</label>
                                                <input type="text" name="age" class="field"
                                                    value="{{ old('age', $user->age) }}">
                                                @error('age')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Country</label>
                                                <select class="field select2-select" name="country" id="country-select">
                                                    <option value="" selected disabled>Select</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country }}"
                                                            {{ strtolower($user->country) == strtolower($country) ? 'selected' : '' }}>
                                                            {{ ucwords($country) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-4">
                                            <div class="form-fields">
                                                <label class="title">City</label>
                                                <input type="text" name="city" class="field"
                                                    value="{{ old('city', $user->city) }}">
                                                @error('city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <div class="form-fields">
                                                <label class="title">Profile Photo</label>
                                                <div class="position-relative d-inline-block mt-2">
                                                    <img id="avatarPreview"
                                                        src="{{ $user->avatar ? $user->avatar : asset('user/assets/images/placeholder-user.png') }}"
                                                        class="rounded-circle border border-secondary"
                                                        style="width: 140px; height: 140px; object-fit: cover; cursor: pointer;"
                                                        onclick="document.getElementById('avatarInput').click()">

                                                    <input type="file" id="avatarInput" name="avatar" accept="image/*"
                                                        style="display: none;" onchange="previewAvatar(this)">
                                                </div>
                                            </div>
                                        </div>
                                        <button class="themeBtn ms-auto mt-4">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
