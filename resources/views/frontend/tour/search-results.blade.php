@extends('frontend.layouts.main')
@section('content')
    @if (!$tours->isEmpty())
        <div class=tours>
            <div class=container>
                <div class=tours-content>
                    <div class="section-content">
                        <div class="heading">
                            We found {{ count($tours) }} tour{{ count($tours) > 1 ? 's' : '' }} for you
                            <br>
                            <small>
                                Showing results from
                                @if ($resourceType === 'city')
                                    City: {{ $resourceName }}
                                @elseif ($resourceType === 'country')
                                    Country: {{ $resourceName }}
                                @elseif ($resourceType === 'category')
                                    Category: {{ $resourceName }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row pt-3">
                    @foreach ($tours as $tour)
                        <div class=col-md-3>
                            <div class=card-content>
                                <a href=# class=card_img>
                                    <img data-src={{ asset($tour->featured_image ?? 'admin/assets/images/placeholder.png') }}
                                        alt="{{ $tour->featured_image_alt_text ?? 'image' }}" class="imgFluid lazy"
                                        loading="lazy">
                                    <div class=price-details>
                                        <div class=price>
                                            <span>
                                                <b>€30</b>
                                                From
                                            </span>
                                        </div>
                                        <div class=heart-icon>
                                            <div class=service-wishlis>
                                                <i class="bx bx-heart"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class=card-details>
                                    <a href=# class=card-title>{{ $tour->title }}</a>
                                    @if (!$tour->cities->isEmpty())
                                        <div class=location-details><i class="bx bx-location-plus"></i>
                                            @if ($tour->cities->isNotEmpty())
                                                {{ $tour->cities[0]->name }}
                                            @endif
                                        </div>
                                    @endif
                                    <div class=card-rating>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star yellow-star"></i>
                                        <i class="bx bxs-star"></i>
                                        <span>1 Reviews</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="my-5">
            <div class="container">
                <div class="text-center">
                    <div class="section-content">
                        <div class="heading">
                            Oops! No tours match your search for
                            @if ($resourceType === 'city')
                                City: {{ $resourceName }}
                            @elseif ($resourceType === 'country')
                                Country: {{ $resourceName }}
                            @elseif ($resourceType === 'category')
                                Category: {{ $resourceName }}
                            @endif
                        </div>
                    </div>
                    <p>We couldn't find any tours based on your search. You can <strong><a class="link-primary"
                                href="{{ url()->previous() }}">Try Again</a></strong> with different criteria or explore
                        other options.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
