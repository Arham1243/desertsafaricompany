@extends('frontend.layouts.main')
@section('content')
    @if ($tours->isNotEmpty())
        <div class="tours section-paddding">
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
                        <div class="col-md-3">
                            <x-tour-card :tour="$tour" style="style3" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="section-paddding">
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
