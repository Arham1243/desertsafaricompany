@php
    $typeFileMap = [
        'inner-page' => 'inner',
        'water-activity' => 'water',
        'boat-trip' => 'boat',
        'bus-trip' => 'bus',
    ];
    
    $typeFile = $typeFileMap[$schemaType] ?? null;
@endphp

@include('admin.schema.tour-types.' . $typeFile)
