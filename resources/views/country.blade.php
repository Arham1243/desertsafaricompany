@extends('layouts.main')
@section('content')
    <div class="country-container">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="country-heading">

                        <div class="location1-content__section--heading">
                            <h2>
                                Middle East
                            </h2>
                        </div>
                    </div>

                    <div class="select-section">
                        <div class="country-selects">
                            <div class="country-select1">
                                <form action="" class="country-select1__form">
                                    <label for="placeType">
                                        Filter by type of place
                                    </label>
                                    <select id="country-select__option" class="country-select-dropdown">
                                        <option value="1">All types </option>
                                        <option value="2">Regions</option>
                                        <option value="2">Countries</option>
                                        <option value="2">Cities</option>
                                        <option value="2">Neighborhoods</option>

                                    </select>
                                </form>
                            </div>
                            <div class="country-select1">
                                <form action="" class="country-select1__form">
                                    <label for="placeType">
                                        Sort places by
                                    </label>
                                    <select id="country-select__option" class="country-select-dropdown">
                                        <option value="1">Most Popular </option>
                                        <option value="2">A - Z</option>
                                        <option value="2">Z - A</option>

                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="country-card">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/173.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>

                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src='assets/images/132 (2).webp' alt='image' class='imgFluid'
                                            loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/sky-dive-600.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/156.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/173.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/156.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/elite-restaurant-alanya-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/49.webp') }}" alt='image'
                                            class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="country-card_content">
                                    <div class="country-card__img">
                                        <img src="{{ asset('frontend/assets/images/dubai-palm-helicopter-tour-600.webp') }}"
                                            alt='image' class='imgFluid' loading='lazy'>
                                    </div>
                                    <a href="javascript:void(0)" class="country-card__details">
                                        <div class="country-card__title">
                                            MIDDLE EAST
                                        </div>
                                        <div class="country-name">Iran
                                        </div>
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="pagination mt-4">
                        <a href="#" class="pagination-icon"><i class="bx bx-chevron-left"></i></a>
                        <a href="#" class="pagination-num active">1</a>
                        <a href="#" class="pagination-num">2</a>
                        <a href="#" class="pagination-num">3</a>
                        <a href="#" class="pagination-icon"><i class="bx bx-chevron-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="country-Advertisement text-center">
                        <div class="Advertisement-heading">
                            Advertisement
                        </div>
                        <div class="country-Advertisement__img">
                            <img src="{{ asset('frontend/assets/images/15761105775232956155.png') }}" alt='image'
                                class='imgFluid' loading='lazy'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="country-Advertisement2">
        <div class="container">
            <div class="Advertisement-heading">
                Advertisement
            </div>
            <div class="country-Advertisement2__img">
                <img src="{{ asset('frontend/assets/images/3510117500144793346.png') }}" alt='image' class='imgFluid'
                    loading='lazy'>
            </div>
        </div>
    </div>
@endsection
