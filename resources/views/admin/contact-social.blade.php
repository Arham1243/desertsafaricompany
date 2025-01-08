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

                <form action="{{ route('admin.saveSocialInfo') }}" method="POST" class="custom-form">
                    @csrf
                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-fields">
                                <label class="title">Facebook<span class="text-danger">*</span>:</label>
                                <input type="url" name="FACEBOOK" class="field"
                                    value="{{ $config['FACEBOOK'] ?? '' }} " placeholder="Enter Facebook Address">
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-fields">
                                <label class="title">Instagram<span class="text-danger">*</span>:</label>
                                <input type="url" name="INSTAGRAM" class="field"
                                    value="{{ $config['INSTAGRAM'] ?? '' }}" placeholder="Enter Instagram Address" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-fields">
                                <label class="title">Twitter<span class="text-danger">*</span>:</label>
                                <input type="url" name="TWITTER" class="field" value="{{ $config['TWITTER'] ?? '' }}"
                                    placeholder="Enter Twitter Address" required>
                            </div>
                        </div>

                        {{-- <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-fields">
                            <label class="title">Youtube<span class="text-danger">*</span>:</label>
                            <div class="relative-div">
                                <input type="url" name="YOUTUBE"  class="field"
                                    value="{{ $config['YOUTUBE'] ?? '' }}" placeholder="Enter Youtube link">
                            </div>
                        </div>
                    </div> --}}

                        <div class="col-12 pt-1 pb-3">
                            <hr>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-fields">
                                <label class="title">Phone<span class="text-danger">*</span>:</label>
                                <div class="relative-div">
                                    <input type="text" name="COMPANYPHONE" class="field"
                                        value="{{ $config['COMPANYPHONE'] ?? '' }}" placeholder="Enter Phone Number"
                                        required>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6 col-12">
                        <div class="form-fields">
                            <label class="title">Phone 2<span class="text-danger">*</span>:</label>
                            <div class="relative-div">
                                <input type="text" name="COMPANYPHONE2"  class="field"
                                    value="{{ $config['COMPANYPHONE2'] ?? '' }}" placeholder="Enter Phone Number">
                            </div>
                        </div>
                    </div> --}}
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-fields">
                                <label class="title">Email<span class="text-danger">*</span>:</label>
                                <div class="relative-div">
                                    <input type="email" name="COMPANYEMAIL" class="field"
                                        value="{{ $config['COMPANYEMAIL'] ?? '' }}" placeholder="Enter Email Address"
                                        required>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-12 col-md-12 col-12">
                        <div class="form-fields">
                            <label class="title">Address<span class="text-danger">*</span>:</label>
                            <div class="relative-div">
                                <input type="text" name="COMPANYADDRESS"  class="field"
                                    value="{{ $config['COMPANYADDRESS'] ?? '' }}" placeholder="Enter Address">
                            </div>
                        </div>
                    </div> --}}


                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-fields">
                            <button class="themeBtn themeBtn--center">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
