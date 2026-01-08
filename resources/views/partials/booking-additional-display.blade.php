@php
    $bookingAdditionalSelections = $tourDataDetails['booking_additional_selections'] ?? null;

    function formatAMPM($time)
    {
        if (!$time) {
            return '';
        }
        [$hours, $minutes] = explode(':', $time);
        $hours = (int) $hours;
        $ampm = $hours >= 12 ? 'PM' : 'AM';
        $hours = $hours % 12 ?: 12;
        return $hours . ':' . $minutes . ' ' . $ampm;
    }

    function formatSelection($value)
    {
        // Timeslot / from-to array
        if (is_array($value) && isset($value[0], $value[1])) {
            return formatAMPM($value[0]) . ' - ' . formatAMPM($value[1]);
        }

        // Simple string

        if (is_string($value)) {
            // Matches HH:mm or H:mm
            if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $value)) {
                return formatAMPM($value);
            }
            return $value;
        }

        return 'N/A';
    }

    function formatNonActivities($selection)
    {
        if (is_array($selection) && isset($selection[0], $selection[1])) {
            // Timeslot / from-to array
            return formatAMPM($selection[0]) . ' - ' . formatAMPM($selection[1]);
        } elseif (is_array($selection) && isset($selection['city_name'], $selection['meeting_point'])) {
            // City / meeting_point
            return $selection['city_name'] . ' - ' . $selection['meeting_point'];
        } elseif (is_array($selection) && isset($selection['location_type'], $selection['address'])) {
            // Pickup location
            return $selection['location_type'] . ' - ' . $selection['address'];
        } elseif (is_string($selection)) {
            // Simple string
            if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d)$/', $selection)) {
                return formatAMPM($selection);
            }
            return $selection;
        }
        return 'N/A';
    }
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
                    @if (is_array($selection))
                        <ul class="list-group">
                            @foreach ($selection as $key => $value)
                                @continue(str_contains($key, '_id'))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                    <span>{{ formatSelection($value) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info mb-0">
                            <strong>Type:</strong> Activities (Single Selection - Informational Only)
                        </div>
                    @endif
                @else
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}:</strong>
                            <span>{{ formatNonActivities($selection) }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
