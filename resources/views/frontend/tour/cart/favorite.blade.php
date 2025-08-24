@extends('frontend.layouts.main')
@section('content')
    <div class="cart section-padding">
        <div class="container">
            @if ($tours->isNotEmpty())
                <div class="row">
                    <div class="section-content">
                        <div class="heading">
                            You have {{ count($tours) }} item{{ count($tours) > 1 ? 's' : '' }} in your Wishlist
                        </div>
                    </div>
                    <div class="col-md-12">
                        @foreach ($tours as $tour)
                            <div class="cart__product">
                                <a href="{{ $tour->detail_url }}" class="cart__productImg">
                                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                        loading="lazy">
                                </a>
                                <div class="cart__productContent">
                                    <div>
                                        <div class="cart__productDescription">
                                            <h4>{{ $tour->title }}</h4>
                                            <div class="d-flex gap-3 mt-3">
                                                <form action="{{ route('tours.favorites.remove', $tour->id) }}"
                                                    method="post">
                                                    @csrf
                                                    <button class="primary-btn"
                                                        onclick="return confirm('Are you sure you want to remove this tour from your wishlist?')"><i
                                                            class='bx bxs-trash-alt'></i>Remove
                                                        From Wishlit</button>
                                                </form>
                                                <a href="{{ $tour->detail_url }}" class="primary-btn"><i
                                                        class='bx bxs-cart'></i>Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{ route('locations.country', 'ae') }}" class="primary-btn mx-auto mt-4"> Explore More </a>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <div class="section-content">
                        <div class="heading">
                            Your wishlist is currently empty
                        </div>
                    </div>
                    <p>Don't worry! Explore our exciting <strong><a class="link-primary"
                                href="{{ route('locations.country', 'ae') }}">tours</a></strong> and add some to your
                        wishlist.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
