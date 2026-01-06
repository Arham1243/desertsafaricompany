@php
    $bookingAdditionalSelections = $tourDataDetails['booking_additional_selections'] ?? null;
@endphp

@if ($bookingAdditionalSelections && isset($bookingAdditionalSelections['type']))
    <div class="col-md-12 col-12 mb-4">
        <div class="form-fields">
            <label class="title">Additional Information:</label>
            <div class="mt-2">
                @php
                    $type = $bookingAdditionalSelections['type'];
                    $selection = $bookingAdditionalSelections['selection'] ?? null;
                @endphp

                @if ($type === 'activities')
                    {{-- Activities Type --}}
                    @if (is_array($selection))
                        {{-- Multiple Selection --}}
                        <ul class="list-group">
                            @foreach ($selection as $key => $value)
                                @if ($value)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                        <span>{{ $value }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        {{-- Single Selection - Just informational, no user selection --}}
                        <div class="alert alert-info mb-0">
                            <strong>Type:</strong> Activities (Single Selection - Informational Only)
                        </div>
                    @endif
                @else
                    {{-- Non-Activities Types --}}
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}:</strong>
                            <span>
                                {{ $selection['city_name'] ?? 'N/A' }} - {{ $selection['meeting_point'] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
