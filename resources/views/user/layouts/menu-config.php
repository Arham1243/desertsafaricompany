<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bx bxs-home',
        'route' => route('user.dashboard'),
    ],
    [
        'title' => 'My Bookings',
        'icon' => 'bx bxs-calendar-check',
        'route' => route('user.bookings.index'),
    ],
    [
        'title' => 'Profile Settings',
        'icon' => 'bx bxs-cog',
        'submenu' => [
            [
                'title' => 'Personal Information',
                'icon' => 'bx bxs-contact',
                'route' => route('user.profile.index'),
            ],
            [
                'title' => 'Change Password',
                'icon' => 'bx bx-key',
                'route' => route('user.profile.changePassword'),
            ],
        ],
    ],
];
