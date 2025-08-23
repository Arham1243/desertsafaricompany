<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'bx bxs-home',
        'route' => route('user.dashboard'),
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
